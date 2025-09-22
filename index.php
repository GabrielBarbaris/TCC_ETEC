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
            <div class="div-wrapper">
              <div class="text-wrapper-27">FINALIZAR PEDIDO</div>
            </div>
          </div>
          <div class="text-wrapper-28">Sua sacola</div>
          <div class="text-wrapper-29">Limpar</div>
          <div class="text-wrapper-30">Calcular tempo de entrega</div>
          <img class="line-7" src="img/Line7.png" />
          <img class="vector" src="img/localizacao.png" />
          <div class="item-contra">
            <div class="overlap-9">
              <div class="rectangle-6"></div>
              <div class="text-wrapper-31">Editar</div>
              <div class="text-wrapper-32">Remover</div>
              <div class="text-wrapper-33">Contra file</div>
              <div class="text-wrapper-34">RS$90,00</div>
              <div class="text-wrapper-35">Peso:</div>
              <div class="text-wrapper-36">Tipo:</div>
              <p class="observa-o">
                <span class="text-wrapper-37">Observação:</span> <span class="text-wrapper-38">&nbsp;</span>
              </p>
              <div class="text-wrapper-39">dividir em 2 pacotes</div>
              <div class="text-wrapper-40">manta</div>
              <div class="text-wrapper-41">2 Kg</div>
              <img class="contra-file-2" src="img/contraFile.png" />
            </div>
          </div>
          <div class="text-wrapper-42">Subtotal:</div>
          <div class="text-wrapper-43">R$135,00</div>
          <div class="text-wrapper-44">R$5,00</div>
          <div class="text-wrapper-45">Frete:</div>
          <p class="total"><span class="text-wrapper-46">Total</span> <span class="text-wrapper-47">:</span></p>
          <div class="text-wrapper-48">R$140,00</div>
          <div class="item-contra-2">
            <div class="overlap-9">
              <div class="rectangle-6"></div>
              <div class="text-wrapper-31">Editar</div>
              <div class="text-wrapper-32">Remover</div>
              <div class="text-wrapper-33">Fraldinha</div>
              <div class="text-wrapper-34">RS$44,99</div>
              <div class="text-wrapper-35">Peso:</div>
              <div class="text-wrapper-36">Tipo:</div>
              <p class="observa-o">
                <span class="text-wrapper-37">Observação:</span> <span class="text-wrapper-38">&nbsp;</span>
              </p>
              <div class="text-wrapper-40">manta</div>
              <div class="text-wrapper-41">1Kg</div>
              <img class="fraldinha-2" src="img/fraldinha.png" />

            </div>
            <img class="line-8" src="img/line-8.svg" />
          </div>
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
         MODAL / DIALOG "ADICIONAR"
         - Mantive todas as classes (id="adicionar" e .produto)
         - Use JS para abrir: document.getElementById('adicionar').showModal()
         - Conteúdo foi mantido idêntico — as várias .rectangle-* e .ellipse-* são elementos de estilo.
         ============================= -->
    <dialog id="adicionar">
      <div class="produto">
        <div class="cor-de-fundo"></div>
        <div class="rectangle-54"></div>
        <div class="rectangle-55"></div>
        <img class="fraldinha_ped" src="img/fraldinha.png" alt="Fraldinha" />
        <div class="fraldinha2">FRALDINHA</div>

        <div class="rectangle-57"></div>
        <div class="rectangle-64"></div>
        <div class="rectangle-56"></div>
        <div class="rectangle-58"></div>

        <div class="div">+</div>
        <div class="div2">-</div>
        <div class="_500-g">500g</div>
        <div class="rectangle-59"></div>
        <div class="adicionar">Adicionar</div>
        <div class="r-22-50">R$22,50</div>
        <div class="rectangle-60"></div>

        <div class="observa-o2">observação:</div>
        <div class="_0-150">0/150</div>
        <div class="bife">bife</div>
        <img class="ellipse-3" src="." alt="" />
        <div class="rectangle-61"></div>
        <div class="inteiro">Inteiro</div>
        <img class="ellipse-32" src="ellipse-31.svg" alt="" />
        <div class="rectangle-612"></div>
        <div class="manta">Manta</div>
        <img class="ellipse-33" src="ellipse-32.svg" alt="" />
        <div class="rectangle-613"></div>

        <div class="como-sera-cortada-a-carne">Como sera cortada a carne?</div>
        <div class="r-43-99">R$43,99</div>
        <div class="o-pre-o-e-o-peso-pode-ter-uma-pequena-vari-o-podendo-ter-100-g-de-diferen-as">
          o preço e o peso pode ter uma pequena varição podendo ter 100g de diferenças
        </div>

        <div class="escolha-1-op-o">Escolha 1 opção</div>
        <div
          class="esta-uma-carne-otima-para-vc-assar-na-sua-casa-mesclando-muito-bem-a-macies-e-o-sabor-tendo-uma-camada-de-gordura-otima-e-muito-saborosa">
          Esta é uma carne otima para vc assar na sua casa mesclando muito bem a macies e o sabor.
        </div>

        <div class="rectangle-62"></div>
        <div class="obrigatorio2">OBRIGATORIO</div>
        <div class="rectangle-63"></div>
        <div class="_0-12">0/1</div>
      </div>
    </dialog>

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
    <div id="produtoView" hidden>
      <img id="prodImagem" class="produto-img" src="" alt="Produto" />
      <section class="produto-right">
        <h1 id="prodNome">Produto</h1>
        <div id="precoKG">R$0,00 / Kg</div>
        <p id="prodDesc"></p>

        <div class="qty" aria-label="Controle de quantidade">
          <button id="btnMenos" type="button" title="Diminuir">-</button>
          <div id="pesoAtual" class="qtd-display" aria-live="polite">0</div>
          <button id="btnMais" type="button" title="Aumentar">+</button>
        </div>

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

        <div class="footer">
          <div class="total">Total: <strong id="precoAtual">R$0,00</strong></div>
          <div id="qtdResumo" aria-live="polite"></div>
          <button id="btnAdicionar" type="button" class="btn-primary">Adicionar</button>
        </div>
      </section>
    </div>
  </dialog>

  <style>
    /* Centraliza o dialog e escurece o fundo */
    #produtoDialog::backdrop { background: rgba(0,0,0,.45); }

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
      #produtoView { padding: 16px; }
      #prodNome { font-size: 22px; }
      #closeProdutoDialog { top: 8px; right: 8px; padding: 6px 10px; }
    }
  </style>

  <script>
    (function(){
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

      function formatBRL(v){ return v.toLocaleString('pt-BR', {style:'currency', currency:'BRL'}); }
      function renderPesoQtd(){
        if (!produto) return;
        const txt = produto.tipo_quantidade === 'PESO' ? (quantidade.toFixed(2).replace('.', ',')) + ' Kg' : (quantidade + ' un');
        pesoAtual.textContent = txt;
        if (qtdResumo) qtdResumo.textContent = txt;
      }
      function renderPreco(){ if (!produto) return; const p = quantidade * produto.preco; precoAtual.textContent = formatBRL(p); precoKG.textContent = formatBRL(produto.preco) + (produto.tipo_quantidade === 'PESO' ? ' / Kg' : ' / Un'); }
      function setQtd(q){ if (!produto) return; const min = produto.tipo_quantidade === 'PESO' ? produto.peso_minimo : 1; const step = produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1; q = Math.max(min, Math.round(q/step)*step); quantidade = parseFloat(q.toFixed(2)); renderPesoQtd(); renderPreco(); }

      function criaRadioCorte(item){
        const label = document.createElement('label');
        label.className = 'radio-item';
        const input = document.createElement('input');
        input.type = 'radio'; input.name = 'corte'; input.value = String(item.id);
        input.addEventListener('change', () => { if (input.checked){ corteSelecionado = item.id; corteCount.textContent = '1/1'; }});
        const span = document.createElement('span'); span.textContent = item.nome;
        label.appendChild(input); label.appendChild(span);
        return label;
      }

      function resetProdutoUI(){
        produto = null; corteSelecionado = null; quantidade = 0;
        nome.textContent = 'Produto'; precoKG.textContent = 'R$0,00'; precoAtual.textContent = 'R$0,00'; pesoAtual.textContent = '0';
        if (qtdResumo) qtdResumo.textContent = '';
        img.src = ''; document.getElementById('prodDesc').textContent = '';
        cortesWrap.innerHTML = ''; corteCount.textContent = '0/1'; obs.value=''; obsCount.textContent = '0/150';
        view.hidden = true;
      }

      function openProduto(id){
        resetProdutoUI();
        if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open','open');
        fetch('produto_detalhe.php?id=' + encodeURIComponent(id))
          .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
          .then(data => {
            produto = data.produto; nome.textContent = produto.nome; img.src = produto.imagem_url || 'img/imagensIlustrativa.jpg';
            document.getElementById('prodDesc').textContent = (produto.descricao || '').trim();
            // Cortes
            cortesWrap.innerHTML = '';
            const lista = data.cortes || [];
            if (lista.length > 0) { lista.forEach(c => cortesWrap.appendChild(criaRadioCorte(c))); corteCount.textContent = '0/1'; }
            else { corteCount.textContent = '0/0'; }
            // Quantidade
            setQtd(produto.tipo_quantidade === 'PESO' ? produto.peso_minimo : 1);
            view.hidden = false;
          })
          .catch(() => { view.hidden = false; nome.textContent = 'Erro ao carregar'; });
      }

      function closeProduto(){ if (typeof dlg.close === 'function') dlg.close(); else dlg.removeAttribute('open'); resetProdutoUI(); }

      // Eventos UI
      obs.addEventListener('input', () => { obsCount.textContent = obs.value.length + '/150'; });
      btnMais.addEventListener('click', () => { const step = produto && produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1; setQtd(quantidade + step); });
      btnMenos.addEventListener('click', () => { const step = produto && produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1; setQtd(quantidade - step); });
      btnAdicionar.addEventListener('click', () => {
        if (!produto) return; if (produto.tipo_quantidade === 'PESO' && cortesWrap.children.length > 0 && !corteSelecionado){ alert('Selecione um corte'); return; }
        const item = { id: produto.id, nome: produto.nome, preco: produto.preco, tipo: produto.tipo_quantidade, quantidade, corte: corteSelecionado || null, observacao: obs.value || '' };
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]'); carrinho.push(item); localStorage.setItem('carrinho', JSON.stringify(carrinho));
        closeProduto();
      });

      // Delegação: abre ao clicar em ADICIONAR
      document.addEventListener('click', function(e){ const el = e.target.closest('.btn-add-prod'); if (el && el.dataset && el.dataset.prodId) { openProduto(el.dataset.prodId); } });

      closeBtn && closeBtn.addEventListener('click', closeProduto);
      dlg && dlg.addEventListener('cancel', function(ev){ ev.preventDefault(); closeProduto(); });
    })();
  </script>
</body>

</html>