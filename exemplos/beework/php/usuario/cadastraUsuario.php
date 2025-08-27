<?php
        include "../conexao.php";

        $nome=$_POST['nome'];
        $email=$_POST['email'];
        $senha=$_POST['senha'];
    
        $comandoSql="INSERT INTO tbUsuario (nomeUsuario, email, senha) VALUES ('$nome','$email','$senha')";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>