<?php
        include "../conexao.php";

        $dadosJson = file_get_contents("php://input");

        // Converte o JSON em array associativo
        $dados = json_decode($dadosJson, true);
        $id=$dados['idServico'];

        $virgula = false;
        

        // $id=$_POST['idUsuario'];

        // $telefoneUsuario=$_POST['telefoneUsuario'];
        // $fotoUsuario=$_POST['fotoUsuario'];
        // $biografiaUsuario=$_POST['biografiaUsuario'];
        // $cepUsuario=$_POST['cep'];
        $comandoSql="UPDATE tbServico SET ";

        if (isset($dados['nomeServico'])) {
            // A variável existe e tem um valor (que pode ser nulo ou vazio)
            $servico = $dados['nomeServico'];
            $comandoSql= $comandoSql . " nomeServico = '$servico'";
            // UPDATE tbUsuario SET + nomeUsuario = + variavel nome
            $virgula = true;
        }
        if(isset($dados['descricao'])){
            $descricao=$dados['descricao'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " descricao = '$descricao'";
        }
        if(isset($dados['codCategoria'])){
            $codCategoria=$dados['codCategoria'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " codCategoria = '$codCategoria'";
        }
        if(isset($dados['mediaValor'])){
            $mediaValor=$dados['mediaValor'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " mediaValor = '$mediaValor'";
        }
        if(isset($dados['tituloServico'])){
            $tituloServico=$dados['tituloServico'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " tituloServico = '$tituloServico'";
        }
        if(isset($dados['dataHora'])){
            $dataHora=$dados['dataHora'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " dataHora = '$dataHora'";
        }
        if(isset($dados['fotoServico'])){
            $fotoServico=$dados['fotoServico'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " fotoServico = '$fotoServico'";
        }
        if(isset($dados['codUsuario'])){
            $codUsuario=$dados['codUsuario'];
            if($virgula === true){
                $comandoSql = $comandoSql . ",";
            }
            $comandoSql = $comandoSql . " codUsuario = '$codUsuario'";
        }

        $comandoSql= $comandoSql . " WHERE idServico = '$id';";
    
            echo $comandoSql;

        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>