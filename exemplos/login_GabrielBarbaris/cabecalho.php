<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<style>
	 .sair{
		  float: right;
	  }
	
	</style>
  </head>
  <body>
   <div class="container">
  <?php 
       
       /*pegando o id do usuario vindo pela url*/
       /*$id=$_GET["id"];
       $nome=$_GET["nome"];
       $tipo=$_GET["tipo"];*/

       /*1- incluir a conexão */
       include "conexao.php";

       /*2- pegando o id do usuario vindo pela url*/
       $id=$_GET["id"];

       /*3- criando o comando sql para pegar os dados do usuario que logou */
       $comandoSql = "select nome_usuario, tipo_usuario from tbusuario where id_usuario = $id";

       /*4- executando o comando sql criado acima e pegando o resultado */
       $result = $conn->query($comandoSql);
       if ($result->num_rows > 0){
        $row = $result->fetch_assoc();

        $nome=$row["nome_usuario"];
        $tipo=$row["tipo_usuario"];

      }

       /*CRIAND VARIAVEIS DE SESSAO COM OS DADOS DO USUARIO */

       session_start();
       $_SESSION["id"] = $id;
       $_SESSION["nome"] = $nome;
       $_SESSION["tipo"] = $tipo;

       if($_SESSION["tipo"]==1)
       $descricaoTipo="admin";
      else
      $descricaoTipo="comum";

      /*exibindo as informações do usuario */
      echo "<div class='alert alert-primary' role='alert'>";
      echo "bem vindo, ". $_SESSION['nome']."<br>
      voce é o usuario ". $_SESSION['id'].
      " e esta logado como ". $descricaoTipo;

      echo "<div class='sair'> <a href='sair.php'> sair </a> </div>";
      echo"</div>"
		
	
?>
</div>
    
	
	
	  <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="js/scripts.js"> </script>
  </body>
  
</html>






