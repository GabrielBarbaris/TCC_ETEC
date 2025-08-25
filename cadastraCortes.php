<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/cadastroCortes.css" />
    <title>Cortes</title>
</head>
<body>
    <?php
    include "menuAdm.php";
    ?>
    <div class="container">
        <section class="titulo">
            <h3>Cortes de Carnes</h3>
        </section>
        <form id="form" class="form">
            <div class="form_content">
                <label for="ID">ID</label>
                <input type="text" id="ID" name="ID" placeholder="Digite o ID do corte">
                <a>mensagen de erro</a>
            </div>
            <div class="form_content">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome do corte">
                <a>mensagen de erro</a>
            </div>
            <button type="submit" id="cadastrar">Cadastrar</button>
            <button type="reset" id="cadastrar">limpar</button>
            
        </form>
    </div>
</body>
</html>