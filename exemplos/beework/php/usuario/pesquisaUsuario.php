<?php
        include "../conexao.php";

        $email=$_POST['email'];
        $senha=$_POST['senha'];

    
        $comandoSql="SELECT idUsuario, nomeUsuario, tipoUsuario, fotoUsuario FROM tbusuario WHERE email='$email' and senha='$senha'";
    
        $resultado=$conn->query($comandoSql);
    
        if($resultado->num_rows>0){
            $row=$resultado->fetch_assoc();
            echo $row['idUsuario']."|". $row['nomeUsuario'] ."|". $row["tipoUsuario"] ."|". $row['fotoUsuario'];
        }else{
            echo "erro";
        }
    
        $conn->close();
?>