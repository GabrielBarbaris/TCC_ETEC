<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Usuarios</title>
  </head>
  <body>
    <div class="container">
    

        <h1>Lista de Usuario</h1>
        
        <table class="table table-dark">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nome usuario</th>
            <th scope="col">Email</th>
            <th scope="col">Senha</th>
            <th scope="col">Tipo de Usuario</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        include "cabecalho.php";
        include "menu.php";
        /*1-Chamando o arquivos de conexão*/ 
            require "conexaoServidor.php";

        /*2-Criando o comando sql para consulta dos registros */
            $comandoSql="select id_usuario,nome_usuario,email_usuario,senha_usuario,tipo_usuario
                        from tb_usuario
                        ORDER BY id_usuario";

            $conn->set_charset("utf8mb4");

        /*3-Executando o comando sql*/
            $resultado=mysqli_query($conn,$comandoSql);

        /*4-Pegando os dados da consult criada e exibindo */
            while($dados=mysqli_fetch_assoc($resultado)){
                $id=$dados["id_usuario"];
                $nome=$dados["nome_usuario"];
                $email=$dados["email_usuario"];
                $senha=$dados["senha_usuario"];
                $tipo=$dados["tipo_usuario"];

                $asterisco = str_repeat("*",strlen($senha));

                echo"<tr>
                    <th scope='row'>$id</th>
                    <td>$nome</td>
                    <td>$email</td>
                    <td>$asterisco</td>
                    <td>$tipo</td>
                    
                </tr>";
            }
        ?>
        </tbody>
        </table>
    </div>
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="js/scriptsUsuariosServidor.js"></script>
  </body>
</html>