<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	<style>
	 /* Fundo do modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
}

/* Conteúdo da caixa */
.modal-content {
  background-color: white;
  margin: 10% auto;
  padding: 20px;
  width: 400px;
  border-radius: 8px;
  box-shadow: 0 0 10px #000;
}

/* Botão de fechar */
.close {
  float: right;
  font-size: 28px;
  cursor: pointer;
}
	
	</style>
  </head>
  <body>
  
    <div class="container">
  <nav class="navbar navbar-expand-lg bg-body-tertiary mt-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Biblioteca</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Usuário
          </a>
          <ul class="dropdown-menu">
            <?php
              $id=$_SESSION["id"];
			  
              if($_SESSION["tipo"]==1){
               echo " <li><a class='dropdown-item' href='cadastraUsuario.php?id=$id'>Cadastrar</a></li>";
               echo "<li><a class='dropown-item' href='cadastraUsuarioServidor.php?id=$id'>Cadastrar Usuario Servidor</a></li>";
			         echo " <li><a class='dropdown-item' href='#'>Alterar</a></li>";
               echo "<li><a class='dropdown-item' href='ListaUsuario.php?id=$id'>Listar</a></li>";
               echo "<li><a class='dropdown-item' href='ListaUsuarioLocal.php?id=$id'>Listar Local</a></li>";
              }
              else if ($_SESSION["tipo"]==0){
                  echo "<li><a class'dropdown-item' href='pesquisaUsuarioServidor.php?id=$id'>Pesquisa<a/></li>";
              }
            ?>
            
            
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

 
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
    <script src="js/scripts.js"> </script>
  </div>
  
  <!-- Modal Bootstrap -->
<div class="modal fade" id="meuModal" tabindex="-1" aria-labelledby="meuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="meuModalLabel">Cadastro de Usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="conteudoModal">
        <!-- Aqui o conteúdo será carregado via AJAX -->
        <div class="text-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  
  
  </body>
</html>






