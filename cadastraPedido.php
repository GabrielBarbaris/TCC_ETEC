<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastroPedido.css" />
    <title>Pedido</title>
</head>

<body>
    <script src="script.js" defer></script>
    <?php
    include "menuAdm.php";
    ?>

    <div class="container">
        <div class="tela1">
            <h3 class="titulo">Cadastro de Pedido</h3>

            <form id="form" class="form">
            <div class="form_content" >
                <span id="mensagem">Menssagem</span>
            </div>
                <section class="produto">
                    <div class="form_content">
                        <label for="produto"> produto</label>
                        <input type="text" id="produto" name="produto" placeholder="Digite o produto">
                        <a>mensagem de erro</a>
                    </div>
                    <div class="form_content">
                        <label for="quantidade"> quantidade</label>
                        <input type="text" id="quantidade" name="quantidade" placeholder="quantidade">
                        <a>mensagem de erro</a>
                    </div>

                    <div class="form_content">
                        <label for="corte">corte</label>
                        <input type="text" id="corte" name="corte" placeholder="digite o tipo de corte">

                        <a>mensagem de erro</a>
                    </div>
                    <button type="button" id="btnAdicionar">Adicionar</button>
                </section>
                <section class="pedido">

                    <div class="form_content">
                        <label for="horario"> horario de retirada</label>
                        <input type="text" id="horario" name="horario" placeholder="horario">
                        <a>mensagem de erro</a>
                    </div>
                    <div class="form_content">
                        <label for="cliente"> cliente</label>
                        <input type="text" id="cliente" name="cliente" placeholder="cliente">
                        <a>mensagem de erro</a>
                    </div>
                    <div class="form_content">
                        <label for="recebimento">Recebimento</label>
                        <div class="radio">
                            <div class="item_radio">
                            <input type="radio" id="entrega" value="entrega" name="recebimento">
                            <label for="entrega">Entrega</label>
                            </div>
                            <div class="item_radio">
                            <input type="radio" id="retirada" value="retirada" name="recebimento" checked>
                            <label for="retirada">Retirada</label>
                            </div>
                        </div>

                        <div id="endereco" class="hidden">
                            <div class="form_content">
                                <label for="cep">CEP</label>
                                <input type="text" id="cep" name="cep" placeholder="Digite o CEP (apenas números)">
                                <a>mensagem de erro</a>
                            </div>

                            <div class="linha-endereco" style="display:flex; align-items:flex-end; gap:8px;">
                                <div class="form_content" style="max-width: 120px;">
                                    <label for="numero_endereco">Número</label>
                                    <input type="text" id="numero_endereco" name="numero" placeholder="Número">
                                    <a>mensagem de erro</a>
                                </div>
                                <div class="form_content" style="flex: 1 1 auto; min-width:0;">
                                    <label for="campo_endereco">Endereço</label>
                                    <input type="text" id="campo_endereco" name="endereco" placeholder="Rua, bairro, cidade - UF">
                                    <a>mensagem de erro</a>
                                </div>
                            </div>

                            <div class="form_content">
                                <label for="complemento_endereco">Complemento (opcional)</label>
                                <input type="text" id="complemento_endereco" name="complemento" placeholder="Apartamento, bloco, referência">
                            </div>
                        </div>
                        </div>
                </section>


                <button type="submit" id="cadastrar">Cadastrar</button>
            </form>
        </div>
        <aside class="painel-itens" id="painel-itens">
            <div class="painel-itens__header">
                <h4>Itens do pedido</h4>
                <span id="contador-itens" class="badge">0</span>
            </div>
            <ul id="lista-itens" class="lista-itens"></ul>
            <div class="painel-itens__footer" id="painel-itens-footer">Nenhum item adicionado.</div>
        </aside>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="./js/cadastraPedido.js"></script>
</body>

</html>