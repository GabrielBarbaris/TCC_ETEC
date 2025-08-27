<?php
        include "../conexao.php";

        $idUsuario=$_DELETE['idUsuario'];

    
        $comandoSql="DELETE FROM tbusuario WHERE idUsuario='$idUsuario'";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado === true){
            echo "ok";
        }else{
            echo "erro";
        }
    
        $conn->close();
?>