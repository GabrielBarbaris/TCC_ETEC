<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Lista de Categoria Tabela</title>
  </head>
  <body>
    <div class="container">
        <h1>Lista de Categoria Tabela</h1>
        <h3><a href="index.html">Voltar</a> </h3>

        <table class="table table-dark">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">NOME CATEGORIA</th>
            <th scope="col">EDITAR</th>
            <th scope="col">EXCLUIR</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        /*1-Chamando o arquivos de conexão*/ 
            require "conexao.php";

        /*2-Criando o comando sql para consulta dos registros */
            $comandoSql="select IDCATE, NOMECATE
                        from TBCATEGORIA
                        ORDER BY IDCATE";

        /*3-Executando o comando sql*/
            $resultado=mysqli_query($con,$comandoSql);

        /*4-Pegando os dados da consult criada e exibindo */
            while($dados=mysqli_fetch_assoc($resultado)){
                $id=$dados["IDCATE"];
                $nome=$dados["NOMECATE"];

                echo"<tr>
                    <th scope='row'>$id</th>
                    <td>$nome</td>

                    <td>
                      <a href='frm_altera_categoria.php?id=$id'>
                        <button type='button' class='btn btn-light'>Editar</button>
                      </a>
                    </td>
                    <td>
                      <a href='del_categoria.php?id=$id'>
                      <button type='button' class='btn btn-light'>Excluir</button></td>
                      </a>
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
  </body>
</html>