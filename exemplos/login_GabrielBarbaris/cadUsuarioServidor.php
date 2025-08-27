<?php
    include "conexaoServidor.php";
    $nome= $_POST['nome'];
    $email= $_POST['email'];
    $senha= $_POST['senha'];

    $conn->set_charset("utf8mp4");

    $comandoSql = "INSERT INTO tb_usuario (nome_usuario,email_usuario,senha_usuario,tipo_usuario)
                   VALUES('$nome','$email','$senha',0)";

    $result = $conn->query($comandoSql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        echo "ok";
    } else {
        echo "erro";
    }

    $conn->close();

?>