<?php
session_start();
$clienteLogado = isset($_SESSION['id_cliente']) || isset($_SESSION['cliente_id']) || isset($_SESSION['id_usuario']) || isset($_SESSION['usuario']) || isset($_SESSION['cliente']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/index.css" />
  <link rel="stylesheet" href="css/telaProduto.css" />
  <link rel="stylesheet" href="css/categorias.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
  <title>Casa de Carnes - Categorias</title>
</head>

<body>
  <div class="tela-inicial">
    <div class="corpo">
      <div class="corpo_1">
        <section class="churrasco_qualidade">
          <div class="separao">
            <?php
            require 'conexao.php';

            // Parametro de busca (texto livre)
            $busca = '';
            if (isset($_GET['busca'])) { $busca = trim((string)$_GET['busca']); }
            elseif (isset($_GET['q'])) { $busca = trim((string)$_GET['q']); }
            $modoBusca = ($busca !== '');

            // Determina a categoria pelo código (preferencial) ou nome
            $catId = 0;
            if (isset($_GET['id'])) { $catId = (int)$_GET['id']; }
            elseif (isset($_GET['cat'])) { $catId = (int)$_GET['cat']; }

            $catNomeParam = '';
            if (isset($_GET['nome'])) { $catNomeParam = trim((string)$_GET['nome']); }
            elseif (isset($_GET['categoria'])) { $catNomeParam = trim((string)$_GET['categoria']); }
            elseif (isset($_GET['c'])) { $catNomeParam = trim((string)$_GET['c']); }

            $categoriaTitulo = 'categoria';
            $categoriaValida = false;

            if ($catId > 0) {
              if ($stmt = $conn->prepare('SELECT nome_categoria FROM tbCategoria WHERE id_categoria = ? LIMIT 1')) {
                $stmt->bind_param('i', $catId);
                if ($stmt->execute()) {
                  $res = $stmt->get_result();
                  if ($row = $res->fetch_assoc()) {
                    $categoriaTitulo = 'categoria ' . htmlspecialchars((string)$row['nome_categoria'], ENT_QUOTES);
                    $categoriaValida = true;
                  }
                }
                $stmt->close();
              }
            } elseif ($catNomeParam !== '') {
              if ($stmt = $conn->prepare('SELECT id_categoria, nome_categoria FROM tbCategoria WHERE UPPER(nome_categoria) = UPPER(?) LIMIT 1')) {
                $stmt->bind_param('s', $catNomeParam);
                if ($stmt->execute()) {
                  $res = $stmt->get_result();
                  if ($row = $res->fetch_assoc()) {
                    $catId = (int)$row['id_categoria'];
                    $categoriaTitulo = 'categoria ' . htmlspecialchars((string)$row['nome_categoria'], ENT_QUOTES);
                    $categoriaValida = true;
                  }
                }
                $stmt->close();
              }
            }
            ?>
            <p class="p"><span class="text-wrapper"><?php echo ($modoBusca ? ('resultados para ' . htmlspecialchars($busca, ENT_QUOTES)) : ($categoriaTitulo ? $categoriaTitulo : 'categoria')); ?></span></p>
          </div>

          <?php
          if ($modoBusca) {
            // Busca por texto nos produtos (nome ou descrição)
            if ($stmt = $conn->prepare('SELECT p.* FROM tbProduto p WHERE p.nome_produto LIKE ? OR p.descricao LIKE ? ORDER BY p.nome_produto')) {
              $like = '%' . $busca . '%';
              $stmt->bind_param('ss', $like, $like);
              if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                  while ($produto = $result->fetch_assoc()) {
                    $id = (int)$produto['id_produto'];
                    $nome = htmlspecialchars((string)$produto['nome_produto'], ENT_QUOTES);
                    $descricao = htmlspecialchars((string)$produto['descricao'], ENT_QUOTES);
                    $preco = number_format((float)$produto['preco'], 2, ',', '.');
                    $url = htmlspecialchars((string)$produto['imagem_url'], ENT_QUOTES);

                    echo "<div class='picanha'>
                            <div class='overlap-4'>
                              <img class='img-3' src='$url' alt='$nome' />
                              <div class='rectangle-5'></div>
                              <div class='text-wrapper-17'>$nome</div>
                              <p class='text-wrapper-7'>$descricao</p>
                              <p class='r-KG'><span class='text-wrapper'>R$preco </span> <span class='text-wrapper-8'>KG</span></p>
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
                  echo '<p>Nenhum produto encontrado para sua busca.</p>';
                }
              } else {
                echo '<p>Falha ao buscar produtos.</p>';
              }
              $stmt->close();
            } else {
              echo '<p>Falha ao preparar consulta de busca.</p>';
            }
          } elseif ($categoriaValida && $catId > 0) {
            // Lista produtos da categoria informada
            if ($stmt = $conn->prepare('SELECT p.* FROM tbProduto p WHERE p.cod_categoria = ? ORDER BY p.nome_produto')) {
              $stmt->bind_param('i', $catId);
              if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                  while ($produto = $result->fetch_assoc()) {
                    $id = (int)$produto['id_produto'];
                    $nome = htmlspecialchars((string)$produto['nome_produto'], ENT_QUOTES);
                    $descricao = htmlspecialchars((string)$produto['descricao'], ENT_QUOTES);
                    $preco = number_format((float)$produto['preco'], 2, ',', '.');
                    $url = htmlspecialchars((string)$produto['imagem_url'], ENT_QUOTES);

                    echo "<div class='picanha'>
                            <div class='overlap-4'>
                              <img class='img-3' src='$url' alt='$nome' />
                              <div class='rectangle-5'></div>
                              <div class='text-wrapper-17'>$nome</div>
                              <p class='text-wrapper-7'>$descricao</p>
                              <p class='r-KG'><span class='text-wrapper'>R$preco </span> <span class='text-wrapper-8'>KG</span></p>
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
                  echo '<p>Não existem produtos cadastrados nesta categoria.</p>';
                }
              } else {
                echo '<p>Falha ao buscar produtos da categoria.</p>';
              }
              $stmt->close();
            } else {
              echo '<p>Falha ao preparar consulta de produtos.</p>';
            }
          } else {
            echo '<p>Categoria não informada ou inválida. Utilize categorias.php?id={codigo}.</p>';
          }
          ?>
        </section>
      </div>

      <!-- coluna direita (sacola) opcional -->
      <div class="corpo_2">
        <div class="pedido">

          <div class="pedido-2"></div>
          <img class="line-6" src="img/Line6.png" />

          <div class="boto-finalizar">
            <div class="div-wrapper" id="btnFinalizarPedido" role="button" tabindex="0" aria-disabled="true" style="cursor:pointer;">
              <div class="text-wrapper-27">FINALIZAR PEDIDO</div>
            </div>
          </div>
          <div class="text-wrapper-28">Sua sacola</div>
          <div class="text-wrapper-29" id="btnLimparCarrinho">Limpar</div>
          <div class="text-wrapper-30" id="btnTempoEntrega" role="button" style="cursor:pointer;">Forma de receber</div>
          <div id="enderecoResumo" style="position:absolute; top:48px; left:60px; width:270px; font-family: 'Calibri-Regular', Helvetica; font-size:14px; color:#5f5f5f;"></div>
          <img class="line-7" src="img/Line7.png" />
          <img class="vector" src="img/localizacao.png" />
          <div id="carrinhoLista"></div>
          <div class="text-wrapper-42">Subtotal:</div>
          <div class="text-wrapper-43" id="subtotalValor">R$0,00</div>
          <div class="text-wrapper-44" id="freteValor">R$0,00</div>
          <div class="text-wrapper-45">Frete:</div>
          <p class="total"><span class="text-wrapper-46">Total</span> <span class="text-wrapper-47">:</span></p>
          <div class="text-wrapper-48" id="totalValor">R$0,00</div>
        </div>
      </div>
    </div>

    <!-- =============================
         RODAPÉ
         - Copiado de index.php para manter a consistência.
         ============================= -->
    <footer class="rodape">
      <div class="interna_rodape">
        <div class="informacoes">
          <img class="logo" src="img/logo.png" alt="Logo" />
          <p class="text-wrapper-19">
            Aqui você encontra qualidade, atendimento e agilidade para sua melhor satisfação quando for comprar um
            alimento essencial na sua casa, por isso tenha nossa casa de carnes como referência.
          </p>
        </div>

        <img class="line-4" src="img/Line5.png" alt="linha" />

        <div class="entre-em-contato">
          <div class="text-wrapper-20">entre em contato</div>
          <div class="overlap-6">
            <div class="text-wrapper-21">Telefone :</div>
            <div class="text-wrapper-22">WhatsApp:</div>
          </div>
          <p class="text-wrapper-23">Rua Alvorada, 123 Selina Dalu - Mirassol - SP</p>
          <div class="overlap-7">
            <div class="text-wrapper-24">(17) 99201-8283</div>
            <div class="text-wrapper-25">(17) 99201-8283</div>
          </div>
          <div class="text-wrapper-26">Endereço:</div>
        </div>

        <img class="line-5" src="img/Line5.png" alt="linha" />

        <div class="horario-de">
          <div class="text-wrapper-50">horario de funcionamento</div>
          <div class="overlap-10">
            <div class="text-wrapper-51">Segunda - Sexta :</div>
            <div class="text-wrapper-52">Sábado:</div>
            <div class="text-wrapper-53">8:00 - 19:40</div>
            <div class="text-wrapper-54">7:00 - 19:40</div>
            <div class="text-wrapper-55">Domingo:</div>
            <div class="text-wrapper-56">7:00 - 12:40</div>
          </div>
        </div>
      </div>
    </footer>

    <!-- HEADER replicado -->
    <header class="HEADER">
      <div class="overlap-17">
        <div class="CATEGORIAS">
          <div class="CHURRASCO">
            <div class="overlap-group-3" onclick="window.location.href='categorias.php?nome=Churrasco'" role="button" style="cursor:pointer;">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-57">CHURRASCO</div>
              <img class="weber" src="img/churrasco.png" alt="">
            </div>
          </div>

          <div class="KITS">
            <div class="overlap-11" onclick="window.location.href='categorias.php?nome=Kits'" role="button" style="cursor:pointer;">
              <div class="text-wrapper-58">KITS</div>
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <img class="shopping-basket" src="img/kits.png" alt="">
            </div>
          </div>

          <div class="AVES">
            <div class="overlap-12" onclick="window.location.href='categorias.php?nome=Aves'" role="button" style="cursor:pointer;">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-59">AVES</div>
              <img class="poultry-leg" src="img/aves.png" alt="">
            </div>
          </div>

          <div class="EMBUTIDOS">
            <div class="overlap-13" onclick="window.location.href='categorias.php?nome=Embutidos'" role="button" style="cursor:pointer;">
              <div class="text-wrapper-60">EMBUTIDOS</div>
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <img class="salami" src="img/embutido.png" alt="">
            </div>
          </div>

          <div class="SUNOS">
            <div class="overlap-14" onclick="window.location.href='categorias.php?nome=Suinos'" role="button" style="cursor:pointer;">
              <img class="rectangle-8" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-61">SUiNOS</div>
              <img class="bacon" src="img/suino.png" alt="">
            </div>
          </div>

          <div class="LINGUIAS">
            <div class="overlap-15" onclick="window.location.href='categorias.php?nome=Linguicas'" role="button" style="cursor:pointer;">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-62">LINGUICAS</div>
              <img class="vector-2" src="img/linguica.png" alt="">
            </div>
          </div>

          <div class="BOVINOS">
            <div class="overlap-16" onclick="window.location.href='categorias.php?nome=Bovinos'" role="button" style="cursor:pointer;">
              <img class="rectangle-9" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-63">BOVINOS</div>
              <img class="barbecue" src="img/bovinos.png" alt="">
            </div>
          </div>
        </div>

        <div class="rectangle-10"></div>
        <div class="rectangle-11"></div>

        <div class="barra_tarefas">
          <div class="search">
            <label for="searchInput"><span class="material-symbols-outlined"> Search</span></label>
            <input type="text" id="searchInput" placeholder="pesquisar">
          </div>

          <button id="btnOpenLogin" title="Entrar" type="button" style="background:none; border:none; cursor:pointer;">
            <img class="user-user" src="img/login.png" alt="login" />
          </button>

          <button title="Ver sacola" type="button">
            <img class="basket" src="img/pedido.png" alt="pedido" />
          </button>
        </div>

        <img class="logo-2" src="img/logo.png" alt="Logo" />
        <a href="index.php"><img class="logo-2" src="img/logo.png" alt="Logo" /></a>
      </div>
    </header>
  </div>

 

  <script src="./js/index.js"></script>

  <!-- Modal de Produto (conteúdo inline, sem iframe) -->
  <dialog id="produtoDialog" style="position:fixed; inset:0; margin:auto; width:min(960px, 95vw); max-width:95vw; max-height:90vh; border:none; padding:0; border-radius:12px; overflow:auto;">
    <button id="closeProdutoDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <header class="nome_produto">
      <h1 id="prodNome">Produto</h1>
    </header>
    <div id="produtoView" hidden>
      <img id="prodImagem" class="produto-img" src="" alt="Produto" />
      <section class="produto-right">
        <div class="descricao">
          <div id="precoKG">R$0,00 / Kg</div>
          <p id="prodDesc"></p>
        </div>
        <div class="opcoes">
          <fieldset class="section">
            <legend class="legend">Como será cortada a carne?</legend>
            <div class="muted" style="display:flex; gap:8px; align-items:center;">
              <span>Escolha 1 opção</span>
              <span id="corteCount">0/1</span>
            </div>
            <div id="cortes" class="radio-group" role="radiogroup" aria-label="Opções de corte"></div>
          </fieldset>

          <section class="section obs-box">
            <div class="legend">Observação</div>
            <textarea id="obsText" maxlength="150" placeholder="Ex.: separar em 2 pacotes, ponto da carne, etc."></textarea>
            <div class="obs-footer">
              <span></span>
              <span id="obsCount">0/150</span>
            </div>
          </section>
        </div>
        <div class="footer">
          <div class="qty" aria-label="Controle de quantidade">
            <button id="btnMenos" type="button" title="Diminuir">-</button>
            <div id="pesoAtual" class="qtd-display" aria-live="polite">0</div>
            <button id="btnMais" type="button" title="Aumentar">+</button>
          </div>
          <button id="btnAdicionar" type="button" class="btn-primary">
            <span class="label">Adicionar</span>
            <strong id="precoAtual">R$0,00</strong>
          </button>
        </div>
      </section>
    </div>
  </dialog>

  <!-- Dialog Entrega/Retirada -->
  <dialog id="entregaDialog" style="position:fixed; inset:0; margin:auto; width:min(520px,95vw); max-height:90vh; border:none; padding:0; border-radius:12px; overflow:auto;">
    <div style="position:sticky; top:0; background:#800000; color:#fff; padding:10px 16px; display:flex; justify-content:space-between; align-items:center;">
      <strong>Forma de receber</strong>
      <button type="button" id="closeEntregaDialog" style="background:#5f0d0d; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    </div>
    <div class="dlg-body" style="padding:16px;">
      <div class="radio-group" style="display:flex; gap:12px; margin-bottom:12px;">
        <label class="radio-item" style="display:flex; align-items:center; gap:6px;">
          <input type="radio" name="tipoEnvio" value="ENTREGA" checked>
          <span>Entrega</span>
        </label>
        <label class="radio-item" style="display:flex; align-items:center; gap:6px;">
          <input type="radio" name="tipoEnvio" value="RETIRADA">
          <span>Retirada</span>
        </label>
      </div>

      <div id="enderecoForm" style="display:block;">
        <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
          <label for="cepInput">CEP</label>
          <input id="cepInput" type="text" placeholder="00000-000" inputmode="numeric">
        </div>
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:12px;">
          <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
            <label for="ruaInput">Endereço</label>
            <input id="ruaInput" type="text" placeholder="Rua / Avenida">
          </div>
          <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
            <label for="numeroInput">Número</label>
            <input id="numeroInput" type="text" placeholder="Nº">
          </div>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
          <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
            <label for="bairroInput">Bairro</label>
            <input id="bairroInput" type="text" placeholder="Bairro">
          </div>
          <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
            <label for="cidadeInput">Cidade</label>
            <input id="cidadeInput" type="text" placeholder="Cidade">
          </div>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
          <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
            <label for="ufInput">UF</label>
            <input id="ufInput" type="text" placeholder="UF" maxlength="2" style="text-transform:uppercase;">
          </div>
          <div class="form_content" style="display:flex; flex-direction:column; margin-bottom:12px;">
            <label for="compInput">Complemento</label>
            <input id="compInput" type="text" placeholder="Bloco / Referência (opcional)">
          </div>
        </div>
      </div>
    </div>
    <div class="footer" style="display:flex; justify-content:flex-end; gap:8px; padding:12px 16px; border-top:1px solid #eee;">
      <button type="button" id="cancelEntrega" style="background:#999; color:#fff; border:none; border-radius:6px; padding:8px 12px; cursor:pointer;">Cancelar</button>
      <button type="button" id="saveEntrega" style="background:#8d1010; color:#fff; border:none; border-radius:6px; padding:8px 12px; cursor:pointer;">Confirmar</button>
    </div>
  </dialog>

  <!-- Dialog Confirmação do Pedido -->
  <dialog id="confirmPedidoDialog" style="position:fixed; inset:0; margin:auto; width:min(720px,95vw); max-height:92vh; border:none; padding:0; border-radius:12px; overflow:auto;">
    <div style="position:sticky; top:0; background:#800000; color:#fff; padding:10px 16px; display:flex; justify-content:space-between; align-items:center;">
      <strong>Confirmar Pedido</strong>
      <button type="button" id="closeConfirmPedido" style="background:#5f0d0d; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    </div>
    <div style="padding:16px; display:flex; flex-direction:column; gap:12px;">
      <section style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div style="display:flex; flex-direction:column;">
          <label for="confNome">Cliente</label>
          <input id="confNome" type="text" readonly placeholder="Nome do cliente">
        </div>
        <div style="display:flex; flex-direction:column;">
          <label for="confTelefone">Telefone</label>
          <input id="confTelefone" type="text" readonly placeholder="(00) 00000-0000">
        </div>
      </section>

      <div style="display:flex; gap:16px; align-items:center;">
        <label style="display:flex; align-items:center; gap:6px;"><input type="radio" name="confTipoEnvio" value="ENTREGA" checked> Entrega</label>
        <label style="display:flex; align-items:center; gap:6px;"><input type="radio" name="confTipoEnvio" value="RETIRADA"> Retirada</label>
      </div>

      <div id="confEnderecoWrap" style="display:block;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div style="display:flex; flex-direction:column;">
            <label for="confCEP">CEP</label>
            <input id="confCEP" type="text" placeholder="00000-000" inputmode="numeric">
          </div>
          <div style="display:flex; flex-direction:column;">
            <label for="confNumero">Número</label>
            <input id="confNumero" type="text" placeholder="Nº">
          </div>
        </div>
        <div style="display:grid; grid-template-columns:2fr 1fr; gap:12px;">
          <div style="display:flex; flex-direction:column;">
            <label for="confRua">Endereço</label>
            <input id="confRua" type="text" placeholder="Rua / Avenida">
          </div>
          <div style="display:flex; flex-direction:column;">
            <label for="confBairro">Bairro</label>
            <input id="confBairro" type="text" placeholder="Bairro">
          </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
          <div style="display:flex; flex-direction:column;">
            <label for="confCidade">Cidade</label>
            <input id="confCidade" type="text" placeholder="Cidade">
          </div>
          <div style="display:flex; flex-direction:column;">
            <label for="confUF">UF</label>
            <input id="confUF" type="text" placeholder="UF" maxlength="2" style="text-transform:uppercase;">
          </div>
          <div style="display:flex; flex-direction:column;">
            <label for="confComp">Complemento</label>
            <input id="confComp" type="text" placeholder="Bloco / Referência (opcional)">
          </div>
        </div>
      </div>

      <div id="confRetiradaWrap" style="display:block;">
        <div style="display:flex; flex-direction:column; max-width:200px;">
          <label for="confHorario">Horário desejado</label>
          <input id="confHorario" type="time" required>
        </div>
      </div>

      <div style="border-top:1px solid #eee; padding-top:8px;">
        <div style="margin-bottom:8px; font-weight:600;">Itens do pedido</div>
        <div id="confItensLista" style="max-height:220px; overflow:auto; display:flex; flex-direction:column; gap:6px;"></div>
        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:8px;">
          <span style="font-weight:600;">Total:</span>
          <span id="confTotalValor" style="font-weight:700;">R$0,00</span>
        </div>
      </div>
    </div>
    <div style="display:flex; justify-content:flex-end; gap:8px; padding:12px 16px; border-top:1px solid #eee;">
      <button type="button" id="cancelConfirmPedido" style="background:#999; color:#fff; border:none; border-radius:6px; padding:8px 12px; cursor:pointer;">Cancelar</button>
      <button type="button" id="saveConfirmPedido" style="background:#8d1010; color:#fff; border:none; border-radius:6px; padding:8px 12px; cursor:pointer;">Confirmar Pedido</button>
    </div>
  </dialog>

  <!-- Dialog Login -->
  <dialog id="loginDialog" style="position:fixed; inset:0; margin:auto; width:min(400px,67vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
    <button id="closeLoginDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <div class="container">
      <section class="header">
        <h2>Login</h2>
      </section>
      <form id="form" class="form">
        <div class="form_content">
          <span id="mensagem">Menssagem</span>
        </div>
        <div class="form_content">
          <label for="telefone">Telefone</label>
          <input type="text" id="telefone" name="telefone" placeholder="Digite seu Telefone">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Digite sua senha">
          <a>mensagen de erro</a>
        </div>
        <a href="#" id="openCadastroLink">Cadastrar</a>
        <button type="submit" id="cadastrar">Logar</button>
      </form>
    </div>
  </dialog>

  <!-- Dialog Cadastro -->
  <dialog id="cadastroDialog" style="position:fixed; inset:0; margin:auto; width:min(400px,67vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
    <button id="closeCadastroDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <div class="container">
      <section class="header">
        <h2>Nova Conta</h2>
      </section>
      <form id="cadForm" class="form">
        <div class="form_content">
          <span id="cadMensagem">Menssagem</span>
        </div>
        <div class="form_content">
          <label for="cadNome">Nome</label>
          <input type="text" id="cadNome" name="nome" placeholder="Digite seu nome">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadSobrenome">Sobrenome</label>
          <input type="text" id="cadSobrenome" name="sobrenome" placeholder="Digite seu sobrenome">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadTelefone">Telefone</label>
          <input type="text" id="cadTelefone" name="telefone" placeholder="Digite seu Telefone">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadSenha">Senha</label>
          <input type="password" id="cadSenha" name="senha" placeholder="Digite sua senha">
          <a>mensagen de erro</a>
        </div>
        <div class="form_content">
          <label for="cadSenhaConf">Confirmacao de Senha</label>
          <input type="password" id="cadSenhaConf" placeholder="Digite sua senha">
          <a>mensagen de erro</a>
        </div>
        <a href="#" id="openLoginFromCadastro">Já tenho conta</a>
        <button type="submit" id="btnCadastrar">Cadastrar</button>
      </form>
    </div>
  </dialog>

  <!-- Dialog Perfil (Informações do Usuário) -->
  <dialog id="perfilDialog" style="position:fixed; inset:0; margin:auto; width:min(760px, 90vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
    <button id="closePerfilDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
    <div class="container" style="min-height:auto; background:#F8F8F8;">
      <section class="header" style="background: linear-gradient(120deg, #600E0E, #440D0D); padding: 16px; text-align:center; color:#fff;">
        <h2 style="margin:0; font-family: Baloo;">Minha conta</h2>
      </section>
      <form id="perfilForm" class="form" style="max-width:680px; margin:24px auto; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:20px;">
        <div class="form_content">
          <label for="perfilNome">Nome</label>
          <input type="text" id="perfilNome" readonly>
          <a></a>
        </div>
        <div class="form_content">
          <label for="perfilSobrenome">Sobrenome</label>
          <input type="text" id="perfilSobrenome" readonly>
          <a></a>
        </div>
        <div class="form_content">
          <label for="perfilTelefone">Telefone</label>
          <input type="text" id="perfilTelefone" readonly>
          <a></a>
        </div>
        <div class="form_content" style="grid-column: span 2; display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" id="btnEditarPerfil" style="background:#5c4444; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer;">Editar</button>
          <button type="submit" id="btnSalvarPerfil" style="background:#006b1b; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer; display:none;">Salvar</button>
          <button type="button" id="btnCancelarPerfil" style="background:#9c2a2a; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer; display:none;">Cancelar</button>
          <button type="button" id="btnLogout" style="background:#800000; color:#fff; border:none; border-radius:10px; padding:10px 16px; font-family: Baloo; font-size:16px; cursor:pointer;">Sair da conta</button>
        </div>
      </form>
    </div>
  </dialog>

  <style>
    /* Estilos do modal de Login (baseado em css/login.css, escopado) */
    #loginDialog::backdrop {
      background: rgba(0, 0, 0, .45);
    }

    #loginDialog .container {
      background-color: #efefef;
      border-radius: 16px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
      overflow: hidden;
    }

    #loginDialog .header {
      background: linear-gradient(120deg, #600E0E, #440D0D);
      padding: 20px;
      font-family: "Shaimus Clean-Regular";
      text-align: center;
      color: #ffffff;
      font-size: 30px;
    }

    #loginDialog .form {
      padding: 18px;
    }

    #loginDialog .form_content {
      margin-bottom: 8px;
      padding-bottom: 18px;
      position: relative;
      font-family: "Calibre";
      color: #807a75;
    }

    #loginDialog .form_content label {
      display: inline-block;
      margin-bottom: 0px;
    }

    #loginDialog .form_content input {
      display: block;
      width: 100%;
      border-radius: 3px;
      padding: 10px;
      border: 2px solid #dfdfdf;
    }

    #loginDialog .form_content a {
      position: absolute;
      bottom: 0px;
      left: 0;
      visibility: hidden;
    }

    #loginDialog .form button {
      background-color: #600E0E;
      color: #ffffff;
      width: 100%;
      border: 0;
      border-radius: 10px;
      padding: 8px;
      font-family: Baloo;
      font-size: 16px;
      cursor: pointer;
      margin-top: 14px;
    }

    #loginDialog .form_content.error input {
      border-color: #fc5e5e;
    }

    #loginDialog .form_content span {
      display: block;
      text-align: center;
      padding: 10px;
      color: #ffffff;
      border: 3px solid rgba(243, 4, 4, 0.156);
      background-color: rgba(105, 23, 23, 0.593);
      border-radius: 13px;
    }

    #loginDialog .form_content.error a {
      color: #fc5e5e;
      visibility: visible;
    }

    /* Estilos do modal de Cadastro (mesma base do login.css, escopado) */
    #cadastroDialog::backdrop {
      background: rgba(0, 0, 0, .45);
    }

    #cadastroDialog .container {
      background-color: #efefef;
      border-radius: 16px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
      overflow: hidden;
    }

    #cadastroDialog .header {
      background: linear-gradient(120deg, #600E0E, #440D0D);
      padding: 20px;
      font-family: "Shaimus Clean-Regular";
      text-align: center;
      color: #ffffff;
      font-size: 30px;
    }

    #cadastroDialog .form {
      padding: 18px;
    }

    #cadastroDialog .form_content {
      margin-bottom: 8px;
      padding-bottom: 18px;
      position: relative;
      font-family: "Calibre";
      color: #807a75;
    }

    #cadastroDialog .form_content label {
      display: inline-block;
      margin-bottom: 0px;
    }

    #cadastroDialog .form_content input {
      display: block;
      width: 100%;
      border-radius: 3px;
      padding: 10px;
      border: 2px solid #dfdfdf;
    }

    #cadastroDialog .form_content a {
      position: absolute;
      bottom: 0px;
      left: 0;
      visibility: hidden;
    }

    #cadastroDialog .form button {
      background-color: #600E0E;
      color: #ffffff;
      width: 100%;
      border: 0;
      border-radius: 10px;
      padding: 8px;
      font-family: Baloo;
      font-size: 16px;
      cursor: pointer;
      margin-top: 14px;
    }

    #cadastroDialog .form_content.error input {
      border-color: #fc5e5e;
    }

    #cadastroDialog .form_content span {
      display: block;
      text-align: center;
      padding: 10px;
      color: #ffffff;
      border: 3px solid rgba(243, 4, 4, 0.156);
      background-color: rgba(105, 23, 23, 0.593);
      border-radius: 13px;
    }

    #cadastroDialog .form_content.error a {
      color: #fc5e5e;
      visibility: visible;
    }

    /* Modal Perfil (estética baseada em cadastroProduto.css) */
    #perfilDialog::backdrop {
      background: rgba(0, 0, 0, .45);
    }

    #perfilDialog .form {
      width: 100%;
      max-width: 680px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 20px;
    }

    #perfilDialog .form_content {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    #perfilDialog label {
      font-family: Arial, Helvetica, sans-serif;
      color: #00000097;
    }

    #perfilDialog input {
      width: 100%;
      padding: 14px;
      border-radius: 8px;
      border: 1px solid #dddddd;
      background: #fff;
      color: #222;
      font-size: 14px;
      outline: none;
    }

    #perfilDialog input[readonly] {
      background: #fafafa;
      color: #333;
    }

    #perfilDialog .error input {
      border: 1px solid #fc5e5e;
      box-shadow: 0 0 0 3px rgba(252, 94, 94, .12);
    }

    /* Centraliza o dialog e escurece o fundo */
    #produtoDialog::backdrop {
      background: rgba(0, 0, 0, .45);
    }

    /* Responsividade do modal */
    @media (max-width: 900px) {
      #produtoDialog {
        width: 100vw !important;
        max-width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        border-radius: 0 !important;
      }
    }

    @media (max-width: 480px) {
      #produtoView {
        padding: 16px;
      }

      #prodNome {
        font-size: 22px;
      }

      #closeProdutoDialog {
        top: 8px;
        right: 8px;
        padding: 6px 10px;
      }
    }
  </style>

  <script>
    (function() {
      const dlg = document.getElementById('produtoDialog');
      const closeBtn = document.getElementById('closeProdutoDialog');
      const view = document.getElementById('produtoView');

      // Elementos do conteúdo
      const img = document.getElementById('prodImagem');
      const nome = document.getElementById('prodNome');
      const precoKG = document.getElementById('precoKG');
      const precoAtual = document.getElementById('precoAtual');
      const pesoAtual = document.getElementById('pesoAtual');
      const cortesWrap = document.getElementById('cortes');
      const obs = document.getElementById('obsText');
      const obsCount = document.getElementById('obsCount');
      const corteCount = document.getElementById('corteCount');
      const btnMais = document.getElementById('btnMais');
      const btnMenos = document.getElementById('btnMenos');
      const btnAdicionar = document.getElementById('btnAdicionar');
      const qtdResumo = document.getElementById('qtdResumo');

      let produto = null;
      let corteSelecionado = null;
      let quantidade = 0;
      let cortesLista = [];
      let editIndex = null;
      let editPayload = null;

      function formatBRL(v) {
        return v.toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        });
      }

      function renderPesoQtd() {
        if (!produto) return;
        const txt = produto.tipo_quantidade === 'PESO' ? (quantidade.toFixed(2).replace('.', ',')) + ' Kg' : (quantidade + ' un');
        pesoAtual.textContent = txt;
        if (qtdResumo) qtdResumo.textContent = txt;
      }

      function renderPreco() {
        if (!produto) return;
        const p = quantidade * produto.preco;
        precoAtual.textContent = formatBRL(p);
        precoKG.textContent = formatBRL(produto.preco) + (produto.tipo_quantidade === 'PESO' ? ' / Kg' : ' / Un');
      }

      function setQtd(q) {
        if (!produto) return;
        const min = produto.tipo_quantidade === 'PESO' ? produto.peso_minimo : 1;
        const step = produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1;
        q = Math.max(min, Math.round(q / step) * step);
        quantidade = parseFloat(q.toFixed(2));
        renderPesoQtd();
        renderPreco();
      }

      function criaRadioCorte(item) {
        const label = document.createElement('label');
        label.className = 'radio-item';
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = 'corte';
        input.value = String(item.id);
        input.addEventListener('change', () => {
          if (input.checked) {
            corteSelecionado = item.id;
            corteCount.textContent = '1/1';
          }
        });
        const span = document.createElement('span');
        span.textContent = item.nome;
        label.appendChild(input);
        label.appendChild(span);
        return label;
      }

      function resetProdutoUI() {
        produto = null;
        corteSelecionado = null;
        quantidade = 0;
        editIndex = null;
        editPayload = null;
        nome.textContent = 'Produto';
        precoKG.textContent = 'R$0,00';
        precoAtual.textContent = 'R$0,00';
        pesoAtual.textContent = '0';
        if (qtdResumo) qtdResumo.textContent = '';
        img.src = '';
        document.getElementById('prodDesc').textContent = '';
        cortesWrap.innerHTML = '';
        corteCount.textContent = '0/1';
        obs.value = '';
        obsCount.textContent = '0/150';
        view.hidden = true;
      }

      function openProduto(id, payload) {
        resetProdutoUI();
        // Modo edição (opcional)
        if (payload && typeof payload === 'object') {
          editPayload = payload;
          if (typeof payload.editIndex === 'number') editIndex = payload.editIndex;
        }
        if (typeof dlg.showModal === 'function') dlg.showModal();
        else dlg.setAttribute('open', 'open');

        // Se vier payload de produto/cortes, evita buscar no banco
        if (payload && payload.produto) {
          produto = payload.produto;
          nome.textContent = produto.nome || 'Produto';
          img.src = produto.imagem_url || 'img/imagensIlustrativa.jpg';
          document.getElementById('prodDesc').textContent = (produto.descricao || '').trim();
          cortesWrap.innerHTML = '';
          cortesLista = payload.cortes || [];
          const lista = cortesLista;
          if (lista.length > 0) {
            lista.forEach(c => cortesWrap.appendChild(criaRadioCorte(c)));
            corteCount.textContent = '0/1';
          } else {
            corteCount.textContent = '0/0';
          }
          setQtd(produto.tipo_quantidade === 'PESO' ? (produto.peso_minimo || 0.5) : 1);

          // Pré-preencher se for edição
          if (editPayload) {
            if (typeof editPayload.quantidade !== 'undefined') {
              setQtd(Number(editPayload.quantidade));
            }
            if (editPayload.observacao) {
              obs.value = editPayload.observacao;
              obsCount.textContent = obs.value.length + '/150';
            }
            if (editPayload.corte) {
              const sel = cortesWrap.querySelector('input[name="corte"][value="' + String(editPayload.corte) + '"]');
              if (sel) {
                sel.checked = true;
                corteSelecionado = editPayload.corte;
                corteCount.textContent = '1/1';
              }
            }
          }
          // Se a lista de cortes estiver incompleta, tenta obter a lista completa do servidor
          if (!Array.isArray(cortesLista) || cortesLista.length < 2) {
            fetch('produto_detalhe.php?id=' + encodeURIComponent(id))
              .then(r => (r && r.ok) ? r.json() : null)
              .then(dataFull => {
                if (!dataFull || !Array.isArray(dataFull.cortes)) return;
                cortesLista = dataFull.cortes;
                cortesWrap.innerHTML = '';
                cortesLista.forEach(c => cortesWrap.appendChild(criaRadioCorte(c)));
                // Reseleciona o corte anterior se houver
                if (editPayload && editPayload.corte) {
                  const sel = cortesWrap.querySelector('input[name="corte"][value="' + String(editPayload.corte) + '"]');
                  if (sel) {
                    sel.checked = true;
                    corteSelecionado = editPayload.corte;
                    corteCount.textContent = '1/1';
                  }
                } else {
                  corteCount.textContent = '0/1';
                }
              })
              .catch(() => {});
          }

          view.hidden = false;
          return; // não buscar no servidor
        }

        // Fallback: buscar no servidor
        fetch('produto_detalhe.php?id=' + encodeURIComponent(id))
          .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
          })
          .then(data => {
            produto = data.produto;
            nome.textContent = produto.nome;
            img.src = produto.imagem_url || 'img/imagensIlustrativa.jpg';
            document.getElementById('prodDesc').textContent = (produto.descricao || '').trim();
            cortesWrap.innerHTML = '';
            cortesLista = data.cortes || [];
            const lista = cortesLista;
            if (lista.length > 0) {
              lista.forEach(c => cortesWrap.appendChild(criaRadioCorte(c)));
              corteCount.textContent = '0/1';
            } else {
              corteCount.textContent = '0/0';
            }
            setQtd(produto.tipo_quantidade === 'PESO' ? produto.peso_minimo : 1);

            if (editPayload) {
              if (typeof editPayload.quantidade !== 'undefined') {
                setQtd(Number(editPayload.quantidade));
              }
              if (editPayload.observacao) {
                obs.value = editPayload.observacao;
                obsCount.textContent = obs.value.length + '/150';
              }
              if (editPayload.corte) {
                const sel = cortesWrap.querySelector('input[name="corte"][value="' + String(editPayload.corte) + '"]');
                if (sel) {
                  sel.checked = true;
                  corteSelecionado = editPayload.corte;
                  corteCount.textContent = '1/1';
                }
              }
            }
            view.hidden = false;
          })
          .catch(() => {
            view.hidden = false;
            nome.textContent = 'Erro ao carregar';
          });
      }

      function closeProduto() {
        if (typeof dlg.close === 'function') dlg.close();
        else dlg.removeAttribute('open');
        resetProdutoUI();
      }

      // Eventos UI
      obs.addEventListener('input', () => {
        obsCount.textContent = obs.value.length + '/150';
      });
      btnMais.addEventListener('click', () => {
        const step = produto && produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1;
        setQtd(quantidade + step);
      });
      btnMenos.addEventListener('click', () => {
        const step = produto && produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1;
        setQtd(quantidade - step);
      });
      btnAdicionar.addEventListener('click', () => {
        if (!produto) return;
        if (produto.tipo_quantidade === 'PESO' && cortesWrap.children.length > 0 && !corteSelecionado) {
          alert('Selecione um corte');
          return;
        }
        let corteNome = null;
        if (corteSelecionado && Array.isArray(cortesLista) && cortesLista.length) {
          const c = cortesLista.find(x => String(x.id) === String(corteSelecionado));
          if (c) corteNome = c.nome;
        }
        const item = {
          id: produto.id,
          nome: produto.nome,
          preco: produto.preco,
          tipo: produto.tipo_quantidade,
          quantidade,
          corte: corteSelecionado || null,
          corte_nome: corteNome,
          imagem_url: produto.imagem_url || '',
          observacao: obs.value || '',
          // Guardar metadados para edição sem depender do BD
          peso_minimo: produto.peso_minimo || (produto.tipo_quantidade === 'PESO' ? 0.5 : 1),
          intervalo_peso: produto.intervalo_peso || (produto.tipo_quantidade === 'PESO' ? 0.5 : 1),
          descricao: produto.descricao || '',
          cortes_lista: (cortesLista || []).map(c => ({
            id: c.id,
            nome: c.nome
          }))
        };
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        if (editIndex !== null && editIndex >= 0 && editIndex < carrinho.length) {
          carrinho[editIndex] = item;
        } else {
          carrinho.push(item);
        }
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        window.dispatchEvent(new Event('carrinho:change'));
        closeProduto();
      });

      // Delegação: abre ao clicar em ADICIONAR
      document.addEventListener('click', function(e) {
        const el = e.target.closest('.btn-add-prod');
        if (el && el.dataset && el.dataset.prodId) {
          openProduto(el.dataset.prodId);
        }
      });
      // expõe para edição vinda da sacola
      window.openProduto = openProduto;

      closeBtn && closeBtn.addEventListener('click', closeProduto);
      dlg && dlg.addEventListener('cancel', function(ev) {
        ev.preventDefault();
        closeProduto();
      });
    })();
  </script>
  <script>
    (function() {
      const lojaEndereco = 'Rua Alvorada, 123 Selina Dalu - Mirassol - SP';
      const btnTempo = document.getElementById('btnTempoEntrega');
      const dlg = document.getElementById('entregaDialog');
      const closeBtn = document.getElementById('closeEntregaDialog');
      const cancelBtn = document.getElementById('cancelEntrega');
      const saveBtn = document.getElementById('saveEntrega');
      const enderecoForm = document.getElementById('enderecoForm');
      const resumo = document.getElementById('enderecoResumo');

      const freteEl = document.getElementById('freteValor');
      const subtotalEl = document.getElementById('subtotalValor');
      const totalEl = document.getElementById('totalValor');

      // Campos de endereço (CEP auto-preenchimento)
      const cepEl = document.getElementById('cepInput');
      const ruaEl = document.getElementById('ruaInput');
      const numeroEl = document.getElementById('numeroInput');
      const bairroEl = document.getElementById('bairroInput');
      const cidadeEl = document.getElementById('cidadeInput');
      const ufEl = document.getElementById('ufInput');
      const compEl = document.getElementById('compInput');

      // Reinicia o pedido ao recarregar a página: limpa carrinho e endereço salvo
      try {
        localStorage.removeItem('carrinho');
        localStorage.removeItem('pedidoEntrega');
      } catch (e) {}
      if (resumo) resumo.textContent = '';

      function digitsOnly(s) {
        return (s || '').replace(/\D/g, '');
      }

      function maskCEP(s) {
        const d = digitsOnly(s).slice(0, 8);
        return d.length > 5 ? d.slice(0, 5) + '-' + d.slice(5) : d;
      }
      async function buscaCEP(cep) {
        const d = digitsOnly(cep);
        if (d.length !== 8) return;
        try {
          const r = await fetch('https://viacep.com.br/ws/' + d + '/json/');
          if (!r.ok) throw new Error('HTTP ' + r.status);
          const data = await r.json();
          if (data && !data.erro) {
            if (ruaEl && !ruaEl.value) ruaEl.value = data.logradouro || '';
            if (bairroEl && !bairroEl.value) bairroEl.value = data.bairro || '';
            if (cidadeEl && !cidadeEl.value) cidadeEl.value = data.localidade || '';
            if (ufEl && !ufEl.value) ufEl.value = data.uf || '';
            if (compEl && !compEl.value) compEl.value = data.complemento || '';
          } else {
            alert('CEP não encontrado.');
          }
        } catch (e) {
          console.warn('Erro ao buscar CEP', e);
        }
      }
      if (cepEl) {
        cepEl.addEventListener('input', function() {
          cepEl.value = maskCEP(cepEl.value);
          const d = digitsOnly(cepEl.value);
          if (d.length === 8) buscaCEP(d);
        });
        cepEl.addEventListener('blur', function() {
          const d = digitsOnly(cepEl.value);
          if (d.length === 8) buscaCEP(d);
        });
      }

      function formatBRL(num) {
        return num.toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        });
      }

      function parseBRL(str) {
        if (!str) return 0;
        return Number(str.replace(/[R$\.\s]/g, '').replace(',', '.')) || 0;
      }

      function openDlg() {
        if (typeof dlg.showModal === 'function') dlg.showModal();
        else dlg.setAttribute('open', 'open');
      }

      function closeDlg() {
        if (typeof dlg.close === 'function') dlg.close();
        else dlg.removeAttribute('open');
      }

      btnTempo && btnTempo.addEventListener('click', openDlg);
      closeBtn && closeBtn.addEventListener('click', closeDlg);
      cancelBtn && cancelBtn.addEventListener('click', closeDlg);
      dlg && dlg.addEventListener('cancel', function(e) {
        e.preventDefault();
        closeDlg();
      });

      // alterna exibição do formulário conforme tipo
      dlg && dlg.addEventListener('change', function(ev) {
        if (ev.target && ev.target.name === 'tipoEnvio') {
          enderecoForm.style.display = ev.target.value === 'ENTREGA' ? 'block' : 'none';
        }
      });

      function getEnderecoDigitado() {
        const cep = (document.getElementById('cepInput').value || '').trim();
        const rua = (document.getElementById('ruaInput').value || '').trim();
        const num = (document.getElementById('numeroInput').value || '').trim();
        const bairro = (document.getElementById('bairroInput').value || '').trim();
        const cidade = (document.getElementById('cidadeInput').value || '').trim();
        const uf = (document.getElementById('ufInput').value || '').trim().toUpperCase();
        const comp = (document.getElementById('compInput').value || '').trim();
        if (!rua || !num || !bairro || !cidade || !uf) return null;
        let s = rua + ', ' + num + ' - ' + bairro + ', ' + cidade + ' - ' + uf;
        if (cep) s = cep + ' • ' + s;
        if (comp) s += ' (' + comp + ')';
        return s;
      }

      function atualizaTotais() {
        const subtotal = parseBRL(subtotalEl.textContent);
        const frete = 0; // frete grátis, origem loja
        freteEl.textContent = formatBRL(frete);
        totalEl.textContent = formatBRL(subtotal + frete);
      }

      saveBtn && saveBtn.addEventListener('click', function() {
        const sel = dlg.querySelector('input[name="tipoEnvio"]:checked');
        const tipo = sel ? sel.value : 'ENTREGA';
        let textoResumo = '';
        if (tipo === 'ENTREGA') {
          const end = getEnderecoDigitado();
          if (!end) {
            alert('Preencha endereço completo: Endereço, Número, Bairro, Cidade e UF.');
            return;
          }
          textoResumo = 'Entrega para: ' + end;
          // frete grátis; origem: endereço da loja
        } else {
          textoResumo = 'Retirada na loja: ' + lojaEndereco;
        }
        resumo.textContent = textoResumo;
        try {
          var saved = {
            tipo: tipo,
            resumo: textoResumo
          };
          if (tipo === 'ENTREGA') {
            saved.cep = digitsOnly(cepEl.value || '');
            saved.rua = (ruaEl.value || '').trim();
            saved.numero = (numeroEl.value || '').trim();
            saved.bairro = (bairroEl.value || '').trim();
            saved.cidade = (cidadeEl.value || '').trim();
            saved.uf = ((ufEl.value || '').trim() || '').toUpperCase();
            saved.complemento = (compEl.value || '').trim();
          }
          localStorage.setItem('pedidoEntrega', JSON.stringify(saved));
        } catch (e) {}
        window.dispatchEvent(new Event('pedidoEntrega:change'));
        atualizaTotais();
        closeDlg();
      });

      // restaura resumo salvo
      try {
        const saved = JSON.parse(localStorage.getItem('pedidoEntrega') || 'null');
        if (saved && saved.resumo) resumo.textContent = saved.resumo;
      } catch (e) {}
    })();
  </script>
  <script>
    // Expor status de login do PHP para o JS
    window.CLIENTE_LOGADO = <?php echo $clienteLogado ? 'true' : 'false'; ?>;
    // Expor dados básicos do cliente (nome/telefone) direto do servidor para pré-preencher o modal
    window.CLIENTE_DADOS = <?php
                            $cli = ['nome' => '', 'telefone' => ''];
                            try {
                              $uid = 0;
                              if (isset($_SESSION['id_usuario'])) {
                                $uid = (int)$_SESSION['id_usuario'];
                              } elseif (isset($_SESSION['id_cliente'])) {
                                $uid = (int)$_SESSION['id_cliente'];
                              } elseif (isset($_SESSION['cliente_id'])) {
                                $uid = (int)$_SESSION['cliente_id'];
                              }
                              if ($uid > 0) {
                                require_once __DIR__ . '/conexao.php';
                                if ($stmt = $conn->prepare('SELECT nome, sobrenome, telefone FROM tbUsuario WHERE id_usuario = ? LIMIT 1')) {
                                  $stmt->bind_param('i', $uid);
                                  if ($stmt->execute()) {
                                    $res = $stmt->get_result();
                                    if ($row = $res->fetch_assoc()) {
                                      $nome = trim((string)($row['nome'] ?? '') . ' ' . (string)($row['sobrenome'] ?? ''));
                                      $cli['nome'] = $nome;
                                      $cli['telefone'] = (string)($row['telefone'] ?? '');
                                    }
                                  }
                                  $stmt->close();
                                }
                              }
                            } catch (Throwable $e) {
                            }
                            echo json_encode($cli, JSON_UNESCAPED_UNICODE);
                            ?>;

    (function() {
      var btn = document.getElementById('btnFinalizarPedido');
      var resumo = document.getElementById('enderecoResumo');

      function getCarrinho() {
        try {
          return JSON.parse(localStorage.getItem('carrinho') || '[]');
        } catch (e) {
          return [];
        }
      }

      function temItens() {
        return getCarrinho().length > 0;
      }

      function entregaInformada() {
        try {
          var x = JSON.parse(localStorage.getItem('pedidoEntrega') || 'null');
          return x && x.tipo;
        } catch (e) {
          return false;
        }
      }

      function setDisabled(dis) {
        if (!btn) return;
        btn.setAttribute('aria-disabled', dis ? 'true' : 'false');
        btn.style.opacity = dis ? '0.6' : '1';
      }

      function isLogged() {
        try {
          return window.CLIENTE_LOGADO === true || !!localStorage.getItem('clienteId');
        } catch (e) {
          return !!window.CLIENTE_LOGADO;
        }
      }

      function canFinalize() {
        return isLogged() && temItens() && entregaInformada();
      }

      function updateFinalizeState() {
        setDisabled(!canFinalize());
      }
      window.updateFinalizeState = updateFinalizeState;

      // Utilitário
      function formatBRL(num) {
        return Number(num || 0).toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        });
      }

      function digitsOnly(s) {
        return (s || '').replace(/\D/g, '');
      }

      // Modal de confirmação de pedido
      function openConfirmPedido() {
        var dlg = document.getElementById('confirmPedidoDialog');
        var closeBtn = document.getElementById('closeConfirmPedido');
        var cancelBtn = document.getElementById('cancelConfirmPedido');
        var saveBtn = document.getElementById('saveConfirmPedido');
        var nomeEl = document.getElementById('confNome');
        var telEl = document.getElementById('confTelefone');
        var itensWrap = document.getElementById('confItensLista');
        var totalEl = document.getElementById('confTotalValor');
        var endWrap = document.getElementById('confEnderecoWrap');
        var retWrap = document.getElementById('confRetiradaWrap');
        var horarioEl = document.getElementById('confHorario');

        // Endereço fields
        var cepEl = document.getElementById('confCEP');
        var ruaEl = document.getElementById('confRua');
        var numEl = document.getElementById('confNumero');
        var bairroEl = document.getElementById('confBairro');
        var cidadeEl = document.getElementById('confCidade');
        var ufEl = document.getElementById('confUF');
        var compEl = document.getElementById('confComp');

        function maskCEP(s) {
          var d = digitsOnly(s).slice(0, 8);
          return d.length > 5 ? d.slice(0, 5) + '-' + d.slice(5) : d;
        }

        // CEP máscara + auto-preenchimento via ViaCEP
        async function buscaCEPConfirm(cep) {
          var d = digitsOnly(cep);
          if (d.length !== 8) return;
          try {
            var r = await fetch('https://viacep.com.br/ws/' + d + '/json/');
            if (!r.ok) throw new Error('HTTP ' + r.status);
            var data = await r.json();
            if (data && !data.erro) {
              if (ruaEl && !ruaEl.value) ruaEl.value = data.logradouro || '';
              if (bairroEl && !bairroEl.value) bairroEl.value = data.bairro || '';
              if (cidadeEl && !cidadeEl.value) cidadeEl.value = data.localidade || '';
              if (ufEl && !ufEl.value) ufEl.value = (data.uf || '').toUpperCase();
              if (compEl && !compEl.value) compEl.value = data.complemento || '';
            } else {
              alert('CEP não encontrado.');
            }
          } catch (e) {
            console.warn('Erro ao buscar CEP', e);
          }
        }
        if (cepEl && !cepEl._bound) {
          cepEl.addEventListener('input', function() {
            cepEl.value = maskCEP(cepEl.value);
            var d = digitsOnly(cepEl.value);
            if (d.length === 8) buscaCEPConfirm(d);
          });
          cepEl.addEventListener('blur', function() {
            var d = digitsOnly(cepEl.value);
            if (d.length === 8) buscaCEPConfirm(d);
          });
          cepEl._bound = true;
        }

        // Alterna tipo envio
        Array.from(document.getElementsByName('confTipoEnvio')).forEach(function(r) {
          if (!r._bound) {
            r.addEventListener('change', function() {
              var tipo = this.value;
              endWrap.style.display = (tipo === 'ENTREGA') ? 'block' : 'none';
              retWrap.style.display = 'block';
            });
            r._bound = true;
          }
        });

        // Preenche cliente
        (function() {
          var idLocal = 0;
          try {
            idLocal = parseInt(localStorage.getItem('clienteId') || '0', 10) || 0;
          } catch (e) {}

          function fillCliente(d) {
            if (!d) return;
            nomeEl.value = ((d.nome || '') + (d.sobrenome ? (' ' + d.sobrenome) : '')).trim();
            telEl.value = d.telefone || '';
          }

          function fetchBySession() {
            return fetch('getCliente.php').then(function(r) {
              return r.ok ? r.json() : null;
            });
          }

          function fetchById(id) {
            return fetch('getCliente.php?id=' + encodeURIComponent(id)).then(function(r) {
              return r.ok ? r.json() : null;
            });
          }

          // Pré-preenche a partir dos dados injetados pelo servidor (sessão)
          try {
            if (window.CLIENTE_DADOS) {
              if (!nomeEl.value && window.CLIENTE_DADOS.nome) nomeEl.value = String(window.CLIENTE_DADOS.nome);
              if (!telEl.value && window.CLIENTE_DADOS.telefone) telEl.value = String(window.CLIENTE_DADOS.telefone);
            }
          } catch (e) {}

          // Tenta primeiro por sessão (usuário logado no PHP). Se falhar, tenta pelo localStorage.
          fetchBySession()
            .then(function(d) {
              if (d && !d.erro) {
                fillCliente(d);
                return;
              }
              if (idLocal > 0) {
                return fetchById(idLocal).then(fillCliente);
              }
            })
            .catch(function() {
              if (idLocal > 0) {
                fetchById(idLocal).then(fillCliente).catch(function() {});
              }
            });
        })();

        // Preenche itens e total
        (function() {
          var itens = [];
          try {
            itens = JSON.parse(localStorage.getItem('carrinho') || '[]');
          } catch (e) {
            itens = [];
          }
          itensWrap.innerHTML = '';
          var subtotal = 0;
          itens.forEach(function(it) {
            var qtd = Number(it.quantidade || 0);
            var preco = Number(it.preco || 0);
            var isPeso = (String(it.tipo || '').toUpperCase() === 'PESO');
            var qtdTxt = isPeso ? (qtd.toFixed(2).replace('.', ',')) + ' Kg' : (qtd + ' un');
            var linha = document.createElement('div');
            linha.style.display = 'grid';
            linha.style.gridTemplateColumns = '1fr auto auto';
            linha.style.gap = '8px';
            linha.style.alignItems = 'center';
            linha.innerHTML = '<div>' + (it.nome || '') + (it.corte_nome ? ' — <small>' + it.corte_nome + '</small>' : '') + (it.observacao ? '<br><small>Obs: ' + it.observacao + '</small>' : '') + '</div>' +
              '<div style="opacity:.8;">' + qtdTxt + '</div>' +
              '<div style="font-weight:600;">' + formatBRL(qtd * preco) + '</div>';
            itensWrap.appendChild(linha);
            subtotal += qtd * preco;
          });
          totalEl.textContent = formatBRL(subtotal);
        })();

        // Tipo envio padrão baseado no que foi salvo
        (function() {
          var tipo = 'ENTREGA';
          try {
            var s = JSON.parse(localStorage.getItem('pedidoEntrega') || 'null');
            if (s && s.tipo) tipo = s.tipo;
          } catch (e) {}
          var radios = document.getElementsByName('confTipoEnvio');
          Array.from(radios).forEach(function(r) {
            r.checked = (r.value === tipo);
          });
          endWrap.style.display = (tipo === 'ENTREGA') ? 'block' : 'none';
          retWrap.style.display = 'block';

          // Preenche os campos de endereço com o que foi informado em "Calcular tempo de entrega"
          try {
            var saved = JSON.parse(localStorage.getItem('pedidoEntrega') || 'null');
            if (saved && saved.tipo === 'ENTREGA') {
              if (cepEl && saved.cep) cepEl.value = maskCEP(String(saved.cep));
              if (ruaEl && saved.rua) ruaEl.value = saved.rua;
              if (numEl && saved.numero) numEl.value = saved.numero;
              if (bairroEl && saved.bairro) bairroEl.value = saved.bairro;
              if (cidadeEl && saved.cidade) cidadeEl.value = saved.cidade;
              if (ufEl && saved.uf) ufEl.value = saved.uf;
              if (compEl && saved.complemento) compEl.value = saved.complemento;
            }
          } catch (e) {}
        })();

        function open() {
          if (typeof dlg.showModal === 'function') dlg.showModal();
          else dlg.setAttribute('open', 'open');
        }

        function close() {
          if (typeof dlg.close === 'function') dlg.close();
          else dlg.removeAttribute('open');
        }

        if (closeBtn && !closeBtn._bound) {
          closeBtn.addEventListener('click', close);
          closeBtn._bound = true;
        }
        if (cancelBtn && !cancelBtn._bound) {
          cancelBtn.addEventListener('click', close);
          cancelBtn._bound = true;
        }
        if (dlg && !dlg._bound) {
          dlg.addEventListener('cancel', function(e) {
            e.preventDefault();
            close();
          });
          dlg._bound = true;
        }

        if (saveBtn && !saveBtn._bound) {
          saveBtn.addEventListener('click', function() {
            // Monta payload
            var tipoEl = Array.from(document.getElementsByName('confTipoEnvio')).find(function(r) {
              return r.checked;
            });
            var tipo = tipoEl ? tipoEl.value : 'ENTREGA';
            var horario = (horarioEl && horarioEl.value) ? horarioEl.value : '';
            if (!horario) {
              alert('Informe o horário desejado.');
              try { horarioEl && horarioEl.focus(); } catch (e) {}
              return;
            }

            var itens = [];
            try {
              itens = JSON.parse(localStorage.getItem('carrinho') || '[]');
            } catch (e) {}
            if (!Array.isArray(itens) || itens.length === 0) {
              alert('Sua sacola está vazia.');
              return;
            }

            var payloadItens = itens.map(function(it) {
              return {
                produto: it.nome,
                quantidade: it.quantidade,
                observacao: it.observacao || '',
                corte: (typeof it.corte !== 'undefined' && it.corte !== null && it.corte !== '' ? it.corte : null)
              };
            });

            var clienteId = 0;
            try {
              clienteId = parseInt(localStorage.getItem('clienteId') || '0', 10) || 0;
            } catch (e) {}

            // Endereço (ENTREGA)
            var enderecoTxt = '';
            var cepTxt = '';
            var numTxt = '';
            var compTxt = '';
            if (tipo === 'ENTREGA') {
              var rua = (ruaEl.value || '').trim();
              var bairro = (bairroEl.value || '').trim();
              var cidade = (cidadeEl.value || '').trim();
              var uf = (ufEl.value || '').trim().toUpperCase();
              numTxt = (numEl.value || '').trim();
              cepTxt = digitsOnly(cepEl.value || '');
              compTxt = (compEl.value || '').trim();

              if (!rua) { alert('Preencha o endereço (Rua/Avenida).'); try { ruaEl && ruaEl.focus(); } catch (e) {} return; }
              if (!numTxt) { alert('Informe o número.'); try { numEl && numEl.focus(); } catch (e) {} return; }
              if (!bairro) { alert('Informe o bairro.'); try { bairroEl && bairroEl.focus(); } catch (e) {} return; }
              if (!cidade) { alert('Informe a cidade.'); try { cidadeEl && cidadeEl.focus(); } catch (e) {} return; }
              if (!uf || uf.length !== 2) { alert('Informe a UF com 2 letras.'); try { ufEl && ufEl.focus(); } catch (e) {} return; }
              if (cepTxt && cepTxt.length !== 8) { alert('CEP inválido (use 8 dígitos).'); try { cepEl && cepEl.focus(); } catch (e) {} return; }

              enderecoTxt = rua + ', ' + numTxt + ' - ' + bairro + ', ' + cidade + ' - ' + uf;
              if (compTxt) enderecoTxt += ' (' + compTxt + ')';
            }

            var params = new URLSearchParams();
            params.set('cliente_id', String(clienteId));
            params.set('recebimento', tipo);
            params.set('horario', horario);
            params.set('itens', JSON.stringify(payloadItens));
            if (tipo === 'ENTREGA') {
              params.set('endereco', enderecoTxt);
              if (cepTxt) params.set('cep', cepTxt);
              if (numTxt) params.set('numero', numTxt);
              if (compTxt) params.set('complemento', compTxt);
            }

            fetch('cadastraPedidoBD.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: params.toString()
              })
              .then(function(r) {
                return r.text();
              })
              .then(function(txt) {
                txt = (txt || '').trim();
                if (txt === 'ok') {
                  alert('Pedido enviado com sucesso!');
                  try {
                    localStorage.removeItem('carrinho');
                    localStorage.removeItem('pedidoEntrega');
                  } catch (e) {}
                  window.dispatchEvent(new Event('carrinho:change'));
                  try {
                    var r = document.getElementById('enderecoResumo');
                    if (r) r.textContent = '';
                  } catch (e) {}
                  close();
                  // Recarrega para garantir UI limpa (sacola e totais resetados)
                  location.reload();
                } else {
                  alert('Falha ao enviar o pedido.');
                }
              })
              .catch(function() {
                alert('Falha de comunicação com o servidor.');
              });
          });
          saveBtn._bound = true;
        }

        open();
      }

      // Eventos que podem mudar o estado
      window.addEventListener('carrinho:change', updateFinalizeState);
      window.addEventListener('pedidoEntrega:change', updateFinalizeState);
      var btnLimpar = document.getElementById('btnLimparCarrinho');
      if (btnLimpar) btnLimpar.addEventListener('click', function() {
        setTimeout(function() {
          window.dispatchEvent(new Event('carrinho:change'));
        }, 50);
      });
      document.addEventListener('DOMContentLoaded', updateFinalizeState);
      // reage a mudanças de login no localStorage
      window.addEventListener('storage', function(e) {
        if (e.key === 'clienteId') // chama novamente ao final para garantir estado correto
          updateFinalizeState();
      });

      // Clique Finalizar
      if (btn) btn.addEventListener('click', function() {
        if (!isLogged()) {
          var loginDlg = document.getElementById('loginDialog');
          if (loginDlg) {
            if (typeof loginDlg.showModal === 'function') loginDlg.showModal();
            else loginDlg.setAttribute('open', 'open');
          }
          return;
        }
        if (!temItens()) {
          alert('Adicione pelo menos um item à sacola antes de finalizar.');
          return;
        }
        if (!entregaInformada()) {
          alert('Informe a forma de receber antes de finalizar o pedido.');
          var d = document.getElementById('entregaDialog');
          if (d) {
            if (typeof d.showModal === 'function') d.showModal();
            else d.setAttribute('open', 'open');
          }
          return;
        }
        // Tudo OK: abrir modal de confirmação
        openConfirmPedido();
      });
      if (btn) btn.addEventListener('keydown', function(e) {
        if ((e.key === 'Enter' || e.key === ' ') && btn.getAttribute('aria-disabled') !== 'true') {
          btn.click();
        }
      });

      updateFinalizeState();
    })();
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script>
    (function() {
      const loginDlg = document.getElementById('loginDialog');
      const btn = document.getElementById('btnOpenLogin');
      const closeLoginBtn = document.getElementById('closeLoginDialog');

      const perfilDlg = document.getElementById('perfilDialog');
      const closePerfilBtn = document.getElementById('closePerfilDialog');
      const btnLogout = document.getElementById('btnLogout');
      const btnEdit = document.getElementById('btnEditarPerfil');
      const btnSave = document.getElementById('btnSalvarPerfil');
      const btnCancel = document.getElementById('btnCancelarPerfil');

      let editSnapshot = null;

      function openLogin() {
        if (loginDlg && typeof loginDlg.showModal === 'function') loginDlg.showModal();
        else if (loginDlg) loginDlg.setAttribute('open', 'open');
      }

      function closeLogin() {
        if (loginDlg && typeof loginDlg.close === 'function') loginDlg.close();
        else if (loginDlg) loginDlg.removeAttribute('open');
      }

      function openPerfil() {
        if (perfilDlg && typeof perfilDlg.showModal === 'function') perfilDlg.showModal();
        else if (perfilDlg) perfilDlg.setAttribute('open', 'open');
        preencherPerfil();
      }

      function closePerfil() {
        if (perfilDlg && typeof perfilDlg.close === 'function') perfilDlg.close();
        else if (perfilDlg) perfilDlg.removeAttribute('open');
      }

      function preencherPerfil() {
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');
        // Limpa antes de preencher
        if (nomeEl) {
          nomeEl.value = '';
          nomeEl.readOnly = true;
        }
        if (sobrenomeEl) {
          sobrenomeEl.value = '';
          sobrenomeEl.readOnly = true;
        }
        if (telefoneEl) {
          telefoneEl.value = '';
          telefoneEl.readOnly = true;
        }
        toggleEdit(false);

        let idLocal = 0;
        try {
          idLocal = parseInt(localStorage.getItem('clienteId') || '0', 10) || 0;
        } catch (e) {}
        const url = idLocal > 0 ? ('getCliente.php?id=' + encodeURIComponent(idLocal)) : 'getCliente.php';

        fetch(url)
          .then(r => r.ok ? r.json() : null)
          .then(d => {
            if (d && !d.erro) {
              if (nomeEl) nomeEl.value = String(d.nome || '');
              if (sobrenomeEl) sobrenomeEl.value = String(d.sobrenome || '');
              if (telefoneEl) telefoneEl.value = String(d.telefone || '');
              return;
            }
            // Fallback: usa dados injetados pelo servidor
            try {
              const cd = window.CLIENTE_DADOS || {};
              const full = String(cd.nome || '').trim();
              if (full) {
                const parts = full.split(/\s+/);
                const primeiro = parts.shift() || '';
                const resto = parts.join(' ');
                if (nomeEl) nomeEl.value = primeiro;
                if (sobrenomeEl) sobrenomeEl.value = resto;
              }
              if (telefoneEl && cd.telefone) telefoneEl.value = String(cd.telefone);
            } catch (e) {}
          })
          .catch(() => {
            // Fallback em caso de erro na rede
            try {
              const cd = window.CLIENTE_DADOS || {};
              const full = String(cd.nome || '').trim();
              if (full) {
                const parts = full.split(/\s+/);
                const primeiro = parts.shift() || '';
                const resto = parts.join(' ');
                if (nomeEl) nomeEl.value = primeiro;
                if (sobrenomeEl) sobrenomeEl.value = resto;
              }
              if (telefoneEl && cd.telefone) telefoneEl.value = String(cd.telefone);
            } catch (e) {}
          });
      }

      function handleIconClick() {
        if (window.CLIENTE_LOGADO === true || localStorage.getItem('clienteId')) openPerfil();
        else openLogin();
      }

      btn && btn.addEventListener('click', handleIconClick);
      closeLoginBtn && closeLoginBtn.addEventListener('click', closeLogin);
      loginDlg && loginDlg.addEventListener('cancel', function(e) {
        e.preventDefault();
        closeLogin();
      });

      closePerfilBtn && closePerfilBtn.addEventListener('click', closePerfil);
      perfilDlg && perfilDlg.addEventListener('cancel', function(e) {
        e.preventDefault();
        closePerfil();
      });

      function toggleEdit(on) {
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');

        if (on) {
          // guarda os valores atuais para permitir cancelamento
          editSnapshot = {
            nome: nomeEl ? nomeEl.value : '',
            sobrenome: sobrenomeEl ? sobrenomeEl.value : '',
            telefone: telefoneEl ? telefoneEl.value : ''
          };
        }

        if (nomeEl) nomeEl.readOnly = !on;
        if (sobrenomeEl) sobrenomeEl.readOnly = !on;
        if (telefoneEl) telefoneEl.readOnly = !on;
        if (btnEdit) btnEdit.style.display = on ? 'none' : '';
        if (btnSave) btnSave.style.display = on ? '' : 'none';
        if (btnCancel) btnCancel.style.display = on ? '' : 'none';

        if (!on) {
          // saindo do modo edição, limpa o snapshot
          editSnapshot = null;
        }
      }

      function maskTel(v) {
        v = (v || '').replace(/\D/g, '');
        if (v.length > 0) v = '(' + v;
        if (v.length > 3) v = v.slice(0, 3) + ') ' + v.slice(3);
        if (v.length > 10) v = v.slice(0, 10) + '-' + v.slice(10);
        if (v.length > 15) v = v.slice(0, 15);
        return v;
      }

      telefoneEl = document.getElementById('perfilTelefone');
      if (telefoneEl) {
        telefoneEl.addEventListener('input', function() {
          if (!telefoneEl.readOnly) telefoneEl.value = maskTel(telefoneEl.value);
        });
      }

      btnEdit && btnEdit.addEventListener('click', function() {
        toggleEdit(true);
      });
      btnCancel && btnCancel.addEventListener('click', function() {
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');
        // restaura valores originais ao cancelar
        if (editSnapshot) {
          if (nomeEl) nomeEl.value = editSnapshot.nome || '';
          if (sobrenomeEl) sobrenomeEl.value = editSnapshot.sobrenome || '';
          if (telefoneEl) telefoneEl.value = editSnapshot.telefone || '';
        }
        toggleEdit(false);
      });

      // Salvar
      const perfilForm = document.getElementById('perfilForm');
      perfilForm && perfilForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const nomeEl = document.getElementById('perfilNome');
        const sobrenomeEl = document.getElementById('perfilSobrenome');
        const telefoneEl = document.getElementById('perfilTelefone');
        const nome = (nomeEl && nomeEl.value || '').trim();
        const sobrenome = (sobrenomeEl && sobrenomeEl.value || '').trim();
        const telefone = (telefoneEl && telefoneEl.value || '').trim();
        // validações simples
        let ok = true;

        function setErr(el, on) {
          const p = el && el.parentElement;
          if (p) p.className = on ? 'form_content error' : 'form_content';
        }
        if (!nome) {
          ok = false;
          setErr(nomeEl, true);
        } else setErr(nomeEl, false);
        if (!sobrenome) {
          ok = false;
          setErr(sobrenomeEl, true);
        } else setErr(sobrenomeEl, false);
        if (!telefone || telefone.length !== 15) {
          ok = false;
          setErr(telefoneEl, true);
        } else setErr(telefoneEl, false);
        if (!ok) return;
        // monta payload
        let idLocal = 0;
        try {
          idLocal = parseInt(localStorage.getItem('clienteId') || '0', 10) || 0;
        } catch (e) {}
        const data = new URLSearchParams();
        if (idLocal > 0) data.set('id', String(idLocal));
        data.set('nome', nome);
        data.set('sobrenome', sobrenome);
        data.set('telefone', telefone);
        fetch('updateCliente.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: data.toString()
          })
          .then(r => r.ok ? r.json() : null)
          .then(resp => {
            if (resp && resp.ok) {
              // atualiza placeholders e recarrega para refletir alterações
              try {
                if (nomeEl) {
                  nomeEl.placeholder = nome;
                }
                if (sobrenomeEl) {
                  sobrenomeEl.placeholder = sobrenome;
                }
                if (telefoneEl) {
                  telefoneEl.placeholder = telefone;
                }
              } catch (e) {}
              if (typeof perfilDlg?.close === 'function') try {
                perfilDlg.close();
              } catch (e) {}
              location.reload();
              return;
            } else {
              alert('Falha ao salvar alterações.');
            }
          })
          .catch(() => alert('Erro de comunicação com o servidor.'));
      });

      btnLogout && btnLogout.addEventListener('click', function() {
        try {
          localStorage.removeItem('clienteId');
        } catch (e) {}
        window.location.href = 'index.php?logout=1';
      });
    })();
  </script>
  <script src="./js/login.js"></script>
  <script>
    // Abrir/fechar cadastro via links e ícones
    (function() {
      const loginDlg = document.getElementById('loginDialog');
      const cadDlg = document.getElementById('cadastroDialog');
      const openCadLink = document.getElementById('openCadastroLink');
      const openLoginLink = document.getElementById('openLoginFromCadastro');
      const closeCadBtn = document.getElementById('closeCadastroDialog');

      function openLogin() {
        if (loginDlg && typeof loginDlg.showModal === 'function') loginDlg.showModal();
        else if (loginDlg) loginDlg.setAttribute('open', 'open');
      }

      function closeLogin() {
        if (loginDlg && typeof loginDlg.close === 'function') loginDlg.close();
        else if (loginDlg) loginDlg.removeAttribute('open');
      }

      function openCad() {
        if (cadDlg && typeof cadDlg.showModal === 'function') cadDlg.showModal();
        else if (cadDlg) cadDlg.setAttribute('open', 'open');
      }

      function closeCad() {
        if (cadDlg && typeof cadDlg.close === 'function') cadDlg.close();
        else if (cadDlg) cadDlg.removeAttribute('open');
      }

      openCadLink && openCadLink.addEventListener('click', function(e) {
        e.preventDefault();
        closeLogin();
        openCad();
      });
      openLoginLink && openLoginLink.addEventListener('click', function(e) {
        e.preventDefault();
        closeCad();
        openLogin();
      });
      closeCadBtn && closeCadBtn.addEventListener('click', closeCad);
      cadDlg && cadDlg.addEventListener('cancel', function(e) {
        e.preventDefault();
        closeCad();
      });
    })();
  </script>
  <script>
    // Lógica do formulário de cadastro (equivalente a js/cadastra.js, porém escopada ao modal)
    (function() {
      const dlg = document.getElementById('cadastroDialog');
      if (!dlg) return;
      const form = dlg.querySelector('#cadForm');
      const nome = dlg.querySelector('#cadNome');
      const sobrenome = dlg.querySelector('#cadSobrenome');
      const telefone = dlg.querySelector('#cadTelefone');
      const senha = dlg.querySelector('#cadSenha');
      const senhaConf = dlg.querySelector('#cadSenhaConf');
      const msg = dlg.querySelector('#cadMensagem');
      if (msg) {
        try {
          $(msg).fadeOut(0);
        } catch (e) {
          msg.style.display = 'none';
        }
      }

      function setOk(el) {
        const it = el.parentElement;
        if (it) it.className = 'form_content';
      }

      function setErr(el, m) {
        const it = el.parentElement;
        if (!it) return;
        const a = it.querySelector('a');
        if (a) a.innerText = m;
        it.className = 'form_content error';
      }

      function maskTel(v) {
        v = (v || '').replace(/\D/g, '');
        if (v.length > 0) v = '(' + v;
        if (v.length > 3) v = v.slice(0, 3) + ') ' + v.slice(3);
        if (v.length > 10) v = v.slice(0, 10) + '-' + v.slice(10);
        if (v.length > 15) v = v.slice(0, 15);
        return v;
      }

      telefone && telefone.addEventListener('input', function() {
        telefone.value = maskTel(telefone.value);
      });
      nome && nome.addEventListener('blur', function() {
        if (!nome.value.trim()) setErr(nome, 'preencha um nome de usuario');
        else setOk(nome);
      });
      sobrenome && sobrenome.addEventListener('blur', function() {
        if (!sobrenome.value.trim()) setErr(sobrenome, 'preencha seu sobrenome');
        else setOk(sobrenome);
      });
      telefone && telefone.addEventListener('blur', function() {
        const v = telefone.value;
        if (!v) setErr(telefone, 'preencha seu telefone');
        else if (v.length !== 15) setErr(telefone, 'preencha seu numero completo');
        else setOk(telefone);
      });
      senha && senha.addEventListener('blur', function() {
        const v = senha.value;
        if (!v) setErr(senha, 'digite uma senha');
        else if (v.length < 8) setErr(senha, 'minimo de 8 caracteres');
        else setOk(senha);
      });
      senhaConf && senhaConf.addEventListener('blur', function() {
        const v = senhaConf.value;
        if (!v) setErr(senhaConf, 'repita sua senha');
        else if (v !== senha.value) setErr(senhaConf, 'sua senha não esta igual');
        else setOk(senhaConf);
      });

      function isValid() {
        nome.dispatchEvent(new Event('blur'));
        sobrenome.dispatchEvent(new Event('blur'));
        telefone.dispatchEvent(new Event('blur'));
        senha.dispatchEvent(new Event('blur'));
        senhaConf.dispatchEvent(new Event('blur'));
        const items = form.querySelectorAll('.form_content');
        return Array.from(items).every(it => it.className === 'form_content');
      }

      form && form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!isValid()) return;
        const payload = {
          nome: nome.value.trim(),
          sobrenome: sobrenome.value.trim(),
          telefone: telefone.value.trim(),
          senha: senha.value
        };
        $.ajax({
          url: 'cadastraLogin.php',
          method: 'POST',
          data: payload,
          success: function(response) {
            const resp = (response || '').toString().trim();
            if (resp === 'ok') {
              $(msg).html('Cadastrado com sucesso');
              $(msg).fadeIn(300).delay(2000).fadeOut(400);
              setTimeout(function() {
                form.reset();
              }, 2500);
            } else {
              $(msg).html('Essa conta já existe ou ocorreu um erro');
              $(msg).fadeIn(300).delay(2000).fadeOut(400);
            }
          },
          error: function() {
            $(msg).html('Falha na comunicação com o servidor');
            $(msg).fadeIn(300).delay(2000).fadeOut(400);
          }
        });
      });
    })();
  </script>
</body>

</html>
