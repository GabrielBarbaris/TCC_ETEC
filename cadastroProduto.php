<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/cadastroProduto.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body>

  <?php include "menuAdm.php"; ?>

  <div class="container">

    <!-- Área de imagem -->
    <div class="imagem">
      <div class="exemplo"></div>
      <button>Inserir imagem</button>
    </div>

    <!-- Formulário -->
    <div class="formulario">
      <form class="form" id="form">

        <p class="titulo" style="grid-column: 1 / span 2;">CADASTRO PRODUTO</p>

        <!-- Campo Nome -->
        <div class="form_content" style="grid-column: 1 / span 2;">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" placeholder="Digite o nome do produto">
          <a>mensagem de erro</a>
        </div>

        <!-- Preço -->
        <div class="form_content">
          <label for="preco">Preço</label>
          <input type="text" id="preco" name="preco" placeholder="R$00,00">
          <a>mensagem de erro</a>
        </div>
        
        <!-- Categoria -->
        <div class="form_content">
          <label for="categoria">Categoria</label>
          <select id="categoria" name="categoria">
            <option>Escolha a categoria</option>
            <option>Escolha a categoria</option>
            <option>Escolha a categoria</option>
            <!-- outras opções -->
          </select>
          <a>mensagem de erro</a>
        </div>


        <!-- Tipo -->
        <p class="tipo" style="grid-column: 1 / span 2;">Tipo</p>

        <div class="radio-group" style="grid-column: 1 / span 2;">
          <label class="radio-item">
            <input type="checkbox" name="tipo" value="manta">
            <span>Manta</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="bife">
            <span>Bife</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="panela">
            <span>Panela</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="moida">
            <span>Moída</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="peca">
            <span>Peça</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="strogonoff">
            <span>Strogonoff</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="tirinha">
            <span>Tirinha</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="medalhao">
            <span>Medalhão</span>
          </label>

          <label class="radio-item">
            <input type="checkbox" name="tipo" value="espetinho">
            <span>Espetinho</span>
          </label>
        </div>

        <!-- Radio unidade peso -->
        <p class="tipo" style="grid-column: 1 / span 2;">Medida</p>
        <div class="radio-group" style="grid-column: 1 / span 2;">
          <label class="radio-item">
            <input type="radio" name="medida" value="Kilograma">
            <span>Kilograma</span>
          </label>

          <label class="radio-item">
            <input type="radio" name="medida" value="Unidade">
            <span>Unidade</span>
          </label>

        </div>

        <!-- Peso mínimo -->
        <div class="form_content" style="grid-column: 1;">
          <label for="peso">Peso mínimo</label>
          <input type="text" id="peso" name="peso" placeholder="Quantidade mínima do produto">
          <a>mensagem de erro</a>
        </div>

        <!-- Intervalo -->
        <div class="form_content" style="grid-column: 2;">
          <label for="intervalo">Intervalo</label>
          <input type="text" id="intervalo" name="intervalo" placeholder="Intervalo de peso de cada produto">
          <a>mensagem de erro</a>
        </div>

        <!-- Descrição -->

        <div class="form_descricao">
          <label for="descricao">Descrição</label>
          <input type="input" id="descricao" name="descricao" placeholder="Digite a descrição do produto neste campo">
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

</body>

</html>