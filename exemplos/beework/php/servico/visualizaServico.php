<?php
        include "../conexao.php";
    
        $comandoSql="SELECT * FROM tbServico";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado->num_rows>0){
            $row=$resultado->fetch_assoc();
            echo $row['idServico']."|". $row['descricao'] ."|". $row["codCategoria"] ."|". $row['mediaValor']."|". $row['tituloServico']."|". $row['dataHora']."|". $row['fotoServico']."|". $row['codUsuario'];
        }else{
            echo "erro";
        }
        $conn->close();
?>