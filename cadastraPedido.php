<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastroPedido.css" />
    <title>Pedido</title>
</head>
<body>
    
    <?php
        include "menuAdm.php";
    ?>
    
    <div class="container">
        <h3 class="titulo">Cadastro de Pedido</h3>

        <form id="form" class="form">

            <div class="form_content">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome">
                <a>mensagen de erro</a>
            </div>
            <div class="form_content">
                <label for="sobrenome">Sobrenome</label>
                <input type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome">
                <a>mensagen de erro</a>
            </div>
            <div class="form_content">
                <label for="Telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" placeholder="Digite seu Telefone" >
                <a>mensagen de erro</a>
            </div>
            <div class="form_content ">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha">
                <a>mensagen de erro</a>
            </div>
            <div class="form_content">
                <label for="senha_confirmacao">Confirmacao de Senha</label>
                <input type="password" id="senha_confirmacao"  placeholder="Digite sua senha">
                <a>mensagen de erro</a>
            </div>
            <a href="./login.php">Ja tenho conta</a>
            <button type="submit" id="cadastrar">Cadastrar</button>
        </form>
    </div>
</body>
</html>