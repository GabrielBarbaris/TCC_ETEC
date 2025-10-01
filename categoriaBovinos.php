<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/globals.css" />
    <link rel="stylesheet" href="css/index.css" />
    <link rel="stylesheet" href="css/telaProduto.css" />
    <title>Categoria: Bovinos</title>
</head>
<body>
    <?php include 'menuAdm.php'; ?>

    <div class="corpo">
        <div class="corpo_1">
            <section class="churrasco_qualidade">
                <div class="separao">
                    <p class="p"><span class="text-wrapper">categoria </span> <span class="span">bovinos</span></p>
                </div>

                <?php
                require 'conexao.php';
                // Busca produtos cuja categoria seja "Bovinos"
                $sql = "SELECT p.*\n                        FROM tbProduto p\n                        INNER JOIN tbCategoria c ON c.id_categoria = p.cod_categoria\n                        WHERE c.nome_categoria = 'Bovinos'\n                        ORDER BY p.nome_produto";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($produto = mysqli_fetch_assoc($result)) {
                        $id   = (int)$produto['id_produto'];
                        $nome = htmlspecialchars($produto['nome_produto'], ENT_QUOTES);
                        $descricao = htmlspecialchars((string)$produto['descricao'], ENT_QUOTES);
                        $preco = number_format((float)$produto['preco'], 2, ',', '.');
                        $url  = htmlspecialchars((string)$produto['imagem_url'], ENT_QUOTES);

                        echo "<div class='picanha'>
                                <div class='overlap-4'>
                                  <img class='img-3' src='$url' alt='$nome' />
                                  <div class='rectangle-5'></div>
                                  <div class='text-wrapper-17'>$nome</div>
                                  <p class='text-wrapper-7'>$descricao</p>
                                  <p class='r-KG'><span class='text-wrapper'>R$$preco </span> <span class='text-wrapper-8'>KG</span></p>
                                  <div class='boto btn-add-prod' data-prod-id='$id' tabindex='0' role='button'>
                                    <div class='overlap-group-2'>
                                      <div class='rectangle-3'></div>
                                      <div class='text-wrapper-9'>ADICIONAR</div>
                                    </div>
                                  </div>
                                </div>
                              </div>";
                    }
                } else {
                    echo '<p>Não existem produtos cadastrados na categoria Bovinos.</p>';
                }
                ?>
            </section>
        </div>
    </div>

    <script src="./js/index.js"></script>
</body>
</html>