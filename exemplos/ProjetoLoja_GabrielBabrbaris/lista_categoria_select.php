<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Listagem de categoria</title>
  </head>
  <body>
  <?php

function lista_categoria(){

echo "<div class='input-group mb-3'>
<div class='input-group-prepend'>
<label class='input-group-text' for='inputGroupSelect01'>Categoria</label>
</div>
<select class='custom-select' id='inputGroupSelect01' name='categoria'>";

/*1- chamando o arquivo de conexão */
require "conexao.php";

/*2-criando o comando sql para consulta dos registros */
$comandoSql="select * from tbcategoria";

/*3-executando comando sql */
$resultado=mysqli_query($con,$comandoSql);

/*4-pegando os dados da consulta e exibindo */
while($dados=mysqli_fetch_assoc($resultado)){
  $id=$dados["IDCATE"];
  $nome=$dados["NOMECATE"];

  echo "Id: $id<br>";
  echo "Nome: $nome<br><br>";

 echo " <option value=$id>$nome</option>";
}

echo "</select>";
echo " </div>"  ;

  }


  function lista_categoria_id($cod){

    echo "<div class='input-group mb-3'>
    <div class='input-group-prepend'>
    <label class='input-group-text' for='inputGroupSelect01'>Categoria</label>
    </div>
    <select class='custom-select' id='inputGroupSelect01' name='categoria'>";
    
    /*1- chamando o arquivo de conexão */
    require "conexao.php";
    
    /*2-criando o comando sql para consulta dos registros */
    $comandoSql="select * from tbcategoria";
    
    /*3-executando comando sql */
    $resultado=mysqli_query($con,$comandoSql);
    
    /*4-pegando os dados da consulta e exibindo */
    while($dados=mysqli_fetch_assoc($resultado)){
      $id=$dados["IDCATE"];
      $nome=$dados["NOMECATE"];
    
    //  echo "Id: $id<br>";
    //  echo "Nome: $nome<br><br>";
    if ($id==$cod)
     echo " <option value=$id selected>$nome</option>";

    else
    echo " <option value=$id>$nome</option>";
     
    }
    
    echo "</select>";
    echo " </div>"  ;
    
      }









?>


    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>