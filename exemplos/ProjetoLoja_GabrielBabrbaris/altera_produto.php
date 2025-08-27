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
    $id=$_POST["id"];
    $n=$_POST["nome"];
    $p=$_POST["preco"];
    $c=$_POST["categoria"];

    /*3-Criando o comando Sql para alteração do registro */
    $comandoSql="update TBPRODUTO set NOMEPROD='$n', PRECOPROD=$p, CODCATE=$c where IDPROD=$id";

    /*4- executando o comando sql */
    $resultado=mysqli_query($con,$comandoSql);

    /*5-Verificando se o comando sql foi executado */
    if ($resultado==true){
        echo "<script> 
            alert ('Alterado com sucesso');

            setTimeout(function(){
                window.location.href='lista_produto_tabela.php'
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