<?php
require_once __DIR__ . '/conexao.php';

// Busca pedidos pendentes + dados do usuário
$sqlPedidos = "SELECT ped.*, usu.*
               FROM tbPedido ped
               JOIN tbUsuario usu ON usu.id_usuario = ped.cod_usuario
               WHERE ped.status = 'PENDENTE'
               ORDER BY ped.data_pedido DESC";
$pedidos = $conn->query($sqlPedidos);

function montarEnderecoUsuario(array $row): string {
    // Tenta compor o endereço com campos comuns; ignora os que não existirem
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
    <title>Pedidos Pendentes</title>
    <style>
      /* complementa estilos com base no cadastroPedido.css */
      .lista-pedidos{ width:100%; max-width:1200px; padding:96px 16px 40px; margin:0 auto; display:grid; gap:16px; }
      .pedido{ background:#fff; border:1px solid var(--border); border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.06); overflow:hidden; }
      .pedido header{ padding:16px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
      .client{ display:flex; flex-direction:column; gap:4px; }
      .client .name{ color:var(--text-heading); font-weight:700; font-size:18px; }
      .client .meta{ color:var(--muted); font-size:13px; }
      .status-badge{ border:1px solid var(--border); border-radius:999px; padding:4px 10px; font-size:12px; background:#fff5f5; border-color:#f3d6d6; color:var(--primary); }
      section.item-list{ padding:0 16px; overflow:hidden; max-height:0; transition:max-height .25s ease-in-out; }
      .pedido.open section.item-list{ max-height:600px; }
      .item{ display:grid; grid-template-columns:1fr auto auto; gap:10px; padding:10px 0; border-bottom:1px dashed var(--border); }
      .item:last-child{ border-bottom:0; }
      .item .title{ color:var(--text-body); font-size:14px; }
      .item .qtd,.item .price{ color:var(--muted); font-size:13px; }
      footer.pedido-footer{ padding:12px 16px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; border-top:1px solid #f2f2f2; }
      .mod-badge{ border:1px solid var(--border); border-radius:999px; padding:4px 10px; font-size:12px; background:#f1fff4; border-color:#cfead9; color:#1b7f3c; }
      .mod-entrega{ background:#f0f6ff; border-color:#d9e7ff; color:#2f5fbf; }
      .footer-note{ color:var(--muted); font-size:13px; }
      .total{ color:var(--text-heading); font-weight:700; }
      .footer-right{ display:flex; flex-direction:column; align-items:flex-end; gap:6px; }
      .endereco, .pagamento{ color:var(--muted); font-size:13px; }
      .actions{ display:flex; gap:10px; padding:0 16px 16px; flex-wrap:wrap; }
      .btn{ background-color:var(--primary); color:#fff; border:0; border-radius:10px; padding:10px 16px; font-family:Baloo, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, "Noto Sans"; font-size:16px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:background-color .2s ease, transform .06s ease; }
      .btn:hover{ background-color:var(--primary-700); }
      .btn:active{ transform:translateY(1px); }
      .btn.secondary{ background:#fff; color:var(--text-body); border:1px solid var(--border); }
      .btn.secondary:hover{ background:#fafafa; }
      @media (max-width:560px){ .item{ grid-template-columns:1fr auto; } }
    </style>
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
        ?>
        <div class="pedido" data-id="<?php echo $idPedido; ?>">
          <header>
            <div class="client">
              <div class="name"><?php echo htmlspecialchars($nomeCliente); ?></div>
              <div class="meta">Pedido #<?php echo $idPedido; ?> • <?php echo htmlspecialchars($dataPedidoFmt); ?></div>
            </div>
            <span class="status-badge">Pendente</span>
          </header>

          <section class="item-list">
            <?php if ($itens): ?>
              <?php foreach ($itens as $item): ?>
                <div class="item">
                  <div class="title"><?php echo htmlspecialchars(nomeProduto($item)); ?></div>
                  <div class="qtd"><?php echo htmlspecialchars((string)$item['quantidade']); ?>x</div>
                  <div class="price"><?php echo fmtPreco(isset($item['preco_total_prod']) ? $item['preco_total_prod'] : ($item['preco_unitario'] * $item['quantidade'])); ?></div>
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
            <button class="btn" onclick="finalizarPedido(this)">Finalizar</button>
            <button class="btn secondary" onclick="toggleMais(this)">saiba mais</button>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="pedido">
        <header>
          <div class="client">
            <div class="name">Nenhum pedido pendente</div>
            <div class="meta">Acompanhe novos pedidos em tempo real.</div>
          </div>
        </header>
      </div>
    <?php endif; ?>
  </div>

  <script>
    function toggleMais(btn){
      const card = btn.closest('.pedido');
      card.classList.toggle('open');
      btn.textContent = card.classList.contains('open') ? 'recolher' : 'saiba mais';
    }
    function finalizarPedido(btn){
      const card = btn.closest('.pedido');
      const id = card.getAttribute('data-id');
      // TODO: integrar com endpoint para mudar status para PRONTO/ENTREGUE
      btn.disabled = true;
      btn.textContent = 'Finalizando...';
      setTimeout(() => removerCard(card), 500);
    }
    function removerCard(card){
      card.style.transition = 'opacity .2s ease, transform .2s ease';
      card.style.opacity = '0';
      card.style.transform = 'scale(0.98)';
      setTimeout(() => card.remove(), 220);
    }
  </script>
</body>
</html>
