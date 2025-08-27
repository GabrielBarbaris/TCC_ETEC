<?php
        include "../conexao.php";
    
        $comandoSql="SELECT * FROM tbUsuario";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado->num_rows>0){
            $row=$resultado->fetch_assoc();
            echo $row['idUsuario']."|". $row['nomeUsuario'] ."|". $row["tipoUsuario"] ."|". $row['fotoUsuario'];
        }else{
            echo "erro";
        }
        $conn->close();
?>