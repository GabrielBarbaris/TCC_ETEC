<?php
include "conexao.php";

$telefone = $_POST["telefone"];
$senha = $_POST["senha"];

$comandoSql = "SELECT id_usuario, nome, tipo_usuario FROM tbUsuario WHERE telefone = '$telefone'
and senha='$senha'";

$resultado = $conn->query($comandoSql);

if ($resultado->num_rows > 0){
    $row= $resultado->fetch_assoc();
    echo  $row['id_usuario'] ."|". $row['nome'] ."|". $row['tipo_usuario'];
}else{
    echo "erro";
}
$conn->close();

?>