<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração Produto</title>
</head>
<body>
    <?php
    /*1-Chamando o arquivos de conexão*/ 
    require "conexao.php";

    /*2-Pegando os dados findos do formlario e armazenando em variaveis */
    $id=$_POST["ID"];
    $n=$_POST["NOME"];


    /*3-Criando o comando Sql para alteração do registro */
    $comandoSql="update TBCATEGORIA set NOMECATE='$n' where IDCATE=$id";
    
    /*4- executando o comando sql */
    $resultado=mysqli_query($con,$comandoSql);

    /*5-Verificando se o comando sql foi executado */
        if ($resultado==true){
        echo "<script> 
            alert ('Alterado com sucesso');

            setTimeout(function(){
                window.location.href='lista_categoria_tabela.php'
            },2000);
        </script>";
    }else{
        echo "<script> 
            alert ('Erro na alteração');

            setTimeout(function(){
               window.location.href='index.html'
           },2000);
        </script>";
    
    }
    
    
    ?>
</body>
</html>