<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/cadastroProduto.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

</head>

<body>

      <?php
      include "menuAdm.php";
      ?>

    <div class="container">
      <div class="imagem">
        <!-- aqui vai sua área de imagem -->
      </div>
      <div class="formulario">
  <div class="form">
    <p class="titulo" style="grid-column: 1 / span 2;">CADASTRO PRODUTO</p>



    <!-- Campo Nome (vai para coluna 2) -->
    <div class="form_content" style="grid-column: 1 / span 2;">
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" placeholder="Digite o nome do produto">
      <a>mensagem de erro</a>
    </div>

    <!-- Linha Preço e Categoria ocupando as 2 colunas -->
    <div class="form_row" style="grid-column: 1 / span 2;">
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

    <!-- Tipo (também ocupa as 2 colunas) -->
    <p class="tipo" style="grid-column: 1 / span 2;">Tipo</p>
    <div class="radio-group" style="grid-column: 1 / span 2;">
      <!-- seus checkboxes aqui -->
      <label class="radio-item"> 
        <input type="checkbox" name="tipo" value="manta"> <span>Manta</span> </label> 
        <label class="radio-item"> <input type="checkbox" name="tipo" value="bife">
         <span>Bife</span> </label> <label class="radio-item"> <input type="checkbox" name="tipo" value="panela">
          <span>Panela</span> </label> <label class="radio-item"> <input type="checkbox" name="tipo" value="moida">
           <span>Moída</span> </label> <label class="radio-item"> <input type="checkbox" name="tipo" value="moida"> 
           <span>Peça</span> </label> <label class="radio-item"> <input type="checkbox" name="tipo" value="moida"> 
           <span>Strogonoff</span> </label> <label class="radio-item"> <input type="checkbox" name="tipo" value="moida">
            <span>Tirinha</span> </label> <label class="radio-item"> <input type="checkbox" name="tipo" value="moida"> <span>Medalhão</span> </label>
             <label class="radio-item"> <input type="checkbox" name="tipo" value="moida"> <span>Espetinho</span> </label>
    </div>

    <!-- Peso mínimo (coluna esquerda) -->
    <div class="form_content" style="grid-column: 1;">
      <label for="peso">Peso mínimo</label>
      <input type="text" id="peso" name="peso" placeholder="Quantidade mínima do produto">
      <a>mensagem de erro</a>
    </div>

    <!-- Intervalo (coluna direita) -->
    <div class="form_content" style="grid-column: 2;">
      <label for="intervalo">Intervalo</label>
      <input type="text" id="intervalo" name="intervalo" placeholder="Intervalo de peso de cada produto">
      <a>mensagem de erro</a>
    </div>

    <!-- Descrição (ocupa as 2 colunas) -->
    <div class="form_content" style="grid-column: 1 / span 2;">
      <div class="descricao">
        <label for="descricao">Descrição</label>
        <input type="input" id="descricao" name="descricao"
          placeholder="Digite a descrição do produto neste campo">
        <a>mensagem de erro</a>
      </div>
    </div>

  </div>
</div>
    </div>





</body>

</html>