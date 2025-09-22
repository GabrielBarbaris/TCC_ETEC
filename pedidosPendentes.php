<?php
require_once __DIR__ . '/conexao.php';

// Busca todos os pedidos + dados do usuário
$sqlPedidos = "SELECT ped.*, usu.*, ped.endereco AS end_pedido
               FROM tbPedido ped
               JOIN tbUsuario usu ON usu.id_usuario = ped.cod_usuario
               ORDER BY ped.data_pedido DESC";
$pedidos = $conn->query($sqlPedidos);

function montarEnderecoUsuario(array $row): string {
    // Prioriza o endereço salvo no próprio pedido (tbPedido.endereco, aliased como end_pedido)
    $enderecoPedido = trim((string)($row['end_pedido'] ?? ''));
    if ($enderecoPedido !== '') {
        return $enderecoPedido;
    }

    // Fallback: tenta compor a partir do cadastro do usuário
    $partesLinha1 = [];
    foreach (['endereco', 'logradouro', 'rua'] as $k) {
        if (isset($row[$k]) && trim((string)$row[$k]) !== '') { $partesLinha1[] = trim((string)$row[$k]); break; }
    }
    if (isset($row['numero']) && trim((string)$row['numero']) !== '') {
        $partesLinha1[] = 'Nº ' . trim((string)$row['numero']);
    }
    if (isset($row['complemento']) && trim((string)$row['complemento']) !== '') {
        $partesLinha1[] = trim((string)$row['complemento']);
    }

    $partesLinha2 = [];
    foreach (['bairro'] as $k) {
        if (isset($row[$k]) && trim((string)$row[$k]) !== '') { $partesLinha2[] = trim((string)$row[$k]); }
    }
    $cidade = '';
    foreach (['cidade', 'municipio'] as $k) {
        if (isset($row[$k]) && trim((string)$row[$k]) !== '') { $cidade = trim((string)$row[$k]); break; }
    }
    $uf = '';
    if (isset($row['uf']) && trim((string)$row['uf']) !== '') { $uf = trim((string)$row['uf']); }
    if ($cidade || $uf) { $partesLinha2[] = trim($cidade . ($uf ? '/' . $uf : '')); }

    if (isset($row['cep']) && trim((string)$row['cep']) !== '') {
        $partesLinha2[] = 'CEP ' . trim((string)$row['cep']);
    }

    $linha1 = implode(', ', $partesLinha1);
    $linha2 = implode(' • ', $partesLinha2);

    $texto = trim($linha1);
    if ($linha2) { $texto .= ($texto ? ' — ' : '') . $linha2; }

    return $texto !== '' ? $texto : 'Endereço não informado';
}

function buscarItensPedido(mysqli $conn, int $idPedido): array {
    $itens = [];
    $sql = "SELECT pp.*, pr.*
            FROM tbPedidoProduto pp
            JOIN tbProduto pr ON pr.id_produto = pp.cod_produto
            WHERE pp.cod_pedido = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $idPedido);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $itens[] = $row;
        }
        $stmt->close();
    }
    return $itens;
}

function nomeProduto(array $item): string {
    foreach (['nome', 'nome_produto', 'descricao', 'produto', 'titulo'] as $k) {
        if (isset($item[$k]) && trim((string)$item[$k]) !== '') return (string)$item[$k];
    }
    return 'Produto';
}

