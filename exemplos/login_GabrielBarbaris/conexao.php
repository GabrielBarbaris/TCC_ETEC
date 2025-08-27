<?php
// Conexão com o banco
$host = "localhost";
$user = "root";
$password = "";
$bdname = "bdsistema_GabrielBarbaris";

$conn = new mysqli($host,$user,$password,$bdname);

//Verifica conexão
if($conn->connect_error){
    die("Conexão falhou: " . $conn->connect_error);
}
else{
    //echo "Conexao ok";
}
?>