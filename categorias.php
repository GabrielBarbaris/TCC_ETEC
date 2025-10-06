<?php
session_start();
$clienteLogado = isset($_SESSION['id_cliente']) || isset($_SESSION['cliente_id']) || isset($_SESSION['id_usuario']) || isset($_SESSION['usuario']) || isset($_SESSION['cliente']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/index.css" />
  <link rel="stylesheet" href="css/telaProduto.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
  <title>Casa de Carnes - Categorias</title>
</head>

<body>
  <div class="tela-inicial">
    <div class="corpo">
      <div class="corpo_1">
        <section class="churrasco_qualidade">
          <div class="separao">
            <?php
            require 'conexao.php';

            // Determina a categoria pelo código (preferencial) ou nome
            $catId = 0;
            if (isset($_GET['id'])) { $catId = (int)$_GET['id']; }
            elseif (isset($_GET['cat'])) { $catId = (int)$_GET['cat']; }

            $catNomeParam = '';
            if (isset($_GET['nome'])) { $catNomeParam = trim((string)$_GET['nome']); }
            elseif (isset($_GET['categoria'])) { $catNomeParam = trim((string)$_GET['categoria']); }
            elseif (isset($_GET['c'])) { $catNomeParam = trim((string)$_GET['c']); }

            $categoriaTitulo = 'categoria';
            $categoriaValida = false;

            if ($catId > 0) {
              if ($stmt = $conn->prepare('SELECT nome_categoria FROM tbCategoria WHERE id_categoria = ? LIMIT 1')) {
                $stmt->bind_param('i', $catId);
                if ($stmt->execute()) {
                  $res = $stmt->get_result();
                  if ($row = $res->fetch_assoc()) {
                    $categoriaTitulo = 'categoria ' . htmlspecialchars((string)$row['nome_categoria'], ENT_QUOTES);
                    $categoriaValida = true;
                  }
                }
                $stmt->close();
              }
            } elseif ($catNomeParam !== '') {
              if ($stmt = $conn->prepare('SELECT id_categoria, nome_categoria FROM tbCategoria WHERE UPPER(nome_categoria) = UPPER(?) LIMIT 1')) {
                $stmt->bind_param('s', $catNomeParam);
                if ($stmt->execute()) {
                  $res = $stmt->get_result();
                  if ($row = $res->fetch_assoc()) {
                    $catId = (int)$row['id_categoria'];
                    $categoriaTitulo = 'categoria ' . htmlspecialchars((string)$row['nome_categoria'], ENT_QUOTES);
                    $categoriaValida = true;
                  }
                }
                $stmt->close();
              }
            }
            ?>
            <p class="p"><span class="text-wrapper"><?php echo $categoriaTitulo ? $categoriaTitulo : 'categoria'; ?></span></p>
          </div>

          <?php
          if ($categoriaValida && $catId > 0) {
            // Lista produtos da categoria informada
            if ($stmt = $conn->prepare('SELECT p.* FROM tbProduto p WHERE p.cod_categoria = ? ORDER BY p.nome_produto')) {
              $stmt->bind_param('i', $catId);
              if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                  while ($produto = $result->fetch_assoc()) {
                    $id = (int)$produto['id_produto'];
                    $nome = htmlspecialchars((string)$produto['nome_produto'], ENT_QUOTES);
                    $descricao = htmlspecialchars((string)$produto['descricao'], ENT_QUOTES);
                    $preco = number_format((float)$produto['preco'], 2, ',', '.');
                    $url = htmlspecialchars((string)$produto['imagem_url'], ENT_QUOTES);

                    echo "<div class='picanha'>
                            <div class='overlap-4'>
                              <img class='img-3' src='$url' alt='$nome' />
                              <div class='rectangle-5'></div>
                              <div class='text-wrapper-17'>$nome</div>
                              <p class='text-wrapper-7'>$descricao</p>
                              <p class='r-KG'><span class='text-wrapper'>R$$preco </span> <span class='text-wrapper-8'>KG</span></p>
                              <div class='boto btn-add-prod' data-prod-id='$id' tabindex='0' role='button'>
                                <div class='overlap-group-2'>
                                  <div class='rectangle-3'></div>
                                  <div class='text-wrapper-9'>ADICIONAR</div>
                                </div>
                              </div>
                            </div>
                          </div>";
                  }
                } else {
                  echo '<p>Não existem produtos cadastrados nesta categoria.</p>';
                }
              } else {
                echo '<p>Falha ao buscar produtos da categoria.</p>';
              }
              $stmt->close();
            } else {
              echo '<p>Falha ao preparar consulta de produtos.</p>';
            }
          } else {
            echo '<p>Categoria não informada ou inválida. Utilize categorias.php?id={codigo}.</p>';
          }
          ?>
        </section>
      </div>

      <!-- coluna direita (sacola) opcional -->
      <div class="corpo_2">
        <!-- conteúdo opcional -->
      </div>
    </div>

    <!-- HEADER replicado -->
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

          <button onclick="cadastrar_cliente()">
            <a href="cadastra.php"><img class="user-user" src="img/login.png" alt="login" /></a>
          </button>

          <button title="Ver sacola">
            <img class="basket" src="img/pedido.png" alt="pedido" />
          </button>
        </div>

        <img class="logo-2" src="img/logo.png" alt="Logo" />
      </div>
    </header>
  </div>

  <script src="./js/index.js"></script>
</body>

</html>
