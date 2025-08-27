<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Cadastro de Usuário</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="css/signin.css" rel="stylesheet">
  </head>
  <body>
   
	<div class="container">
	<?php
	  include "cabecalho.php";
	  include "menu.php";
	
	?>
	 
      <form class="form-signin" id="formCadastro">
	   <div class="alert alert-danger" role="alert" id="mensagem" style="display: none;"> Mensagem  </div>
        <h2 class="mb-4 text-center">Cadastro de Usuário</h2>

        <div class="form-group">
          <label for="nome">Nome:</label>
          <input type="text" class="form-control" id="nome" name="nome" value="nome completo">
        </div>

        <div class="form-group">
          <label for="email">E-mail:</label>
          <input type="email" class="form-control" id="email" name="email" value="name@example.com">
        </div>

        <div class="form-group">
          <label for="senha">Senha:</label>
          <input type="password" class="form-control" id="senha" name="senha" value="senha">
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="button" id="botaoCadastrar">Cadastrar</button>
     
	  </form>
    </div>
	
   

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="js/scriptsUsuarios.js"></script>
  </body>
</html>