function fmtPreco($v): string { return 'R$ ' . number_format((float)$v, 2, ',', '.'); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/cadastroPedido.css" />
    <link rel="stylesheet" href="css/pedidosPendentes.css" />
    <title>peditos realizados</title>
    
</head>
<body>
  <?php include 'menuAdm.php'; ?>

  <div class="lista-pedidos">
    <?php if ($pedidos && $pedidos->num_rows > 0): ?>
      <?php while ($p = $pedidos->fetch_assoc()): ?>
        <?php
          $idPedido = (int)$p['id_pedido'];
          $itens = buscarItensPedido($conn, $idPedido);
          // Total: usa preco_total do pedido, senão soma itens
          $totalPedido = isset($p['preco_total']) ? (float)$p['preco_total'] : 0.0;
          if ($totalPedido <= 0 && $itens) {
            $soma = 0.0;
            foreach ($itens as $it) {
              $soma += isset($it['preco_total_prod']) ? (float)$it['preco_total_prod'] : ((float)$it['preco_unitario'] * (float)$it['quantidade']);
            }
            $totalPedido = $soma;
          }
          $isEntrega = strtoupper((string)$p['tipo_pedido']) === 'ENTREGA';
          $nomeCliente = isset($p['nome']) ? $p['nome'] : (isset($p['nome_usuario']) ? $p['nome_usuario'] : 'Cliente');
          $dataPedidoFmt = date('d/m/Y H:i', strtotime((string)$p['data_pedido']));
          $endereco = $isEntrega ? montarEnderecoUsuario($p) : '';
          $formaPagamento = isset($p['forma_pagamento']) ? $p['forma_pagamento'] : '';
          $horarioRetirada = isset($p['horario_retirada']) && $p['horario_retirada'] !== null ? substr((string)$p['horario_retirada'], 0, 5) : '';

          // Mapeia status para badge e botão
          $status = strtoupper((string)($p['status'] ?? ''));
          switch ($status) {
            case 'PENDENTE':
              $statusClass = 'status-pendente';
              $statusText = 'Pendente';
              $btnDisabled = '';
              $btnText = 'Finalizar';
              break;
            case 'PRONTO':
              $statusClass = 'status-pronto';
              $statusText = 'Pronto';
              $btnDisabled = 'disabled';
              $btnText = 'Pronto';
              break;
            case 'ENTREGUE':
              $statusClass = 'status-pronto';
              $statusText = 'Entregue';
              $btnDisabled = 'disabled';
              $btnText = 'Entregue';
              break;
            default:
              $statusClass = 'status-pendente';
              $statusText = ucfirst(strtolower($status ?: 'Pendente'));
              $btnDisabled = $status === 'PENDENTE' ? '' : 'disabled';
              $btnText = $status === 'PENDENTE' ? 'Finalizar' : $statusText;
          }
        ?>
        <div class="pedido" data-id="<?php echo $idPedido; ?>">
          <header>
            <div class="client">
              <div class="name"><?php echo htmlspecialchars($nomeCliente); ?></div>
              <div class="meta">Pedido #<?php echo $idPedido; ?> • <?php echo htmlspecialchars($dataPedidoFmt); ?></div>
            </div>
            <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
          </header>

          <section class="item-list">
            <?php if ($itens): ?>
              <?php foreach ($itens as $item): ?>
                <div class="item">
                  <div class="title"><?php echo htmlspecialchars(nomeProduto($item)); ?></div>
                  <div class="qtd"><?php echo htmlspecialchars((string)$item['quantidade']); ?>x</div>
                  <div class="price"><?php echo fmtPreco(isset($item['preco_total_prod']) ? $item['preco_total_prod'] : ($item['preco_unitario'] * $item['quantidade'])); ?></div>

                  <?php $obsItem = isset($item['observacao']) ? trim((string)$item['observacao']) : ''; ?>
                  <?php if ($obsItem !== ''): ?>
                    <div class="obs">Obs: <?php echo htmlspecialchars($obsItem); ?></div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="item">
                <div class="title">Sem itens</div>
                <div class="qtd">-</div>
                <div class="price">-</div>
              </div>
            <?php endif; ?>
          </section>

          <footer class="pedido-footer">
            <div class="footer-left">
              <?php if ($isEntrega): ?>
                <span class="mod-badge mod-entrega">Entrega</span>
              <?php else: ?>
                <span class="mod-badge">Retirada</span>
                <?php if ($horarioRetirada): ?>
                  <span class="footer-note">Horário: <?php echo htmlspecialchars($horarioRetirada); ?></span>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <div class="footer-right">
              <div class="total">Total: <?php echo fmtPreco($totalPedido); ?></div>
              <?php if ($isEntrega): ?>
                <div class="endereco">Endereço: <?php echo htmlspecialchars($endereco); ?></div>
                <?php if ($formaPagamento): ?>
                  <div class="pagamento">Pagamento: <?php echo htmlspecialchars($formaPagamento); ?></div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </footer>

          <div class="actions">
            <button class="btn" onclick="finalizarPedido(this)" <?php echo $btnDisabled; ?>><?php echo htmlspecialchars($btnText); ?></button>
            <button class="btn secondary" onclick="toggleMais(this)">saiba mais</button>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="pedido">
        <header>
          <div class="client">
            <div class="name">Nenhum pedido realizado</div>
            <div class="meta">Acompanhe novos pedidos em tempo real.</div>
          </div>
        </header>
      </div>
    <?php endif; ?>
  </div>

  <!-- bibliotecas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="./js/pedidosPendentes.js"></script>
</body>
</html>
