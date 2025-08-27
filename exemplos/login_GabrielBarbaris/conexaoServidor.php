<?php
// Conexão com o banco
$host = "162.250.125.14";
$user = "rosanaregia_ds3aa";
$password = "phila@3aa";
$bdname = "rosanaregia_3aa";

$conn = new mysqli($host,$user,$password,$bdname);

//Verifica conexão
if($conn->connect_error){
    die("Conexão falhou: " . $conn->connect_error);
}
else{
  //  echo "Conexao com servidor esta ok";
}
?>