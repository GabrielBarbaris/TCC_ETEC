<?php
session_start();
if (isset($_GET['logout'])) {
  try {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
  } catch (Throwable $e) {
  }
  header('Location: index.php');
  exit;
}
require_once __DIR__ . '/conexao.php';

// Verifica se o usuário está logado
$clienteLogado = isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'];
if (!$clienteLogado) {
    header('Location: index.php'); // Redireciona para o início se não estiver logado
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

          <button id="btnOpenLogin" title="Minha Conta" type="button" style="background:none; border:none; cursor:pointer;">
            <img class="user-user" src="img/login.png" alt="login" />
          </button>

          

          <button title="Ver sacola" type="button" onclick="window.location.href='meusPedidos.php'">
            <img class="basket" src="img/pedido.png" alt="pedido" />
          </button>
        </div>

        <a href="index.php"><img class="logo-2" src="img/logo.png" alt="Logo" /></a>
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

  <!-- Dialog Login -->
  <dialog id="loginDialog" style="position:fixed; inset:0; margin:auto; width:min(400px,67vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
    <button id="closeLoginDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <div class="container">
      <section class="header">
        <h2>Login</h2>
      </section>
      <form id="form" class="form">
        <div class="form_content">
          <span id="mensagem">Menssagem</span>
        </div>
        <div class="form_content">
          <label for="telefone">Telefone</label>
          <input type="text" id="telefone" name="telefone" placeholder="Digite seu Telefone">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Digite sua senha">
          <a>mensagen de erro</a>
        </div>
        <a href="#" id="openCadastroLink">Cadastrar</a>
        <button type="submit" id="cadastrar">Logar</button>
      </form>
    </div>
  </dialog>

  <!-- Dialog Cadastro -->
  <dialog id="cadastroDialog" style="position:fixed; inset:0; margin:auto; width:min(400px,67vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
    <button id="closeCadastroDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <div class="container">
      <section class="header">
        <h2>Nova Conta</h2>
      </section>
      <form id="cadForm" class="form">
        <div class="form_content">
          <span id="cadMensagem">Menssagem</span>
        </div>
        <div class="form_content">
          <label for="cadNome">Nome</label>
          <input type="text" id="cadNome" name="nome" placeholder="Digite seu nome">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadSobrenome">Sobrenome</label>
          <input type="text" id="cadSobrenome" name="sobrenome" placeholder="Digite seu sobrenome">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadTelefone">Telefone</label>
          <input type="text" id="cadTelefone" name="telefone" placeholder="Digite seu Telefone">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadSenha">Senha</label>
          <input type="password" id="cadSenha" name="senha" placeholder="Digite sua senha">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadSenhaConf">Confirmacao de Senha</label>
          <input type="password" id="cadSenhaConf" placeholder="Digite sua senha">
          <a>mensagen de erro</a>
        </div>
        <a href="#" id="openLoginFromCadastro">Já tenho conta</a>
        <button type="submit" id="btnCadastrar">Cadastrar</button>
      </form>
    </div>
  </dialog>

  <!-- Dialog Perfil (Informações do Usuário) -->
  <dialog id="perfilDialog" style="position:fixed; inset:0; margin:auto; width:min(760px, 90vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
    <button id="closePerfilDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <div class="container" style="min-height:auto; background:#F8F8F8;">
      <section class="header" style="background: linear-gradient(120deg, #600E0E, #440D0D); padding: 16px; text-align:center; color:#fff;">
        <h2 style="margin:0; font-family: Baloo;">Minha conta</h2>
      </section>
      <form id="perfilForm" class="form" style="max-width:680px; margin:24px auto; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:20px;">
        <div class="form_content">
          <label for="perfilNome">Nome</label>
          <input type="text" id="perfilNome" readonly>
          <a></a>
        </div>
        <div class="form_content">
          <label for="perfilSobrenome">Sobrenome</label>
          <input type="text" id="perfilSobrenome" readonly>
          <a></a>
        </div>
        <div class="form_content">
          <label for="perfilTelefone">Telefone</label>
          <input type="text" id="perfilTelefone" readonly>
          <a></a>
        </div>
        <div class="form_content" style="grid-column: span 2; display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" id="btnEditarPerfil" style="background:#5c4444; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer;">Editar</button>
          <button type="submit" id="btnSalvarPerfil" style="background:#006b1b; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer; display:none;">Salvar</button>
          <button type="button" id="btnCancelarPerfil" style="background:#9c2a2a; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer; display:none;">Cancelar</button>
          <button type="button" id="btnLogout" style="background:#800000; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer;">Sair da conta</button>
        </div>
      </form>
    </div>
  </dialog>

  <style>
    #loginDialog::backdrop, #cadastroDialog::backdrop, #perfilDialog::backdrop { background: rgba(0, 0, 0, .45); }
    #loginDialog .container, #cadastroDialog .container { background-color: #efefef; border-radius: 16px; max-width: 400px; width: 100%; box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5); overflow: hidden; }
    #loginDialog .header, #cadastroDialog .header { background: linear-gradient(120deg, #600E0E, #440D0D); padding: 20px; font-family: "Shaimus Clean-Regular"; text-align: center; color: #ffffff; font-size: 30px; }
    #loginDialog .form, #cadastroDialog .form { padding: 18px; }
    #loginDialog .form_content, #cadastroDialog .form_content { margin-bottom: 8px; padding-bottom: 18px; position: relative; font-family: "Calibre"; color: #807a75; }
    #loginDialog .form_content label, #cadastroDialog .form_content label { display: inline-block; margin-bottom: 0px; }
    #loginDialog .form_content input, #cadastroDialog .form_content input { display: block; width: 100%; border-radius: 3px; padding: 10px; border: 2px solid #dfdfdf; }
    #loginDialog .form_content a, #cadastroDialog .form_content a { position: absolute; bottom: 0px; left: 0; visibility: hidden; }
    #loginDialog .form button, #cadastroDialog .form button { background-color: #600E0E; color: #ffffff; width: 100%; border: 0; border-radius: 10px; padding: 8px; font-family: Baloo; font-size: 16px; cursor: pointer; margin-top: 14px; }
    #loginDialog .form_content.error input, #cadastroDialog .form_content.error input { border-color: #fc5e5e; }
    #loginDialog .form_content span, #cadastroDialog .form_content span { display: block; text-align: center; padding: 10px; color: #ffffff; border: 3px solid rgba(243, 4, 4, 0.156); background-color: rgba(105, 23, 23, 0.593); border-radius: 13px; }
    #loginDialog .form_content.error a, #cadastroDialog .form_content.error a { color: #fc5e5e; visibility: visible; }
    #perfilDialog .form { width: 100%; max-width: 680px; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    #perfilDialog .form_content { display: flex; flex-direction: column; gap: 6px; }
    #perfilDialog label { font-family: Arial, Helvetica, sans-serif; color: #00000097; }
    #perfilDialog input { width: 100%; padding: 14px; border-radius: 8px; border: 1px solid #dddddd; background: #fff; color: #222; font-size: 14px; outline: none; }
    #perfilDialog input[readonly] { background: #fafafa; color: #333; }
    #perfilDialog .error input { border: 1px solid #fc5e5e; box-shadow: 0 0 0 3px rgba(252, 94, 94, .12); }
  </style>

  <script>
    // Expor dados do cliente do PHP para o JS
    window.CLIENTE_LOGADO = <?php echo $clienteLogado ? 'true' : 'false'; ?>;
    window.CLIENTE_DADOS = <?php
                            $cli = ['nome' => '', 'telefone' => ''];
                            if ($clienteLogado) {
                                require_once __DIR__ . '/conexao.php';
                                if ($stmt = $conn->prepare('SELECT nome, sobrenome, telefone FROM tbUsuario WHERE id_usuario = ? LIMIT 1')) {
                                  $stmt->bind_param('i', $_SESSION['id_usuario']);
                                  if ($stmt->execute()) {
                                    $res = $stmt->get_result();
                                    if ($row = $res->fetch_assoc()) {
                                      $nome = trim((string)($row['nome'] ?? '') . ' ' . (string)($row['sobrenome'] ?? ''));
                                      $cli['nome'] = $nome;
                                      $cli['telefone'] = (string)($row['telefone'] ?? '');
                                    }
                                  }
                                  $stmt->close();
                                }
                            }
                            echo json_encode($cli, JSON_UNESCAPED_UNICODE);
                            ?>;
  </script>

  <!-- bibliotecas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="./js/meusPedidos.js"></script>
  <script src="./js/login.js"></script>
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

    // Lógica dos modais de login/perfil/cadastro (copiado de index.php)
    (function() {
      const loginDlg = document.getElementById('loginDialog');
      const btn = document.getElementById('btnOpenLogin');
      const closeLoginBtn = document.getElementById('closeLoginDialog');

      const perfilDlg = document.getElementById('perfilDialog');
      const closePerfilBtn = document.getElementById('closePerfilDialog');
      const btnLogout = document.getElementById('btnLogout');
      const btnEdit = document.getElementById('btnEditarPerfil');
      const btnSave = document.getElementById('btnSalvarPerfil');
      const btnCancel = document.getElementById('btnCancelarPerfil');

      let editSnapshot = null;

      function openLogin() {
        if (loginDlg && typeof loginDlg.showModal === 'function') loginDlg.showModal();
        else if (loginDlg) loginDlg.setAttribute('open', 'open');
      }

      function closeLogin() {
        if (loginDlg && typeof loginDlg.close === 'function') loginDlg.close();
        else if (loginDlg) loginDlg.removeAttribute('open');
      }

      function openPerfil() {
        if (perfilDlg && typeof perfilDlg.showModal === 'function') perfilDlg.showModal();
        else if (perfilDlg) perfilDlg.setAttribute('open', 'open');
        preencherPerfil();
      }

      function closePerfil() {
        if (perfilDlg && typeof perfilDlg.close === 'function') perfilDlg.close();
        else if (perfilDlg) perfilDlg.removeAttribute('open');
      }

      function preencherPerfil() {
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');
        if (nomeEl) { nomeEl.value = ''; nomeEl.readOnly = true; }
        if (sobrenomeEl) { sobrenomeEl.value = ''; sobrenomeEl.readOnly = true; }
        if (telefoneEl) { telefoneEl.value = ''; telefoneEl.readOnly = true; }
        toggleEdit(false);

        fetch('getCliente.php')
          .then(r => r.ok ? r.json() : null)
          .then(d => {
            if (d && !d.erro) {
              if (nomeEl) nomeEl.value = String(d.nome || '');
              if (sobrenomeEl) sobrenomeEl.value = String(d.sobrenome || '');
              if (telefoneEl) telefoneEl.value = String(d.telefone || '');
            }
          });
      }

      function handleIconClick() {
        if (window.CLIENTE_LOGADO === true) openPerfil();
        else openLogin();
      }

      btn && btn.addEventListener('click', handleIconClick);
      closeLoginBtn && closeLoginBtn.addEventListener('click', closeLogin);
      loginDlg && loginDlg.addEventListener('cancel', (e) => { e.preventDefault(); closeLogin(); });

      closePerfilBtn && closePerfilBtn.addEventListener('click', closePerfil);
      perfilDlg && perfilDlg.addEventListener('cancel', (e) => { e.preventDefault(); closePerfil(); });

      function toggleEdit(on) {
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');

        if (on) {
          editSnapshot = {
            nome: nomeEl ? nomeEl.value : '',
            sobrenome: sobrenomeEl ? sobrenomeEl.value : '',
            telefone: telefoneEl ? telefoneEl.value : ''
          };
        }

        if (nomeEl) nomeEl.readOnly = !on;
        if (sobrenomeEl) sobrenomeEl.readOnly = !on;
        if (telefoneEl) telefoneEl.readOnly = !on;
        if (btnEdit) btnEdit.style.display = on ? 'none' : '';
        if (btnSave) btnSave.style.display = on ? '' : 'none';
        if (btnCancel) btnCancel.style.display = on ? '' : 'none';
        if (!on) editSnapshot = null;
      }

      function maskTel(v) {
        v = (v || '').replace(/\D/g, '');
        if (v.length > 0) v = '(' + v;
        if (v.length > 3) v = v.slice(0, 3) + ') ' + v.slice(3);
        if (v.length > 10) v = v.slice(0, 10) + '-' + v.slice(10);
        if (v.length > 15) v = v.slice(0, 15);
        return v;
      }

      const telefoneEl = document.getElementById('perfilTelefone');
      if (telefoneEl) {
        telefoneEl.addEventListener('input', function() {
          if (!telefoneEl.readOnly) telefoneEl.value = maskTel(telefoneEl.value);
        });
      }

      btnEdit && btnEdit.addEventListener('click', () => toggleEdit(true));
      btnCancel && btnCancel.addEventListener('click', () => {
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');
        if (editSnapshot) {
          if (nomeEl) nomeEl.value = editSnapshot.nome || '';
          if (sobrenomeEl) sobrenomeEl.value = editSnapshot.sobrenome || '';
          if (telefoneEl) telefoneEl.value = editSnapshot.telefone || '';
        }
        toggleEdit(false);
      });

      const perfilForm = document.getElementById('perfilForm');
      perfilForm && perfilForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');
        const nome = (nomeEl && nomeEl.value || '').trim();
        const sobrenome = (sobrenomeEl && sobrenomeEl.value || '').trim();
        const telefone = (telefoneEl && telefoneEl.value || '').trim();
        let ok = true;

        function setErr(el, on) {
          const p = el && el.parentElement;
          if (p) p.className = on ? 'form_content error' : 'form_content';
        }
        setErr(nomeEl, !nome);
        setErr(sobrenomeEl, !sobrenome);
        setErr(telefoneEl, !telefone || telefone.length !== 15);
        if (!nome || !sobrenome || !telefone || telefone.length !== 15) return;

        const data = new URLSearchParams();
        data.set('nome', nome);
        data.set('sobrenome', sobrenome);
        data.set('telefone', telefone);
        fetch('updateCliente.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: data.toString()
          })
          .then(r => {
            if (!r.ok) return r.json().then(err => Promise.reject(err));
            return r.json();
          })
          .then(resp => {
            if (resp && resp.ok) {
              alert('Dados atualizados com sucesso!');
              location.reload();
            }
          })
          .catch(err => {
            if (err && err.erro === 'telefone_existente') {
              alert(err.message || 'Este telefone já está cadastrado em outra conta.');
              setErr(telefoneEl, true);
            } else {
              alert('Erro de comunicação com o servidor ou falha ao salvar.');
            }
          });
      });

      btnLogout && btnLogout.addEventListener('click', () => {
        try {
          localStorage.removeItem('clienteId');
          localStorage.removeItem('carrinho');
          localStorage.removeItem('pedidoEntrega');
        } catch (e) {}
        window.location.href = 'index.php?logout=1';
      });

      // Lógica para abrir/fechar cadastro
      const cadDlg = document.getElementById('cadastroDialog');
      const openCadLink = document.getElementById('openCadastroLink');
      const openLoginLink = document.getElementById('openLoginFromCadastro');
      const closeCadBtn = document.getElementById('closeCadastroDialog');

      function openCad() { if (cadDlg) cadDlg.showModal(); }
      function closeCad() { if (cadDlg) cadDlg.close(); }

      openCadLink && openCadLink.addEventListener('click', (e) => { e.preventDefault(); closeLogin(); openCad(); });
      openLoginLink && openLoginLink.addEventListener('click', (e) => { e.preventDefault(); closeCad(); openLogin(); });
      closeCadBtn && closeCadBtn.addEventListener('click', closeCad);
      cadDlg && cadDlg.addEventListener('cancel', (e) => { e.preventDefault(); closeCad(); });

      // Lógica do formulário de cadastro
      const cadForm = document.getElementById('cadForm');
      cadForm && cadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // ... (a lógica de validação e envio do cadastro já está no login.js, que foi incluído)
      });
    })();
  </script>
</body>
</html>
