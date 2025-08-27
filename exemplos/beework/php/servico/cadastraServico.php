<?php
        include "../conexao.php";

        $descricao=$_POST['descricao'];
        $codCategoria=$_POST['codCategoria'];
        $mediaValor=$_POST['mediaValor'];
        $tituloServico=$_POST['tituloServico'];
        $dataHora=$_POST['dataHora'];
        $fotoServico=$_POST['fotoServico'];
        $codServico=$_POST['codUsuario'];
    
        $comandoSql="INSERT INTO tbUsuario (descricaoUsuario, codCategoria, mediaValor) VALUES ('$descricao','$codCategoria','$mediaValor','$tituloServico','$dataHora','$fotoServico','$codCategoria')";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>