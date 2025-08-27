<?php
        include "../conexao.php";

        $nome=$_POST['nome'];
        $descricao=$_POST['email'];

        $comandoSql="INSERT INTO tbCategoria (nomeCategoria, descricaoCategoria) VALUES ('$nome','$descricao')";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>