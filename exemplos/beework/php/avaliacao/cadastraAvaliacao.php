<?php
        include "../conexao.php";

        $dataHora=$_POST['dataHora'];
        $comentarioAvaliacao=$_POST['comentarioAvaliacao'];
        $codUsuario=$_POST['codUsuario'];
        $codServico=$_POST['codServico'];
        $anonimo=$_POST['anonimo'];
        $estrelas=$_POST['estrelas'];
    
        $comandoSql="INSERT INTO tbUsuario (dataHora, comentarioAvaliacao, codUsuario, codServico, anonimo, estrelas) VALUES ('$dataHora','$comentarioAvaliacao','$codUsuario','$codServico','$anonimo','$estrelas')";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>