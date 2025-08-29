<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/index.css" />
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
                          <div class='boto'>
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

  <script src="./js/botoesAdicionar.js"></script>
</body>

</html>