<?php
require "conexaoServidor.php";

$nomeUsuario = $_GET['nome'];

$comandoSql = "SELECT * FROM tb_usuario WHERE nome_usuario LIKE '%$nomeUsuario%'";
$resultado = mysqli_query($conn, $comandoSql);

$usuarios = [];

while ($dados = $resultado->fetch_assoc()) {
    $usuarios[] = $dados;
}

echo json_encode($usuarios);
?>
