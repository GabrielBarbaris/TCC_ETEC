<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/telaProduto.css" />
  <title>Produto</title>
</head>
<body>

  <main id="produtoView" hidden>
    <!-- Imagem à esquerda -->
    <img id="prodImagem" class="produto-img" src="" alt="Produto" />

    <!-- Detalhes à direita -->
    <section class="produto-right">
      <h1 id="prodNome">Produto</h1>
      <div id="precoKG">R$0,00 / Kg</div>
      <p id="prodDesc"></p>

      <!-- Quantidade -->
      <div class="qty" aria-label="Controle de quantidade">
        <button id="btnMenos" type="button" title="Diminuir">-</button>
        <div id="pesoAtual" class="qtd-display" aria-live="polite">0</div>
        <button id="btnMais" type="button" title="Aumentar">+</button>
      </div>

      <!-- Cortes (radio) gerados no servidor com base em tbQuantidadeCorte -->
      <fieldset class="section">
        <legend class="legend">Como será cortada a carne?</legend>
        <div class="muted" style="display:flex; gap:8px; align-items:center;">
          <span>Escolha 1 opção</span>
          <span id="corteCount">0/1</span>
        </div>
        <div id="cortes" class="radio-group" role="radiogroup" aria-label="Opções de corte">
          <?php
            require_once 'conexao.php';
            $idProd = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $temCortes = false;
            if ($idProd > 0) {
              $stmt = $conn->prepare('SELECT c.id_corte, c.nome_corte FROM tbQuantidadeCorte qc JOIN tbcorte c ON c.id_corte = qc.cod_corte WHERE qc.cod_produto = ? ORDER BY c.nome_corte');
              if ($stmt) {
                $stmt->bind_param('i', $idProd);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                  $temCortes = true;
                  $id = (int)$row['id_corte'];
                  $nome = htmlspecialchars($row['nome_corte'], ENT_QUOTES, 'UTF-8');
                  echo "<label class='radio-item'>\n                          <input type='radio' name='corte' value='$id' />\n                          <span>$nome</span>\n                        </label>";
                }
                $stmt->close();
              }
            }
            if (!$temCortes) {
              echo "<p class='muted'>Não há cortes específicos para este produto.</p>";
            }
          ?>
        </div>
      </fieldset>

      <!-- Observação -->
      <section class="section obs-box">
        <div class="legend">Observação</div>
        <textarea id="obsText" maxlength="150" placeholder="Ex.: separar em 2 pacotes, ponto da carne, etc."></textarea>
        <div class="obs-footer">
          <span></span>
          <span id="obsCount">0/150</span>
        </div>
      </section>

      <!-- Total e Ação -->
      <div class="footer">
        <div class="total">Total: <strong id="precoAtual">R$0,00</strong></div>
        <button id="btnAdicionar" type="button" class="btn-primary">Adicionar</button>
      </div>
    </section>
  </main>

  <div id="erro" style="padding:24px; font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;"></div>

  <script>
  (function(){
    const qs = new URLSearchParams(location.search);
    const id = parseInt(qs.get('id'), 10);
    const view = document.getElementById('produtoView');
    const elErro = document.getElementById('erro');

    if (!id || id <= 0) {
      elErro.textContent = 'ID do produto inválido. Abra a página como TelaProduto.php?id=1';
      return;
    }

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

    let produto = null;
    let corteSelecionado = null;
    let quantidade = 0; // KG quando PESO, ou UNIDADES quando UNIDADE

    function formatBRL(v){
      return v.toLocaleString('pt-BR', {style:'currency', currency:'BRL'});
    }

    function renderPesoQtd(){
      if (!produto) return;
      if (produto.tipo_quantidade === 'PESO') {
        pesoAtual.textContent = (quantidade.toFixed(2).replace('.', ',')) + ' Kg';
      } else {
        pesoAtual.textContent = quantidade + ' un';
      }
    }

    function renderPreco(){
      if (!produto) return;
      const preco = quantidade * produto.preco;
      precoAtual.textContent = formatBRL(preco);
      precoKG.textContent = formatBRL(produto.preco) + (produto.tipo_quantidade === 'PESO' ? ' / Kg' : ' / Un');
    }

    function setQtd(q){
      if (!produto) return;
      const min = produto.tipo_quantidade === 'PESO' ? produto.peso_minimo : 1;
      const step = produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1;
      // arredonda para o step e limita ao mínimo
      q = Math.max(min, Math.round(q / step) * step);
      quantidade = parseFloat(q.toFixed(2));
      renderPesoQtd();
      renderPreco();
    }

    function wireCorteRadios(){
      const radios = cortesWrap.querySelectorAll('input[name="corte"]');
      if (!radios || radios.length === 0) {
        corteCount.textContent = '0/0';
        return;
      }
      corteCount.textContent = '0/1';
      radios.forEach(r => {
        r.addEventListener('change', () => {
          if (r.checked) {
            corteSelecionado = parseInt(r.value, 10);
            corteCount.textContent = '1/1';
          }
        });
      });
    }

    obs.addEventListener('input', () => {
      obsCount.textContent = obs.value.length + '/150';
    });

    btnMais.addEventListener('click', () => {
      const step = produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1;
      setQtd(quantidade + step);
    });
    btnMenos.addEventListener('click', () => {
      const step = produto.tipo_quantidade === 'PESO' ? produto.intervalo_peso : 1;
      setQtd(quantidade - step);
    });

    btnAdicionar.addEventListener('click', () => {
      if (!produto) return;
      if (produto.tipo_quantidade === 'PESO' && cortesWrap.children.length > 0 && !corteSelecionado) {
        alert('Selecione um corte');
        return;
      }
      const item = {
        id: produto.id,
        nome: produto.nome,
        preco: produto.preco,
        tipo: produto.tipo_quantidade,
        quantidade: quantidade,
        corte: corteSelecionado || null,
        observacao: obs.value || ''
      };
      const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
      carrinho.push(item);
      localStorage.setItem('carrinho', JSON.stringify(carrinho));
      alert('Adicionado à sacola');
    });

    // Rádio já está renderizado pelo servidor; fazer o wire-up agora
    wireCorteRadios();

    // Buscar detalhes do produto (nome, imagem, preço, descrição)
    fetch('produto_detalhe.php?id=' + id)
      .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
      .then(data => {
        produto = data.produto;
        nome.textContent = produto.nome;
        img.src = produto.imagem_url || 'img/imagensIlustrativa.jpg';
        document.title = produto.nome + ' - Produto';

        // Descrição
        const desc = (produto.descricao || '').trim();
        document.getElementById('prodDesc').textContent = desc;

        // Quantidade inicial = mínimo
        setQtd(produto.tipo_quantidade === 'PESO' ? produto.peso_minimo : 1);

        view.hidden = false;
      })
      .catch(err => {
        console.error(err);
        elErro.textContent = 'Falha ao carregar produto.';
      });
  })();
  </script>
</body>
</html>