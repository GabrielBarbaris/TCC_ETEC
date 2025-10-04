<?php
session_start();
include "conexao.php";

$telefone = $_POST["telefone"];
$senha = $_POST["senha"];

$comandoSql = "SELECT id_usuario, nome, tipo_usuario FROM tbUsuario WHERE telefone = '$telefone'
and senha='$senha'";

$resultado = $conn->query($comandoSql);

if ($resultado->num_rows > 0){
    $row= $resultado->fetch_assoc();

    // Salva dados essenciais na sessão para serem usados em getCliente.php e na aplicação
    $_SESSION['id_usuario'] = (int)$row['id_usuario'];
    $_SESSION['id_cliente'] = (int)$row['id_usuario']; // compatibilidade
    $_SESSION['cliente_id'] = (int)$row['id_usuario']; // compatibilidade
    $_SESSION['usuario'] = $row['nome'];
    $_SESSION['cliente'] = ($row['tipo_usuario'] === 'cliente');
    $_SESSION['clienteLogado'] = true;

    echo  $row['id_usuario'] ."|". $row['nome'] ."|". $row['tipo_usuario'];
}else{
    echo "erro";
}
$conn->close();

?>