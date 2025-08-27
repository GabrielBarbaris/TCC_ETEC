<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Exclusão de produto</title>
  </head>
  <body>
  <?php //require "index.php" ?>
   <div class="container">
    <h3> Exclusão de Produto </h3>
   <?php

     /* 1- chamando o arquivo de conexão */
     include "conexao.php";
	 
    /*2- pegando o valor do id vindo da confirmação*/
	  $id_categoria=$_GET["id"];
    
    /*3- criando o comando sql para exclusao do registro */ 
	  $comandoSql="delete from TBCATEGORIA where IDCATE='$id_categoria'";
   
    /*4- executando o comando sql */
	  $resultado=mysqli_query($con,$comandoSql);
   
    /*5- verificando se o comando sql foi executado */
	  if($resultado==true){
		echo "<script> 
                alert('Excluido com sucesso'); 
                
                  setTimeout(function(){
                 window.location.href='lista_categoria_tabela.php';
                 },200);
                
				

                 </script>";
	  }
	 else{
		"<script> 
                alert('Erro na exclusão');      
                 setTimeout(function(){
                 window.location.href='lista_categoria_tabela.php';
                 },1000);

                 </script>";
	 }

  ?>
  </div>

    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>