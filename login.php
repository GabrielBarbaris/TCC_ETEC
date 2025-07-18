<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login/login.css" />
    <title>login</title>
</head>
<body>
    <div class="container">
        <section class="header">
            <h2>Login</h2>
        </section>
        
        <form id="form" class="form">
            <div class="form_content">
                <span id="mensagem">Menssagem</span>
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
            
            <a href="./cadastra.php">Cadastrar</a>
            <button type="submit" id="cadastrar">Logar</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="./js/login/login.js"></script>
    
</body>
</html>