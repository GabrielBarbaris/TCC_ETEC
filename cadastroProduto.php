<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro de Produto</title>

  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/cadastroProduto.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body>

  <?php include "menuAdm.php"; ?>

  <div class="container">

    <!-- Pane da imagem (esquerda) -->
    <div class="imagem">
      <div class="exemplo"></div>
    </div>

    <!-- Pane do formulário (direita) -->
    <div class="formulario">
      <form class="form" id="form" action="processaCadastro.php" method="POST" enctype="multipart/form-data" autocomplete="off" novalidate>
        <!-- Input de arquivo real (escondido) para ser enviado junto com o formulário -->
        <input type="file" id="imagem" name="imagem" accept="image/*" required style="display:none" />

        <h1 class="titulo" style="grid-column: 1 / span 2;">CADASTRO PRODUTO</h1>

        <!-- Campo Nome -->
        <div class="form_content" style="grid-column: 1 / span 2;">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" placeholder="Digite o nome do produto" required maxlength="120" autocomplete="off">
          <a>mensagem de erro</a>
        </div>

        <!-- Preço -->
        <div class="form_content">
          <label for="preco">Preço</label>
          <input type="text" id="preco" name="preco" placeholder="R$ 0,00" inputmode="decimal" autocomplete="off" required>
          <a>mensagem de erro</a>
        </div>

        <!-- Categoria -->
        <div class="form_content">
          <label for="categoria">Categoria</label>
          <select id="categoria" name="categoria" required>
            <option value="">Escolha a categoria</option>
            <?php
              require_once 'conexao.php';
              $comandoSql = 'SELECT * FROM tbcategoria;';
              $result = mysqli_query($conn, $comandoSql);

              if ($result && mysqli_num_rows($result) > 0) {
                  while ($categorias = mysqli_fetch_assoc($result)) {
                      $id = $categorias['id_categoria'];
                      $nome = htmlspecialchars($categorias['nome_categoria'], ENT_QUOTES);
                      echo "<option value='$id'>$nome</option>";
                  }
              } else {
                  echo "<option value=''>Não existe categoria</option>";
              }
            ?>
          </select>
          <a>mensagem de erro</a>
        </div>

        <!-- Tipo -->
        <p class="tipo" style="grid-column: 1 / span 2;">Tipo</p>
        <div class="radio-group" style="grid-column: 1 / span 2;">
          <?php
            require_once 'conexao.php';
            $comandoSql = 'SELECT * FROM tbcorte;';
            $result = mysqli_query($conn, $comandoSql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($cortes = mysqli_fetch_assoc($result)) {
                    $id = $cortes['id_corte'];
                    $nome = htmlspecialchars($cortes['nome_corte'], ENT_QUOTES);
                    echo "<label class='radio-item'>
                            <input type='checkbox' name='tipo[]' value='$id'>
                            <span>$nome</span>
                          </label>";
                }
            } else {
                echo '<p>Não existem cortes cadastrados!</p>';
            }
          ?>
          <a>mensagem de erro</a>
        </div>

        <!-- Radio unidade peso -->
        <p class="tipo" style="grid-column: 1 / span 2;">Medida</p>
        <div class="radio-group" style="grid-column: 1 / span 2;">
          <label class="radio-item">
            <input type="radio" name="medida" value="PESO" checked>
            <span>Peso</span>
          </label>

          <label class="radio-item">
            <input type="radio" name="medida" value="UNIDADE">
            <span>Unidade</span>
          </label>
        </div>

        <!-- Peso mínimo -->
        <div class="form_content" style="grid-column: 1;">
          <label for="peso">Peso mínimo</label>
          <input type="text" id="peso" name="peso" placeholder="Quantidade mínima do produto" inputmode="decimal" autocomplete="off">
          <a>mensagem de erro</a>
        </div>

        <!-- Intervalo -->
        <div class="form_content" style="grid-column: 2;">
          <label for="intervalo">Intervalo</label>
          <input type="text" id="intervalo" name="intervalo" placeholder="Intervalo de peso de cada produto" inputmode="decimal" autocomplete="off">
          <a>mensagem de erro</a>
        </div>

        <!-- Descrição -->
        <div class="form_content" style="grid-column: span 2;">
          <label for="descricao">Descrição</label>
          <textarea id="descricao" name="descricao" placeholder="Digite a descrição do produto neste campo" rows="4" required></textarea>
          <a>mensagem de erro</a>
        </div>

        <!-- Botão -->
        <button type="submit" id="cadastrar">Cadastrar</button>
      </form>
    </div>

  </div>

  <!-- bibliotecas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="./js/cadastraProduto.js"></script>
  <script>
    (function() {
      function updateTipoDisabled() {
        var medidaEl = document.querySelector('input[name="medida"]:checked');
        var isUnidade = !!(medidaEl && medidaEl.value === 'UNIDADE');
        var tipos = document.querySelectorAll('input[name="tipo[]"]');
        tipos.forEach(function(chk) {
          chk.disabled = isUnidade;
          if (isUnidade) chk.checked = false;
          var label = chk.closest('label');
          if (label) label.style.opacity = isUnidade ? '0.6' : '';
        });
      }
      document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'medida') updateTipoDisabled();
      });
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateTipoDisabled);
      } else {
        updateTipoDisabled();
      }
    })();
  </script>

</body>

</html>
