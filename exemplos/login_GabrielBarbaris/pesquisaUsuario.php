<?php
include "conexao.php";

$email = $_POST["email"];
$senha = $_POST["senha"];

$comandoSql = "SELECT id_usuario, nome_usuario, tipo_usuario FROM tbusuario WHERE email_usuario = '$email'
and senha_usuario='$senha'";

$resultado = $conn->query($comandoSql);

if ($resultado->num_rows > 0){
    $row= $resultado->fetch_assoc();
    echo  $row['id_usuario'] ."|". $row['nome_usuario'] ."|". $row['tipo_usuario'];
}else{
    echo "erro";
}
$conn->close();

?>