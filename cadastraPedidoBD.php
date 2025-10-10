<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain; charset=UTF-8');
    require_once 'conexao.php';

    $itensJson = $_POST['itens'] ?? '[]';
    $itens = json_decode($itensJson, true);
    if (!is_array($itens) || count($itens) === 0) {
        echo 'erro';
        exit;
    }

    $cliente = trim($_POST['cliente'] ?? '');
    $clienteIdPost = isset($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : 0;
    $horario = trim($_POST['horario'] ?? '');
    $receb = strtoupper(trim($_POST['recebimento'] ?? 'RETIRADA'));
    $tipoPedido = ($receb === 'ENTREGA') ? 'ENTREGA' : 'RETIRADA';

    // Campos de entrega recebidos (quando aplicável)
    $enderecoTxt = trim($_POST['endereco'] ?? '');
    $cepTxt = preg_replace('/\D/', '', (string)($_POST['cep'] ?? ''));
    $numeroTxt = trim((string)($_POST['numero'] ?? ''));
    $complTxt = trim((string)($_POST['complemento'] ?? ''));

    // Monta uma string única de endereço para salvar em tbPedido.endereco
    $enderecoPedido = '';
    if ($tipoPedido === 'ENTREGA') {
        $partes = [];
        if ($enderecoTxt !== '') $partes[] = $enderecoTxt; // Rua, bairro, cidade - UF
        if ($numeroTxt !== '') $partes[] = 'Nº ' . $numeroTxt;
        if ($complTxt !== '') $partes[] = $complTxt;
        $linha = implode(', ', $partes);
        if ($cepTxt !== '') {
            $linha .= ($linha ? ' — ' : '') . 'CEP ' . $cepTxt;
        }
        $enderecoPedido = $linha;
    }

    // Converte horário para HH:MM:SS ou NULL
    $horarioTime = null;
    if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $horario)) {
        $horarioTime = (strlen($horario) === 5) ? ($horario . ':00') : $horario;
    }

    // Define o usuário do pedido: prioriza cliente_id; caso contrário tenta por nome; sem fallback: exige cliente existente
    $codUsuario = 0;

    if ($clienteIdPost > 0) {
        if ($stmt = $conn->prepare('SELECT id_usuario FROM tbUsuario WHERE id_usuario = ? LIMIT 1')) {
            $stmt->bind_param('i', $clienteIdPost);
            if ($stmt->execute()) {
                $stmt->bind_result($uid);
                if ($stmt->fetch()) { $codUsuario = (int)$uid; }
            }
            $stmt->close();
        }
    } else if ($cliente !== '') {
        if ($stmt = $conn->prepare('SELECT id_usuario FROM tbUsuario WHERE nome = ? LIMIT 1')) {
            $stmt->bind_param('s', $cliente);
            if ($stmt->execute()) {
                $stmt->bind_result($uid);
                if ($stmt->fetch()) {
                    $codUsuario = (int)$uid;
                }
            }
            $stmt->close();
        }
    }

    // Se não encontrou cliente válido, retorna erro específico para o frontend abrir o modal de cadastro
    if ($codUsuario <= 0) {
        echo 'cliente_invalido';
        exit;
    }

    $conn->begin_transaction();
    try {
        // Cria pedido com total 0 e forma_pagamento indefinida, salvando o endereço diretamente em tbPedido
        $forma = 'A DEFINIR';
        $stmtPed = $conn->prepare('INSERT INTO tbPedido (cod_usuario, tipo_pedido, horario_retirada, forma_pagamento, preco_total, endereco) VALUES (?, ?, ?, ?, 0.00, ?)');
        if (!$stmtPed) { throw new Exception('prepare pedido'); }
        $stmtPed->bind_param('issss', $codUsuario, $tipoPedido, $horarioTime, $forma, $enderecoPedido);
        if (!$stmtPed->execute()) { throw new Exception('exec pedido'); }
        $pedidoId = $stmtPed->insert_id;
        $stmtPed->close();

        // Agregar itens por produto + corte para permitir cortes diferentes no mesmo pedido
        $agregados = [];
        foreach ($itens as $it) {
            $nomeProd = isset($it['produto']) ? trim((string)$it['produto']) : '';
            $qtdRaw = isset($it['quantidade']) ? trim((string)$it['quantidade']) : '0';
            if ($nomeProd === '') { throw new Exception('item_nome'); }
            // Quantidade numérica (permite vírgula)
            $qtd = (float)str_replace(',', '.', preg_replace('/[^0-9.,-]/', '', $qtdRaw));
            if (!is_finite($qtd) || $qtd <= 0) { throw new Exception('item_qtd'); }

            // Localiza produto por nome exato
            $stmtP = $conn->prepare('SELECT id_produto, preco FROM tbProduto WHERE nome_produto = ? LIMIT 1');
            if (!$stmtP) { throw new Exception('prepare sel produto'); }
            $stmtP->bind_param('s', $nomeProd);
            if (!$stmtP->execute()) { $stmtP->close(); throw new Exception('exec sel produto'); }
            $stmtP->bind_result($pid, $preco);
            if (!$stmtP->fetch()) { $stmtP->close(); throw new Exception('produto_nao_encontrado'); }
            $stmtP->close();

            $pid = (int)$pid;
            $preco = (float)$preco;

            // Corte (opcional)
            $corteId = null;
            if (isset($it['corte'])) {
                $corteIdTmp = (int)$it['corte'];
                if ($corteIdTmp > 0) { $corteId = $corteIdTmp; }
            }

            $obs = isset($it['observacao']) ? trim((string)$it['observacao']) : '';

            $key = $pid . '|' . ($corteId ?? 'NULL');
            if (!isset($agregados[$key])) {
                $agregados[$key] = ['pid' => $pid, 'corte' => $corteId, 'qtd' => 0.0, 'preco' => $preco, 'obs' => []];
            }
            $agregados[$key]['qtd'] += $qtd;
            if ($obs !== '') { $agregados[$key]['obs'][] = $obs; }
        }

        $totalPedido = 0.0;
        $insItem = $conn->prepare('INSERT INTO tbPedidoProduto (cod_pedido, cod_produto, cod_corte, quantidade, preco_unitario, preco_total_prod, observacao) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if (!$insItem) { throw new Exception('prepare ins item'); }
        foreach ($agregados as $key => $info) {
            $pid = (int)$info['pid'];
            $corteId = isset($info['corte']) ? $info['corte'] : null;
            if ($corteId !== null) { $corteId = (int)$corteId; }
            $qtd = (float)$info['qtd'];
            $preco = (float)$info['preco'];
            $subtotal = $qtd * $preco;
            $totalPedido += $subtotal;
            $obsStr = '';
            if (isset($info['obs']) && is_array($info['obs'])) {
                $uniq = [];
                foreach ($info['obs'] as $o) {
                    $t = trim((string)$o);
                    if ($t !== '' && !in_array($t, $uniq, true)) { $uniq[] = $t; }
                }
                $obsStr = implode('; ', $uniq);
            }
            $insItem->bind_param('iiiddds', $pedidoId, $pid, $corteId, $qtd, $preco, $subtotal, $obsStr);
            if (!$insItem->execute()) { $insItem->close(); throw new Exception('exec ins item'); }
        }
        $insItem->close();

        // Atualiza total do pedido
        $upd = $conn->prepare('UPDATE tbPedido SET preco_total = ? WHERE id_pedido = ?');
        if (!$upd) { throw new Exception('prepare upd'); }
        $upd->bind_param('di', $totalPedido, $pedidoId);
        if (!$upd->execute()) { $upd->close(); throw new Exception('exec upd'); }
        $upd->close();

        $conn->commit();
        echo 'ok';
    } catch (Throwable $e) {
        $conn->rollback();
        echo 'erro';
    }
    exit;
}
?>