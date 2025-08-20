<?php
include "conexao.php";
$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];



$sql2 = "SELECT * FROM tbUsuario WHERE telefone = '$telefone';";
$result2 = $conn->query($sql2);


if ($result2->num_rows == 0) {
    $sql = "INSERT INTO tbUsuario(nome,sobrenome,senha,tipo_usuario,endereco,telefone)
            VALUES('$nome','$sobrenome','$senha','cliente','','$telefone');";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "ok";
    } else {
        echo "erro";
    }
} else {
    echo "erro";
}
$conn->close();
