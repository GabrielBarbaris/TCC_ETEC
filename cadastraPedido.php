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
            <section class="produto">
                <div class="form_content">
                    <label for="nome"> produto</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome">
                    <a>mensagen de erro</a>
                </div>
                <div class="form_content">
                    <label for="nome"> quantidade</label>
                    <input type="text" id="nome" name="nome" placeholder="quantidade">
                    <a>mensagen de erro</a>
                </div>

                <div class="form_content">
                    <label for="sobrenome">corte</label>
                    <input type="text" id="azul" name="sobrenome" placeholder="Digite seu sobrenome">
                    
                    <a>mensagen de erro</a>
                </div>
                <button>Adicionar</button>
            </section>
            <section class="pedido">
                <div class="form_content">
                    <div class="radio" >
                        <div class="item_radio">
                        <input type="radio" id="azul" value="azul" >
                        <label for="azul">azul</label>
                        </div>
                        <div class="item_radio">
                        <input type="radio" id="azul" value="azul" >
                        <label for="azul">azul</label>
                        </div>
                    </div>
                </div>      
            </section>


            <button type="submit" id="cadastrar">Cadastrar</button>
        </form>
    </div>
</body>

</html>