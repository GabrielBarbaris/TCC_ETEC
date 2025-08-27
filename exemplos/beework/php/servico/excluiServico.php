<?php
        include "../conexao.php";

        $idServico=$_DELETE['idServico'];
    
        $comandoSql="DELETE FROM tbServico WHERE idServico='$idServico'";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>