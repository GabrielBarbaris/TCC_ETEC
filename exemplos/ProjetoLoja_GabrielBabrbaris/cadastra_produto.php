<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
    <?php
        /*1-Chamando o arquivos de conexão*/ 
        require "conexao.php";

        /*2-Pegando os dados vindos do formulario */ 
        $n=$_POST["nome"];
        $p=$_POST["preco"];
        $c=$_POST["categoria"];

        /*3-Criando o comando sql para inserçao do registro */
        $comandoSql="insert into TBPRODUTO
        (NOMEPROD, PRECOPROD, CODCATE)
        values
        ('$n', $p, $c)";
     

        /*4- executando o comando sql */
        $resultado=mysqli_query($con, $comandoSql);

        /*5-verificando se o comando sql foi executado*/
        if($resultado==true){
           // echo "Cadastro com sucesso";
           echo"<script>
            alert('cadastrado com sucesso');
            setTimeout(function(){
                window.location.href='lista_produto_tabela.php'
            },2000);

           </script>";
        }else{
           // echo "Erro no Cadastro";
           echo"<script>
           alert('erro no  cadastro');
           setTimeout(function(){
               window.location.href='index.html'
           },2000);

          </script>";
        }

    ?>
</body>
</html>