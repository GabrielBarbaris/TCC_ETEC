<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/cadastroProduto.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

</head>
<body>
  
<div class="tela-inicial">
<header class="HEADER">
        <div class="overlap-17">
          <div class="rectangle-10"></div>
          <div class="rectangle-11"></div>

          <div class="CONES">
            <div class="search">
              <label for="searchInput">
              </label>
              <input type="text" id="searchInput" placeholder="pesquisar">
            </div>
            <button onclick="cadastrar_cliente()">
              <img class="user-user" src="img/login.png" />
            </button>

            <button>
              <img class="basket" src="img/pedido.png" />
            </button>
          </div>
          <img class="logo-2" src="img/logo.png" />



        </div>
      </header>
      <div class="container">
    <div class="imagem">
        <!-- aqui vai sua área de imagem -->
    </div>
    <div class="formulario">
        <div class="form">
            <p class="titulo">CADASTRO PRODUTO</p>

            <!-- Campo Nome -->
            <div class="form_content">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome do produto">
                <a>mensagem de erro</a>
            </div>

            <!-- Linha com Preço e Categoria -->
            <div class="form_row">
                <div class="form_content">
                    <label for="preco">Preço</label>
                    <input type="text" id="preco" name="preco" placeholder="R$00,00">
                    <a>mensagem de erro</a>
                </div>
                <div class="form_content">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria">
                        <option>Escolha a categoria</option>
                        <!-- outras opções -->
                    </select>
                    <a>mensagem de erro</a>
                </div>
            </div>

            <div class="radio-group">
                <p class="tipo">Tipo</p>
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
                <!-- continue com os outros -->
            </div>

        </div>
    </div>
</div>

          </div>
      </div>
      </div>  
      
      
</body>
</html>