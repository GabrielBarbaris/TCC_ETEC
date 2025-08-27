<?php
        include "../conexao.php";
    
        $comandoSql="SELECT * FROM tbCategoria";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado->num_rows>0){
            $row=$resultado->fetch_assoc();
            echo $row['iCategoria']."|". $row['nomeCategoria'] ."|". $row["descricaoCategoria"];
        }else{
            echo "erro";
        }
    
        $conn->close();
?>