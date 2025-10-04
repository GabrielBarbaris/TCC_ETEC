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
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
  <title>Casa de Carnes</title>
</head>

<body>
  <div class="tela-inicial">

    <!-- =============================
         BANNER / IMAGEM ILUSTRATIVA
         - Contém a imagem principal do topo (.imagem-ilustrativa)
         - .sombra: sobreposição escura usada para contraste do texto (CSS)
         - .sliders: indicadores visuais (bolinhas)
         ============================= -->
    <div class="IMAGEM">
      <img class="imagem-ilustrativa" src="img/imagensIlustrativa.jpg" alt="Banner ilustrativo" />
      <div class="sombra"></div>
      <div class="sliders">
        <div class="ellipse"></div>
        <div class="ellipse-2"></div>
        <div class="ellipse-2"></div>
      </div>
    </div>

    <!-- =============================
         CONTEÚDO PRINCIPAL (CORPO)
         - .corpo contém 2 colunas: .corpo_1 (conteúdo) e (opcional) .corpo_2 (pedido)
         - Mantive as classes originais para compatibilidade total com o CSS atual.
         ============================= -->
    <div class="corpo">

      <!-- =============================
           COLUNA PRINCIPAL: produtos dinâmicos, kits e "dia a dia"
           - .corpo_1 agrupa as seções principais.
           - As seções abaixo (churrasco_qualidade, selo-qualidade, dia_dia)
             usam classes já presentes no CSS.
           ============================= -->
      <div class="corpo_1">

        <!-- seção principal de destaque -->
        <section class="churrasco_qualidade">
          <div class="separao">
            <p class="p"><span class="text-wrapper">churrasco de </span> <span class="span">qualidade</span></p>
          </div>

          <!--
            LOOP PHP: busca produtos em tbProduto e renderiza um card para cada produto.
            Observações:
             - Mantive htmlspecialchars() e number_format() para segurança e formatação.
             - O HTML gerado pelo echo usa as mesmas classes que o seu CSS espera.
             - Se quiser inserir atributos data-* (ex: data-id="$id") para JS, me diga que eu adiciono.
          -->
          <?php
          require 'conexao.php';
          $comandoSql = 'SELECT * FROM tbProduto';
          $result = mysqli_query($conn, $comandoSql);

          if (mysqli_num_rows($result) > 0) {
            while ($produto = mysqli_fetch_assoc($result)) {
              $id = $produto['id_produto'];
              $nome = htmlspecialchars($produto['nome_produto'], ENT_QUOTES);
              $descricao = htmlspecialchars($produto['descricao'], ENT_QUOTES);
              $preco = number_format($produto['preco'], 2, ',', '.');
              $url = htmlspecialchars($produto['imagem_url'], ENT_QUOTES);

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
            echo '<p>Não existem produtos cadastrados!</p>';
          }
          ?>
        </section>

        <!-- =============================
             SEÇÃO: selo / kits
             - .selo-qualidade e .kit_fernandes: área com dois kits estáticos
             - Reaproveita as mesmas classes de card (img-4, rectangle-2, boto etc.)
             ============================= -->
        <aside class="selo-qualidade">
          <div class="overlap-3">
            <div class="informacoes_kit">
              <div class="text-wrapper-4">
                kits fernandes
                <p class="text-wrapper-5">
                  Pensando no seu bem-estar, preparamos um kit completo para você passar a semana com
                  muito mais praticidade, conforto e sabor. Selecionamos os melhores mantimentos e
                  embalamos tudo a vácuo, garantindo máxima conservação, frescor e facilidade no
                  manuseio.
                </p>
              </div>

              <div class="kit_fernandes">
                <div class="kit">
                  <div class="overlap-4">
                    <img class="img-4" src="img/KitChurrasco.png" alt="Kit Churrasco" />
                    <div class="rectangle-2"></div>
                    <div class="text-wrapper-6">kit 2</div>
                    <p class="text-wrapper-7">
                      O combo perfeito para um churrasco completo: carnes nobres, suculentas e prontas para brilhar na
                      grelha.
                    </p>
                    <p class="r-KG"><span class="text-wrapper">R$43,99 </span> <span class="text-wrapper-8">KG</span>
                    </p>
                    <div class="boto">
                      <div class="overlap-group-2">
                        <div class="rectangle-3"></div>
                        <div class="text-wrapper-9">ADICIONAR</div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="kit">
                  <div class="overlap-5">
                    <img class="img-4" src="img/KitMistura.png" alt="Kit Mistura" />
                    <div class="rectangle-4"></div>
                    <div class="text-wrapper-12">kit 1</div>
                    <p class="text-wrapper-13">
                      Um kit especial com uma seleção de carnes fresquinhas e versáteis.
                    </p>
                    <p class="r-KG-2"><span class="text-wrapper">R$43,99 </span> <span class="text-wrapper-8">KG</span>
                    </p>
                    <div class="boto">
                      <div class="overlap-group-2">
                        <div class="rectangle-3"></div>
                        <div class="text-wrapper-9">ADICIONAR</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> <!-- .kit_fernandes -->
            </div>
          </div>
        </aside>

        <!-- =============================
             SEÇÃO: dia a dia (produtos estáticos exemplares)
             - Mantive os cards estáticos com classes originais
             - Use .img-2 / .img-3 / .img-4 conforme o CSS já referencia
             ============================= -->
        <section class="dia_dia">
          <div class="separao">
            <p class="p">
              <span class="text-wrapper">para o seu </span>
              <span class="text-wrapper-2">dia</span>
              <span class="text-wrapper"> a </span>
              <span class="text-wrapper-3">dia</span>
            </p>
          </div>

          <div class="bisteca">
            <div class="overlap-4">
              <img class="img-2" src="img/bisteca.png" alt="Bisteca" />
              <div class="rectangle-2"></div>
              <div class="text-wrapper-6">bisteca</div>
              <p class="text-wrapper-7">
                Carne suculenta e macia, ótima para churrasco.
              </p>
              <p class="r-KG"><span class="text-wrapper">R$43,99 </span> <span class="text-wrapper-8">KG</span></p>
              <div class="boto">
                <div class="overlap-group-2">
                  <div class="rectangle-3"></div>
                  <div class="text-wrapper-9">ADICIONAR</div>
                </div>
              </div>
            </div>
          </div>

          <div class="peito-de-frango">
            <div class="overlap-4">
              <img class="img-2" src="img/peitoDeFrango.png" alt="Peito de frango" />
              <div class="rectangle-2"></div>
              <div class="text-wrapper-10">peito de frango</div>
              <p class="text-wrapper-7">Sabor leve, ótimo para grelhar.</p>
              <p class="r-KG"><span class="text-wrapper">R$43,99 </span> <span class="text-wrapper-8">KG</span></p>
              <div class="boto">
                <div class="overlap-group-2">
                  <div class="rectangle-3"></div>
                  <div class="text-wrapper-9">ADICIONAR</div>
                </div>
              </div>
            </div>
          </div>

          <div class="hamburguer">
            <div class="overlap-4">
              <img class="img-3" src="img/image.png" alt="" />
              <img class="img-4" src="img/hamburguer.png" alt="Hambúrguer" />
              <div class="rectangle-2"></div>
              <div class="text-wrapper-11">hamburguer</div>
              <p class="text-wrapper-7">Blend artesanal, pronto para grelhar.</p>
              <p class="r-KG"><span class="text-wrapper">R$43,99 </span> <span class="text-wrapper-8">KG</span></p>
              <div class="boto">
                <div class="overlap-group-2">
                  <div class="rectangle-3"></div>
                  <div class="text-wrapper-9">ADICIONAR</div>
                </div>
              </div>
            </div>
          </div>

          <div class="carne-moida">
            <div class="overlap-4">
              <img class="img-3" src="img/carneMoida.png" alt="Carne moída" />
              <div class="rectangle-2"></div>
              <div class="text-wrapper-6">acem moido</div>
              <p class="text-wrapper-7">Versátil para receitas do dia a dia.</p>
              <p class="r-KG"><span class="text-wrapper">R$43,99 </span> <span class="text-wrapper-8">KG</span></p>
              <div class="boto">
                <div class="overlap-group-2">
                  <div class="rectangle-3"></div>
                  <div class="text-wrapper-9">ADICIONAR</div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div> <!-- .corpo_1 -->

      <!-- =============================
           COLUNA DIREITA: pedido / sacola (OPCIONAL)
           - O bloco está comentado no HTML original — deixei comentado para ativar quando necessário.
           - Se ativar, mantenha as classes (.corpo_2, .pedido, .item-contra) para o CSS.
           ============================= -->
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

    </div> <!-- .corpo -->

    <!-- =============================
         RODAPÉ
         - .rodape e .interna_rodape mantém as classes para o CSS atual.
         - Contém informações, contato e horário.
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



    <!-- =============================
         HEADER / CATEGORIAS (FIXO)
         - Mantive a estrutura HTML original com todas as classes.
         - A barra de categorias (.CATEGORIAS) é posicionada pelo CSS.
         ============================= -->
    <header class="HEADER">
      <div class="overlap-17">
        <div class="CATEGORIAS">
          <div class="CHURRASCO">
            <div class="overlap-group-3">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-57">CHURRASCO</div>
              <img class="weber" src="img/churrasco.png" alt="">
            </div>
          </div>

          <div class="KITS">
            <div class="overlap-11">
              <div class="text-wrapper-58">KITS</div>
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <img class="shopping-basket" src="img/kits.png" alt="">
            </div>
          </div>

          <div class="AVES">
            <div class="overlap-12">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-59">AVES</div>
              <img class="poultry-leg" src="img/aves.png" alt="">
            </div>
          </div>

          <div class="EMBUTIDOS">
            <div class="overlap-13">
              <div class="text-wrapper-60">EMBUTIDOS</div>
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <img class="salami" src="img/embutido.png" alt="">
            </div>
          </div>

          <div class="SUNOS">
            <div class="overlap-14">
              <img class="rectangle-8" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-61">SUiNOS</div>
              <img class="bacon" src="img/suino.png" alt="">
            </div>
          </div>

          <div class="LINGUIAS">
            <div class="overlap-15">
              <img class="rectangle-7" src="img/bordaCategoria.png" alt="">
              <div class="text-wrapper-62">LINGUICAS</div>
              <img class="vector-2" src="img/linguica.png" alt="">
            </div>
          </div>

          <div class="BOVINOS">
            <div class="overlap-16">
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

          <button onclick="cadastrar_cliente()">
            <a href="cadastra.php"><img class="user-user" src="img/login.png" alt="login" /></a>
          </button>

          <button title="Ver sacola">
            <img class="basket" src="img/pedido.png" alt="pedido" />
          </button>
        </div>

        <img class="logo-2" src="img/logo.png" alt="Logo" />
      </div>
    </header>
  </div> <!-- .tela-inicial -->

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

      <div id="confRetiradaWrap" style="display:none;">
        <div style="display:flex; flex-direction:column; max-width:200px;">
          <label for="confHorario">Horário desejado</label>
          <input id="confHorario" type="time">
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

  <style>
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
    (function(){
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
      try { localStorage.removeItem('carrinho'); localStorage.removeItem('pedidoEntrega'); } catch(e){}
      if (resumo) resumo.textContent = '';

      function digitsOnly(s){ return (s||'').replace(/\D/g,''); }
      function maskCEP(s){ const d = digitsOnly(s).slice(0,8); return d.length>5 ? d.slice(0,5)+'-'+d.slice(5) : d; }
      async function buscaCEP(cep){
        const d = digitsOnly(cep);
        if (d.length !== 8) return;
        try {
          const r = await fetch('https://viacep.com.br/ws/'+d+'/json/');
          if (!r.ok) throw new Error('HTTP '+r.status);
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
        } catch(e){ console.warn('Erro ao buscar CEP', e); }
      }
      if (cepEl){
        cepEl.addEventListener('input', function(){
          cepEl.value = maskCEP(cepEl.value);
          const d = digitsOnly(cepEl.value);
          if (d.length === 8) buscaCEP(d);
        });
        cepEl.addEventListener('blur', function(){
          const d = digitsOnly(cepEl.value);
          if (d.length === 8) buscaCEP(d);
        });
      }

      function formatBRL(num){ return num.toLocaleString('pt-BR',{style:'currency',currency:'BRL'}); }
      function parseBRL(str){ if(!str) return 0; return Number(str.replace(/[R$\.\s]/g,'').replace(',','.'))||0; }
      function openDlg(){ if (typeof dlg.showModal==='function') dlg.showModal(); else dlg.setAttribute('open','open'); }
      function closeDlg(){ if (typeof dlg.close==='function') dlg.close(); else dlg.removeAttribute('open'); }

      btnTempo && btnTempo.addEventListener('click', openDlg);
      closeBtn && closeBtn.addEventListener('click', closeDlg);
      cancelBtn && cancelBtn.addEventListener('click', closeDlg);
      dlg && dlg.addEventListener('cancel', function(e){ e.preventDefault(); closeDlg(); });

      // alterna exibição do formulário conforme tipo
      dlg && dlg.addEventListener('change', function(ev){
        if (ev.target && ev.target.name === 'tipoEnvio') {
          enderecoForm.style.display = ev.target.value === 'ENTREGA' ? 'block' : 'none';
        }
      });

      function getEnderecoDigitado(){
        const cep = (document.getElementById('cepInput').value||'').trim();
        const rua = (document.getElementById('ruaInput').value||'').trim();
        const num = (document.getElementById('numeroInput').value||'').trim();
        const bairro = (document.getElementById('bairroInput').value||'').trim();
        const cidade = (document.getElementById('cidadeInput').value||'').trim();
        const uf = (document.getElementById('ufInput').value||'').trim().toUpperCase();
        const comp = (document.getElementById('compInput').value||'').trim();
        if (!rua || !num || !bairro || !cidade || !uf) return null;
        let s = rua + ', ' + num + ' - ' + bairro + ', ' + cidade + ' - ' + uf;
        if (cep) s = cep + ' • ' + s;
        if (comp) s += ' (' + comp + ')';
        return s;
      }

      function atualizaTotais(){
        const subtotal = parseBRL(subtotalEl.textContent);
        const frete = 0; // frete grátis, origem loja
        freteEl.textContent = formatBRL(frete);
        totalEl.textContent = formatBRL(subtotal + frete);
      }

      saveBtn && saveBtn.addEventListener('click', function(){
        const sel = dlg.querySelector('input[name="tipoEnvio"]:checked');
        const tipo = sel ? sel.value : 'ENTREGA';
        let textoResumo = '';
        if (tipo === 'ENTREGA') {
          const end = getEnderecoDigitado();
          if (!end) { alert('Preencha endereço completo: Endereço, Número, Bairro, Cidade e UF.'); return; }
          textoResumo = 'Entrega para: ' + end;
          // frete grátis; origem: endereço da loja
        } else {
          textoResumo = 'Retirada na loja: ' + lojaEndereco;
        }
        resumo.textContent = textoResumo;
        try {
          var saved = { tipo: tipo, resumo: textoResumo };
          if (tipo === 'ENTREGA') {
            saved.cep = digitsOnly(cepEl.value||'');
            saved.rua = (ruaEl.value||'').trim();
            saved.numero = (numeroEl.value||'').trim();
            saved.bairro = (bairroEl.value||'').trim();
            saved.cidade = (cidadeEl.value||'').trim();
            saved.uf = ((ufEl.value||'').trim()||'').toUpperCase();
            saved.complemento = (compEl.value||'').trim();
          }
          localStorage.setItem('pedidoEntrega', JSON.stringify(saved));
        } catch(e){}
        window.dispatchEvent(new Event('pedidoEntrega:change'));
        atualizaTotais();
        closeDlg();
      });

      // restaura resumo salvo
      try {
        const saved = JSON.parse(localStorage.getItem('pedidoEntrega')||'null');
        if (saved && saved.resumo) resumo.textContent = saved.resumo;
      } catch(e){}
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
        if (isset($_SESSION['id_usuario'])) { $uid = (int)$_SESSION['id_usuario']; }
        elseif (isset($_SESSION['id_cliente'])) { $uid = (int)$_SESSION['id_cliente']; }
        elseif (isset($_SESSION['cliente_id'])) { $uid = (int)$_SESSION['cliente_id']; }
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
      } catch (Throwable $e) {}
      echo json_encode($cli, JSON_UNESCAPED_UNICODE);
    ?>;

    (function(){
      var btn = document.getElementById('btnFinalizarPedido');
      var resumo = document.getElementById('enderecoResumo');

      function getCarrinho(){ try { return JSON.parse(localStorage.getItem('carrinho')||'[]'); } catch(e){ return []; } }
      function temItens(){ return getCarrinho().length > 0; }
      function entregaInformada(){ try { var x = JSON.parse(localStorage.getItem('pedidoEntrega')||'null'); return x && x.tipo; } catch(e){ return false; } }

      function setDisabled(dis){
        if (!btn) return;
        btn.setAttribute('aria-disabled', dis ? 'true' : 'false');
        btn.style.opacity = dis ? '0.6' : '1';
      }

      function isLogged(){
        try { return window.CLIENTE_LOGADO === true || !!localStorage.getItem('clienteId'); }
        catch(e){ return !!window.CLIENTE_LOGADO; }
      }
      function canFinalize(){
        return isLogged() && temItens() && entregaInformada();
      }

      function updateFinalizeState(){ setDisabled(!canFinalize()); }
      window.updateFinalizeState = updateFinalizeState;

      // Utilitário
      function formatBRL(num){ return Number(num||0).toLocaleString('pt-BR',{style:'currency',currency:'BRL'}); }
      function digitsOnly(s){ return (s||'').replace(/\D/g,''); }

      // Modal de confirmação de pedido
      function openConfirmPedido(){
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

        function maskCEP(s){ var d = digitsOnly(s).slice(0,8); return d.length>5 ? d.slice(0,5)+'-'+d.slice(5) : d; }

        // CEP máscara
        if (cepEl && !cepEl._bound){
          cepEl.addEventListener('input', function(){ cepEl.value = maskCEP(cepEl.value); });
          cepEl._bound = true;
        }

        // Alterna tipo envio
        Array.from(document.getElementsByName('confTipoEnvio')).forEach(function(r){
          if (!r._bound){
            r.addEventListener('change', function(){
              var tipo = this.value;
              endWrap.style.display = (tipo==='ENTREGA') ? 'block' : 'none';
              retWrap.style.display = (tipo==='RETIRADA') ? 'block' : 'none';
            });
            r._bound = true;
          }
        });

        // Preenche cliente
        (function(){
          var idLocal = 0;
          try { idLocal = parseInt(localStorage.getItem('clienteId')||'0',10)||0; } catch(e){}

          function fillCliente(d){
            if (!d) return;
            nomeEl.value = ((d.nome||'') + (d.sobrenome ? (' ' + d.sobrenome) : '')).trim();
            telEl.value = d.telefone || '';
          }
          function fetchBySession(){
            return fetch('getCliente.php').then(function(r){ return r.ok ? r.json() : null; });
          }
          function fetchById(id){
            return fetch('getCliente.php?id='+encodeURIComponent(id)).then(function(r){ return r.ok ? r.json() : null; });
          }

          // Pré-preenche a partir dos dados injetados pelo servidor (sessão)
          try {
            if (window.CLIENTE_DADOS) {
              if (!nomeEl.value && window.CLIENTE_DADOS.nome) nomeEl.value = String(window.CLIENTE_DADOS.nome);
              if (!telEl.value && window.CLIENTE_DADOS.telefone) telEl.value = String(window.CLIENTE_DADOS.telefone);
            }
          } catch(e) {}

          // Tenta primeiro por sessão (usuário logado no PHP). Se falhar, tenta pelo localStorage.
          fetchBySession()
            .then(function(d){
              if (d && !d.erro){ fillCliente(d); return; }
              if (idLocal>0){ return fetchById(idLocal).then(fillCliente); }
            })
            .catch(function(){
              if (idLocal>0){ fetchById(idLocal).then(fillCliente).catch(function(){}); }
            });
        })();

        // Preenche itens e total
        (function(){
          var itens = [];
          try { itens = JSON.parse(localStorage.getItem('carrinho')||'[]'); } catch(e){ itens=[]; }
          itensWrap.innerHTML = '';
          var subtotal = 0;
          itens.forEach(function(it){
            var qtd = Number(it.quantidade||0);
            var preco = Number(it.preco||0);
            var isPeso = (String(it.tipo||'').toUpperCase()==='PESO');
            var qtdTxt = isPeso ? (qtd.toFixed(2).replace('.',','))+' Kg' : (qtd+' un');
            var linha = document.createElement('div');
            linha.style.display = 'grid';
            linha.style.gridTemplateColumns = '1fr auto auto';
            linha.style.gap = '8px';
            linha.style.alignItems = 'center';
            linha.innerHTML = '<div>'+ (it.nome||'') + (it.corte_nome? ' — <small>'+it.corte_nome+'</small>':'') + (it.observacao? '<br><small>Obs: '+it.observacao+'</small>':'' ) + '</div>' +
                              '<div style="opacity:.8;">'+qtdTxt+'</div>' +
                              '<div style="font-weight:600;">'+formatBRL(qtd*preco)+'</div>';
            itensWrap.appendChild(linha);
            subtotal += qtd * preco;
          });
          totalEl.textContent = formatBRL(subtotal);
        })();

        // Tipo envio padrão baseado no que foi salvo
        (function(){
          var tipo = 'ENTREGA';
          try { var s = JSON.parse(localStorage.getItem('pedidoEntrega')||'null'); if (s && s.tipo) tipo = s.tipo; } catch(e){}
          var radios = document.getElementsByName('confTipoEnvio');
          Array.from(radios).forEach(function(r){ r.checked = (r.value === tipo); });
          endWrap.style.display = (tipo==='ENTREGA') ? 'block' : 'none';
          retWrap.style.display = (tipo==='RETIRADA') ? 'block' : 'none';

          // Preenche os campos de endereço com o que foi informado em "Calcular tempo de entrega"
          try {
            var saved = JSON.parse(localStorage.getItem('pedidoEntrega')||'null');
            if (saved && saved.tipo === 'ENTREGA') {
              if (cepEl && saved.cep) cepEl.value = maskCEP(String(saved.cep));
              if (ruaEl && saved.rua) ruaEl.value = saved.rua;
              if (numEl && saved.numero) numEl.value = saved.numero;
              if (bairroEl && saved.bairro) bairroEl.value = saved.bairro;
              if (cidadeEl && saved.cidade) cidadeEl.value = saved.cidade;
              if (ufEl && saved.uf) ufEl.value = saved.uf;
              if (compEl && saved.complemento) compEl.value = saved.complemento;
            }
          } catch(e){}
        })();

        function open(){ if (typeof dlg.showModal==='function') dlg.showModal(); else dlg.setAttribute('open','open'); }
        function close(){ if (typeof dlg.close==='function') dlg.close(); else dlg.removeAttribute('open'); }

        if (closeBtn && !closeBtn._bound){ closeBtn.addEventListener('click', close); closeBtn._bound = true; }
        if (cancelBtn && !cancelBtn._bound){ cancelBtn.addEventListener('click', close); cancelBtn._bound = true; }
        if (dlg && !dlg._bound){ dlg.addEventListener('cancel', function(e){ e.preventDefault(); close(); }); dlg._bound = true; }

        if (saveBtn && !saveBtn._bound){
          saveBtn.addEventListener('click', function(){
            // Monta payload
            var tipoEl = Array.from(document.getElementsByName('confTipoEnvio')).find(function(r){ return r.checked; });
            var tipo = tipoEl ? tipoEl.value : 'ENTREGA';
            var horario = (horarioEl && horarioEl.value) ? horarioEl.value : '';

            var itens = [];
            try { itens = JSON.parse(localStorage.getItem('carrinho')||'[]'); } catch(e){}
            if (!Array.isArray(itens) || itens.length===0){ alert('Sua sacola está vazia.'); return; }

            var payloadItens = itens.map(function(it){ return { produto: it.nome, quantidade: it.quantidade, observacao: it.observacao||'' }; });

            var clienteId = 0; try { clienteId = parseInt(localStorage.getItem('clienteId')||'0',10)||0; } catch(e){}

            // Endereço (ENTREGA)
            var enderecoTxt = '';
            var cepTxt = '';
            var numTxt = '';
            var compTxt = '';
            if (tipo === 'ENTREGA'){
              var rua = (ruaEl.value||'').trim();
              var bairro = (bairroEl.value||'').trim();
              var cidade = (cidadeEl.value||'').trim();
              var uf = (ufEl.value||'').trim();
              numTxt = (numEl.value||'').trim();
              cepTxt = digitsOnly(cepEl.value||'');
              compTxt = (compEl.value||'').trim();
              if (!rua || !numTxt || !bairro || !cidade || !uf){
                alert('Preencha o endereço completo para entrega.');
                return;
              }
              enderecoTxt = rua + ', ' + numTxt + ' - ' + bairro + ', ' + cidade + ' - ' + uf;
              if (compTxt) enderecoTxt += ' (' + compTxt + ')';
            }

            var params = new URLSearchParams();
            params.set('cliente_id', String(clienteId));
            params.set('recebimento', tipo);
            if (horario) params.set('horario', horario);
            params.set('itens', JSON.stringify(payloadItens));
            if (tipo === 'ENTREGA'){
              params.set('endereco', enderecoTxt);
              if (cepTxt) params.set('cep', cepTxt);
              if (numTxt) params.set('numero', numTxt);
              if (compTxt) params.set('complemento', compTxt);
            }

            fetch('cadastraPedidoBD.php', { method:'POST', headers:{ 'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8' }, body: params.toString() })
              .then(function(r){ return r.text(); })
              .then(function(txt){
                txt = (txt||'').trim();
                if (txt === 'ok'){
                  alert('Pedido enviado com sucesso!');
                  try { localStorage.removeItem('carrinho'); localStorage.removeItem('pedidoEntrega'); } catch(e){}
                  window.dispatchEvent(new Event('carrinho:change'));
                  try { var r = document.getElementById('enderecoResumo'); if (r) r.textContent = ''; } catch(e){}
                  close();
                  // Recarrega para garantir UI limpa (sacola e totais resetados)
                  location.reload();
                } else {
                  alert('Falha ao enviar o pedido.');
                }
              })
              .catch(function(){ alert('Falha de comunicação com o servidor.'); });
          });
          saveBtn._bound = true;
        }

        open();
      }

      // Eventos que podem mudar o estado
      window.addEventListener('carrinho:change', updateFinalizeState);
      window.addEventListener('pedidoEntrega:change', updateFinalizeState);
      var btnLimpar = document.getElementById('btnLimparCarrinho');
      if (btnLimpar) btnLimpar.addEventListener('click', function(){ setTimeout(function(){ window.dispatchEvent(new Event('carrinho:change')); }, 50); });
      document.addEventListener('DOMContentLoaded', updateFinalizeState);
      // reage a mudanças de login no localStorage
      window.addEventListener('storage', function(e){ if (e.key === 'clienteId') // chama novamente ao final para garantir estado correto
      updateFinalizeState(); });

      // Clique Finalizar
      if (btn) btn.addEventListener('click', function(){
        if (!isLogged()){
          alert('Faça login para finalizar seu pedido.');
          window.location.href = 'login.php';
          return;
        }
        if (!temItens()){
          alert('Adicione pelo menos um item à sacola antes de finalizar.');
          return;
        }
        if (!entregaInformada()){
          alert('Informe a forma de receber antes de finalizar o pedido.');
          var d = document.getElementById('entregaDialog');
          if (d){ if (typeof d.showModal==='function') d.showModal(); else d.setAttribute('open','open'); }
          return;
        }
        // Tudo OK: abrir modal de confirmação
        openConfirmPedido();
      });
      if (btn) btn.addEventListener('keydown', function(e){ if ((e.key==='Enter'||e.key===' ') && btn.getAttribute('aria-disabled')!=='true'){ btn.click(); }});

      updateFinalizeState();
    })();
  </script>
</body>

</html>