<?php
        include "../conexao.php";

        $dadosJson = file_get_contents("php://input");

        // Converte o JSON em array associativo
        $dados = json_decode($dadosJson, true);
        $id=$dados['idCategoria'];

        $virgula = false;
        

        // $id=$_POST['idUsuario'];

        // $telefoneUsuario=$_POST['telefoneUsuario'];
        // $fotoUsuario=$_POST['fotoUsuario'];
        // $biografiaUsuario=$_POST['biografiaUsuario'];
        // $cepUsuario=$_POST['cep'];
        $comandoSql="UPDATE tbCategoria SET ";

        if (isset($dados['nomeCategoria'])) {
            // A variável existe e tem um valor (que pode ser nulo ou vazio)
            $categoria = $dados['nomeCategoria'];
            $comandoSql= $comandoSql . " nomeCategoria = '$categoria'";
            // UPDATE tbUsuario SET + nomeUsuario = + variavel nome
            $virgula = true;
        }
        if(isset($dados['descricaoCategoria'])){
            $descricaoCategoria=$dados['descricaoCategoria'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " descricaoCategoria = '$descricaoCategoria'";
        }

        $comandoSql= $comandoSql . " WHERE idCategoria = '$id';";
    
            echo $comandoSql;

        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>