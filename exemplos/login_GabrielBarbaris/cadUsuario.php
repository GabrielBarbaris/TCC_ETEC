<?php
    include "conexao.php";
    $nome= $_POST['nome'];
    $email= $_POST['email'];
    $senha= $_POST['senha'];

    $comandoSql = "INSERT INTO tbusuario (nome_usuario,email_usuario,senha_usuario,tipo_usuario)
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