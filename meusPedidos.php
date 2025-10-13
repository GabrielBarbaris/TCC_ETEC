<?php
session_start();
require_once __DIR__ . '/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !$_SESSION['id_usuario']) {
    header('Location: login.php');
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];

// Busca apenas os pedidos do usuário logado + dados do usuário
$sqlPedidos = "SELECT ped.*, usu.*, ped.endereco AS end_pedido
               FROM tbPedido ped
               JOIN tbUsuario usu ON usu.id_usuario = ped.cod_usuario
               WHERE ped.cod_usuario = ?
               ORDER BY ped.data_pedido DESC";
$pedidos = null;
if ($stmt = $conn->prepare($sqlPedidos)) {
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $pedidos = $stmt->get_result();
    $stmt->close();
}

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
    <link rel="stylesheet" href="css/globals.css" />
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="css/cadastroPedido.css" />
    <link rel="stylesheet" href="css/pedidosPendentes.css" />
    <style>
      /* Fontes necessárias para o header */
      @font-face { font-family: 'Shaimus Clean-Regular'; src: url('fonts/Shaimus Clean Regular.ttf'); }
      @font-face { font-family: 'Baloo'; src: url('fonts/Baloo-Regular.ttf'); }

      /* Header fixo reutilizado do index */
      .tela-inicial .HEADER { position: fixed; width: 100%; max-width: 3840px; height: 229px; top: 25px; left: 50%; transform: translate(-50%, -50%); background-color: transparent; }
      .tela-inicial .overlap-17 { position: relative; width: 100%; height: 160px; top: 69px; }
      .tela-inicial .rectangle-10 { position: absolute; width: 100%; height: 102px; top: 0; background-color: #800000; }
      .tela-inicial .rectangle-11 { position: absolute; width: 100%; height: 11px; top: 91px; left: 0; background-color: #6a0000; box-shadow: 0px 4px 7px #00000040; }

      /* Barra de tarefas (busca, login e sacola) */
      .tela-inicial .barra_tarefas { position: absolute; width: 600px; height: 50px; margin: 30px; right: 0; gap: 20px; align-items: center; display: flex; z-index: 50; }
      .tela-inicial .barra_tarefas button { cursor: pointer; background: transparent; border: none; padding: 6px; display: inline-flex; align-items: center; justify-content: center; }
      .tela-inicial .search { position: relative; z-index: 10; display:flex; background:#fff; border-radius:50px; padding:8px 15px; box-shadow:0 3px 6px rgba(0,0,0,.2); width:500px; transition: box-shadow .3s ease; }
      .tela-inicial .search:hover { box-shadow:0 5px 10px rgba(0,0,0,.3); }
      .tela-inicial .search input { border:none; outline:none; padding:8px; font-size:14px; flex:1; background:transparent; color:#333; }
      .tela-inicial .search input::placeholder { color:#999; }
      .tela-inicial .search label { cursor:pointer; color:#555; margin-right:8px; display:flex; align-items:center; }
      .tela-inicial .search span { font-size:24px; color:#333; }
      .tela-inicial .user-user { width:24px; height:24px; display:block; }
      .tela-inicial .basket { width:24px; height:24px; display:block; }
      .tela-inicial .logo-2 { width:229px; height:61px; top:24px; left:19px; position:absolute; object-fit:cover; }

      /* Barra de categorias */
      .tela-inicial .CATEGORIAS { position: fixed; width: 100%; max-width:1920px; height: 79px; top: 200px; left: 50%; transform: translate(-50%, -50%); background-color: #f4f4f4; border-radius: 13px; box-shadow: 0px 4px 4px #00000080; align-items: center; }
      .tela-inicial .CHURRASCO { position: absolute; width: 178px; height: 48px; top: 19px; left: 73.3%; }
      .tela-inicial .overlap-group-3 { position: relative; width: 176px; height: 48px; }
      .tela-inicial .rectangle-7 { position: absolute; width: 47px; height: 48px; top: 0; left: 0; }
      .tela-inicial .text-wrapper-57 { position: absolute; width: 145px; height: 23px; top: 14px; left: 31px; font-family: 'Shaimus Clean-Regular', Helvetica; color: #5f0d0d; font-size: 24px; text-align: center; white-space: nowrap; }
      .tela-inicial .weber { position: absolute; width: 41px; height: 41px; top: 4px; left: 3px; }

      .tela-inicial .KITS { position: absolute; width: 103px; height: 48px; top: 20px; left: 86.5%; }
      .tela-inicial .overlap-11 { position: relative; width: 101px; height: 48px; }
      .tela-inicial .text-wrapper-58 { width: 63px; height: 22px; top: 14px; left: 38px; color: #5f0d0d; font-size: 24px; text-align: center; white-space: nowrap; position: absolute; font-family: 'Shaimus Clean-Regular', Helvetica; }
      .tela-inicial .shopping-basket { position: absolute; width: 34px; height: 34px; top: 7px; left: 7px; }

      .tela-inicial .AVES { position: absolute; width: 108px; height: 48px; top: 21px; left: 20.5%; }
      .tela-inicial .overlap-12 { position: relative; width: 106px; height: 48px; }
      .tela-inicial .text-wrapper-59 { position: absolute; width: 63px; height: 22px; top: 13px; left: 43px; font-family: 'Shaimus Clean-Regular', Helvetica; color: #5f0d0d; font-size:24px; text-align:center; white-space:nowrap; }
      .tela-inicial .poultry-leg { position: absolute; width: 39px; height: 39px; top: 5px; left: 4px; }

      .tela-inicial .EMBUTIDOS { position: absolute; width: 168px; height: 48px; top: 20px; left: 60.1%; }
      .tela-inicial .overlap-13 { position: relative; width: 166px; height: 48px; }
      .tela-inicial .text-wrapper-60 { position: absolute; width: 128px; height: 25px; top: 13px; left: 38px; font-family: 'Shaimus Clean-Regular', Helvetica; color:#5f0d0d; font-size:24px; text-align:center; white-space:nowrap; }
      .tela-inicial .salami { position: absolute; width: 41px; height: 40px; top: 5px; left: 5px; }

      .tela-inicial .SUNOS { position: absolute; width: 130px; height: 50px; top: 21px; left: 33.7%; }
      .tela-inicial .overlap-14 { position: relative; width: 128px; height: 50px; }
      .tela-inicial .rectangle-8 { position: absolute; width: 47px; height: 48px; top: 2px; left: 0; }
      .tela-inicial .text-wrapper-61 { position: absolute; width: 93px; height: 24px; top: 13px; left: 35px; font-family: 'Shaimus Clean-Regular', Helvetica; color:#5f0d0d; font-size:24px; text-align:center; white-space:nowrap; }
      .tela-inicial .bacon { position: absolute; width: 50px; height: 50px; top: 0; left: 0; }

      .tela-inicial .LINGUIAS { position: absolute; width: 159px; height: 48px; top: 23px; left: 46.9%; }
      .tela-inicial .overlap-15 { position: relative; width: 157px; height: 48px; }
      .tela-inicial .text-wrapper-62 { position: absolute; width: 126px; height: 23px; top: 12px; left: 31px; font-family: 'Shaimus Clean-Regular', Helvetica; color:#5f0d0d; font-size:24px; text-align:center; white-space:nowrap; }
      .tela-inicial .vector-2 { position: absolute; width: 38px; height: 30px; top: 8px; left: 4px; }

      .tela-inicial .BOVINOS { position: absolute; width: 148px; height: 52px; top: 17px; left: 9.6%; }
      .tela-inicial .overlap-16 { position: relative; width: 146px; height: 52px; }
      .tela-inicial .rectangle-9 { position: absolute; width: 47px; height: 48px; top: 4px; left: 2px; }
      .tela-inicial .text-wrapper-63 { position: absolute; width: 120px; height: 26px; top: 15px; left: 26px; font-family: 'Shaimus Clean-Regular', Helvetica; color:#5f0d0d; font-size:24px; text-align:center; white-space:nowrap; }
      .tela-inicial .barbecue { position: absolute; width: 52px; height: 52px; top: 0; left: 0; }

      /* Ajuste do conteúdo para não ficar sob o header fixo */
      .lista-pedidos { padding: 320px 16px 40px !important; }
    </style>
    <title>Meus pedidos</title>
</head>
<body>
  <div class="tela-inicial">
  <header class="HEADER">
      <div class="overlap-17">
        <div class="CATEGORIAS">
          <div class="CHURRASCO">
            <div class="overlap-group-3" onclick="window.location.href='categorias.php?nome=Churrasco'" role="button" style="cursor:pointer;">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-57">CHURRASCO</div>
              <img class="weber" src="img/churrasco.png" alt="">
            </div>
          </div>

          <div class="KITS">
            <div class="overlap-11" onclick="window.location.href='categorias.php?nome=Kits'" role="button" style="cursor:pointer;">
              <div class="text-wrapper-58">KITS</div>
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <img class="shopping-basket" src="img/kits.png" alt="">
            </div>
          </div>

          <div class="AVES">
            <div class="overlap-12" onclick="window.location.href='categorias.php?nome=Aves'" role="button" style="cursor:pointer;">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-59">AVES</div>
              <img class="poultry-leg" src="img/aves.png" alt="">
            </div>
          </div>

          <div class="EMBUTIDOS">
            <div class="overlap-13" onclick="window.location.href='categorias.php?nome=Embutidos'" role="button" style="cursor:pointer;">
              <div class="text-wrapper-60">EMBUTIDOS</div>
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <img class="salami" src="img/embutido.png" alt="">
            </div>
          </div>

          <div class="SUNOS">
            <div class="overlap-14" onclick="window.location.href='categorias.php?nome=Suinos'" role="button" style="cursor:pointer;">
              <img class="rectangle-8" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-61">SUiNOS</div>
              <img class="bacon" src="img/suino.png" alt="">
            </div>
          </div>

          <div class="LINGUIAS">
            <div class="overlap-15" onclick="window.location.href='categorias.php?nome=Linguicas'" role="button" style="cursor:pointer;">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-62">LINGUICAS</div>
              <img class="vector-2" src="img/linguica.png" alt="">
            </div>
          </div>

          <div class="BOVINOS">
            <div class="overlap-16" onclick="window.location.href='categorias.php?nome=Bovinos'" role="button" style="cursor:pointer;">
              <img class="rectangle-9" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-63">BOVINOS</div>
              <img class="barbecue" src="img/bovinos.png" alt="">
            </div>
          </div>
        </div>

        <div class="rectangle-10"></div>
        <div class="rectangle-11"></div>

        <div class="barra_tarefas">
          <div class="search">
            <label for="searchInput"><span class="material-symbols-outlined"> Search</span></label>
            <input type="text" id="searchInput" placeholder="pesquisar">
          </div>

          <button id="btnOpenLogin" title="Entrar" type="button" style="background:none; border:none; cursor:pointer;">
            <img class="user-user" src="img/login.png" alt="login" />
          </button>

          <button title="Ver sacola" type="button" onclick="window.location.href='meusPedidos.php'">
            <img class="basket" src="img/pedido.png" alt="pedido" />
          </button>
        </div>

        <img class="logo-2" src="img/logo.png" alt="Logo" />
      </div>
    </header>
  </div>
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

          // Mapeia status para badge e botão Cancelar
          $status = strtoupper((string)($p['status'] ?? ''));
          switch ($status) {
            case 'PENDENTE':
              $statusClass = 'status-pendente';
              $statusText = 'Pendente';
              $btnDisabled = '';
              $btnText = 'Cancelar';
              break;
            case 'PRONTO':
              $statusClass = 'status-pronto';
              $statusText = 'Pronto';
              $btnDisabled = 'disabled';
              $btnText = 'Finalizado';
              break;
            case 'ENTREGUE':
              $statusClass = 'status-pronto';
              $statusText = 'Entregue';
              $btnDisabled = 'disabled';
              $btnText = 'Finalizado';
              break;
            case 'CANCELADO':
              $statusClass = 'status-pendente';
              $statusText = 'Cancelado';
              $btnDisabled = 'disabled';
              $btnText = 'Cancelado';
              break;
            default:
              $statusClass = 'status-pendente';
              $statusText = ucfirst(strtolower($status ?: 'Pendente'));
              $btnDisabled = 'disabled';
              $btnText = $statusText;
          }
        ?>
        <div class="pedido" data-id="<?php echo $idPedido; ?>">
          <header>
            <div class="client">
              <div class="name">Pedido #<?php echo $idPedido; ?></div>
              <div class="meta"><?php echo htmlspecialchars($dataPedidoFmt); ?></div>
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
            <button class="btn" onclick="cancelarPedido(this)" <?php echo $btnDisabled; ?>><?php echo htmlspecialchars($btnText); ?></button>
            <button class="btn secondary" onclick="toggleMais(this)">saiba mais</button>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="pedido">
        <header>
          <div class="client">
            <div class="name">Nenhum pedido encontrado</div>
            <div class="meta">Faça seu pedido para acompanhar aqui.</div>
          </div>
        </header>
      </div>
    <?php endif; ?>
  </div>

  <script>
    (function(){
      var LOGADO = <?php echo isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] ? 'true' : 'false'; ?>;
      var btn = document.getElementById('btnOpenLogin');
      if (btn) {
        btn.addEventListener('click', function(){
          if (LOGADO) {
            window.location.href = 'index.php';
          } else {
            window.location.href = 'login.php';
          }
        });
      }
      var basketImg = document.querySelector('.barra_tarefas .basket');
      if (basketImg) { basketImg.style.display = 'block'; basketImg.style.visibility = 'visible'; }
    })();
  </script>

  <!-- bibliotecas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="./js/meusPedidos.js"></script>
  <script>
    // Busca: redireciona para categorias.php ao pressionar Enter (mesmo comportamento do index)
    document.addEventListener('DOMContentLoaded', function() {
      var input = document.getElementById('searchInput');
      if (!input) return;
      input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          var q = (input.value || '').trim();
          if (q) {
            e.preventDefault();
            window.location.href = 'categorias.php?busca=' + encodeURIComponent(q);
          }
        }
      });
    });
  </script>
</body>
</html>
