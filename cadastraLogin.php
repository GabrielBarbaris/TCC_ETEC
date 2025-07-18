<?php
    include "conexao.php";
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    
    $sql = "INSERT INTO usuario(nome,sobrenome,senha,tipo_usuario,endereco,telefone)
            VALUES('$nome','$sobrenome','$senha','cliente','','$telefone');";
    
            $result = $conn->query($sql);
            
    echo"$sql";
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        echo "ok";
    } else {
        echo "erro";
    }

    $conn->close();

?>