// JS da página inicial + carrinho da sacola
(function() {
  'use strict';

  // Utilitários
  function formatBRL(v) {
    v = Number(v || 0);
    return v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
  }
  function getCarrinho() {
    try {
      return JSON.parse(localStorage.getItem('carrinho') || '[]');
    } catch (e) {
      return [];
    }
  }
  function setCarrinho(itens) {
    localStorage.setItem('carrinho', JSON.stringify(itens || []));
  }

  // Busca detalhes do produto para enriquecer itens do carrinho (imagem e nome do corte)
  function enrichItem(index, item) {
    if (!item || !item.id) return;
    if (item._enriching) return; // evita repetição simultânea
    item._enriching = true;
    fetch('produto_detalhe.php?id=' + encodeURIComponent(item.id))
      .then(r => (r && r.ok) ? r.json() : null)
      .then(data => {
        if (!data || !data.produto) return;
        const itens = getCarrinho();
        const it = itens[index];
        if (!it) return;
        if (!it.imagem_url && data.produto.imagem_url) {
          it.imagem_url = data.produto.imagem_url;
        }
        if (it.corte && !it.corte_nome && Array.isArray(data.cortes)) {
          const c = data.cortes.find(x => String(x.id) === String(it.corte));
          if (c) it.corte_nome = c.nome;
        }
        delete it._enriching;
        setCarrinho(itens);
        setTimeout(renderCarrinho, 0);
      })
      .catch(() => {
        try {
          const itens = getCarrinho();
          const it = itens[index];
          if (it) delete it._enriching;
        } catch(e) {}
      });
  }

  // Elementos (definidos quando o DOM estiver pronto)
  let carrinhoLista, subtotalEl, freteEl, totalEl, btnLimpar;

  function ensureCarrinhoLayout() {
    // Ajusta o container da lista para comportar múltiplos itens sem sobrepor
    if (!carrinhoLista) return;
    carrinhoLista.style.position = 'absolute';
    carrinhoLista.style.left = '16px';
    carrinhoLista.style.top = '107px';
    carrinhoLista.style.width = '360px';
    carrinhoLista.style.maxHeight = '380px';
    carrinhoLista.style.overflowY = 'auto';
    carrinhoLista.style.paddingRight = '4px';
  }

  function renderCarrinho() {
    if (!carrinhoLista || !subtotalEl || !freteEl || !totalEl) return;

    const itens = getCarrinho();
    carrinhoLista.innerHTML = '';

    let subtotal = 0;

    itens.forEach((item, idx) => {
      const qtd = Number(item.quantidade || 0);
      const preco = Number(item.preco || 0);
      const itemTotal = preco * qtd;
      subtotal += itemTotal;

      const isPeso = (item.tipo || '').toUpperCase() === 'PESO';
      const qtdTxt = isPeso ? (qtd.toFixed(2).replace('.', ',')) + ' Kg' : (qtd + ' un');
      const tipoTxt = item.corte_nome ? String(item.corte_nome) : '—';
      const imgUrl = (item.imagem_url && String(item.imagem_url).trim()) ? item.imagem_url : 'img/imagensIlustrativa.jpg';

      const wrap = document.createElement('div');
      wrap.className = 'item-contra';
      wrap.dataset.index = String(idx);
      // Override posicionamento absoluto do CSS original
      wrap.style.position = 'relative';
      wrap.style.width = '360px';
      wrap.style.height = '132px';
      wrap.style.marginBottom = '12px';

      wrap.innerHTML = `
        <div class="overlap-9">
          <div class="rectangle-6"></div>
          <div class="text-wrapper-31 btn-editar" style="cursor:pointer;">Editar</div>
          <div class="text-wrapper-32 btn-remover" style="cursor:pointer;">Remover</div>
          <div class="text-wrapper-33">${(item.nome || '').toString()}</div>
          <div class="text-wrapper-34">${formatBRL(itemTotal)}</div>
          <div class="text-wrapper-35">Peso:</div>
          <div class="text-wrapper-36">Tipo:</div>
          <p class="observa-o">
            <span class="text-wrapper-37">Observação:</span> <span class="text-wrapper-38">&nbsp;</span>
          </p>
          <div class="text-wrapper-39">${(item.observacao || '').toString()}</div>
          <div class="text-wrapper-40">${tipoTxt}</div>
          <div class="text-wrapper-41">${qtdTxt}</div>
          <img class="contra-file-2" src="${imgUrl}" alt="Produto" />
        </div>
      `;

      carrinhoLista.appendChild(wrap);
    });

    const frete = 0; // Regra de frete pode ser implementada depois
    subtotalEl.textContent = formatBRL(subtotal);
    freteEl.textContent = formatBRL(frete);
    totalEl.textContent = formatBRL(subtotal + frete);
  }

  function bindCarrinhoEvents() {
    if (btnLimpar) {
      btnLimpar.addEventListener('click', () => {
        setCarrinho([]);
        renderCarrinho();
      });
    }

    if (carrinhoLista) {
      carrinhoLista.addEventListener('click', (e) => {
        const remover = e.target.closest && e.target.closest('.btn-remover');
        if (remover) {
          const itemEl = remover.closest('.item-contra');
          if (!itemEl) return;
          const idx = Number(itemEl.dataset.index);
          const itens = getCarrinho();
          if (idx >= 0 && idx < itens.length) {
            itens.splice(idx, 1);
            setCarrinho(itens);
            renderCarrinho();
          }
          return;
        }
        const editar = e.target.closest && e.target.closest('.btn-editar');
        if (editar) {
          const itemEl = editar.closest('.item-contra');
          if (!itemEl) return;
          const idx = Number(itemEl.dataset.index);
          const itens = getCarrinho();
          const it = itens[idx];
          if (!it) return;
          if (typeof window.openProduto === 'function') {
            const produtoPayload = {
              id: it.id,
              nome: it.nome,
              preco: it.preco,
              tipo_quantidade: it.tipo,
              imagem_url: it.imagem_url,
              // Defaults seguros caso não existam no item
              peso_minimo: (it.tipo && it.tipo.toUpperCase() === 'PESO') ? (Number(it.peso_minimo || 0.5)) : 1,
              intervalo_peso: (it.tipo && it.tipo.toUpperCase() === 'PESO') ? (Number(it.intervalo_peso || 0.5)) : 1,
              descricao: it.descricao || ''
            };
            const cortesPayload = (it.corte && it.corte_nome) ? [{ id: it.corte, nome: it.corte_nome }] : [];
            window.openProduto(it.id, {
              editIndex: idx,
              quantidade: it.quantidade,
              corte: it.corte,
              observacao: it.observacao,
              produto: produtoPayload,
              cortes: cortesPayload
            });
          } else {
            alert('Não foi possível abrir a edição.');
          }
        }
      });
    }

    // Re-render quando o modal adiciona produto
    const btnAdicionar = document.getElementById('btnAdicionar');
    if (btnAdicionar) {
      btnAdicionar.addEventListener('click', function() {
        // O handler do modal grava no localStorage. Aguarda a gravação ocorrer.
        setTimeout(renderCarrinho, 0);
      });
    }

    // Atualiza se outro contexto alterar o localStorage
    window.addEventListener('storage', (ev) => {
      if (ev.key === 'carrinho') renderCarrinho();
    });
  }

  // Inicialização
  document.addEventListener('DOMContentLoaded', () => {
    // Elementos da sacola
    carrinhoLista = document.getElementById('carrinhoLista');
    subtotalEl = document.getElementById('subtotalValor');
    freteEl = document.getElementById('freteValor');
    totalEl = document.getElementById('totalValor');
    btnLimpar = document.getElementById('btnLimparCarrinho');

    ensureCarrinhoLayout();
    bindCarrinhoEvents();
    renderCarrinho();

    // Código legado: abre modais se existirem (evita erros se não houver)
    const firstButton = document.querySelector('button');
    const modal = document.querySelector('#adicionar');
    const login_modal = document.querySelector('#login');

    if (firstButton && modal && typeof modal.showModal === 'function') {
      firstButton.onclick = function() { modal.showModal(); };
    }
    window.cadastrar_cliente = function() {
      if (login_modal && typeof login_modal.showModal === 'function') {
        login_modal.showModal();
      } else {
        // fallback para link padrão, não faz nada especial
      }
    };
  });
// Busca: redireciona para categorias.php ao pressionar Enter
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('searchInput');
    if (!input) return;

    input.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        var q = input.value.trim();
        if (q) {
          e.preventDefault();
          window.location.href = 'categorias.php?busca=' + encodeURIComponent(q);
        }
      }
    });
  });

})();
