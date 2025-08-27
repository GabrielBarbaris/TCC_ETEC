<?php
        include "../conexao.php";

        $dadosJson = file_get_contents("php://input");

        // Converte o JSON em array associativo
        $dados = json_decode($dadosJson, true);
        $id=$dados['idUsuario'];

        $virgula = false;
        

        // $id=$_POST['idUsuario'];

        // $telefoneUsuario=$_POST['telefoneUsuario'];
        // $fotoUsuario=$_POST['fotoUsuario'];
        // $biografiaUsuario=$_POST['biografiaUsuario'];
        // $cepUsuario=$_POST['cep'];
        $comandoSql="UPDATE tbUsuario SET ";

        if (isset($dados['nomeUsuario'])) {
            // A variável existe e tem um valor (que pode ser nulo ou vazio)
            $nome = $dados['nomeUsuario'];
            $comandoSql= $comandoSql . " nomeUsuario = '$nome'";
            // UPDATE tbUsuario SET + nomeUsuario = + variavel nome
            $virgula = true;
        }
        if(isset($dados['telefone'])){
            $telefoneUsuario=$dados['telefoneUsuario'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " telefoneUsuario = '$telefoneUsuario'";
        }
        if(isset($dados['fotoUsuario'])){
            $fotoUsuario=$dados['fotoUsuario'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " fotoUsuario = '$fotoUsuario'";
        }
        if(isset($dados['biografiaUsuario'])){
            $biografiaUsuario=$dados['biografiaUsuario'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " biografiaUsuario = '$biografiaUsuario'";
        }
        if(isset($dados['cep'])){
            $cep=$dados['cep'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " cep = '$cep'";
        }

        $comandoSql= $comandoSql . " WHERE idUsuario = '$id';";
    
            echo $comandoSql;

        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>