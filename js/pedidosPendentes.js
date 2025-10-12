function toggleMais(btn){
  const card = btn.closest('.pedido');
  card.classList.toggle('open');
  btn.textContent = card.classList.contains('open') ? 'recolher' : 'saiba mais';
}

async function finalizarPedido(btn){
  const card = btn.closest('.pedido');
  const id = card.getAttribute('data-id');
  if (!id) return;

  btn.disabled = true;
  const originalText = btn.textContent;
  btn.textContent = 'Finalizando...';

  try{
    const resp = await fetch('finalizaPedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
      body: new URLSearchParams({ id })
    });
    const data = await resp.json();

    if (data && data.success){
      marcarComoPronto(card, btn);
    } else {
      // Se o servidor informou que já estava pronto, também marcamos localmente
      if (data && (data.message || '').toLowerCase().includes('já estava pronto')){
        marcarComoPronto(card, btn);
      } else {
        throw new Error((data && (data.error || data.message)) || 'Falha ao finalizar');
      }
    }
  } catch (e){
    alert('Erro ao finalizar o pedido: ' + e.message);
    btn.disabled = false;
    btn.textContent = originalText;
    return;
  }
}

function marcarComoPronto(card, btn){
  const badge = card.querySelector('.status-badge');
  if (badge){
    badge.textContent = 'Pronto';
    badge.classList.remove('status-pendente');
    badge.classList.add('status-pronto');
  }
  if (btn){
    btn.textContent = 'Pronto';
    btn.disabled = true;
  }
  card.classList.remove('is-pendente');
  card.classList.add('is-pronto');
  card.setAttribute('data-status', 'PRONTO');
  // injeta os botões de ações do status PRONTO
  ensureEntregarButton(card);
  ensurePendenteButton(card);
  // re-aplica filtro atual, se existir
  try { if (window.__activeFilter) aplicarFiltro(window.__activeFilter); } catch(e){}
}

function removerCard(card){
  card.style.transition = 'opacity .2s ease, transform .2s ease';
  card.style.opacity = '0';
  card.style.transform = 'scale(0.98)';
  setTimeout(() => card.remove(), 220);
}

// Injeta o botão "Marcar como Entregue" quando o pedido está PRONTO
function ensureEntregarButton(card){
  const actions = card.querySelector('.actions');
  if (!actions) return;
  let btnEntregar = actions.querySelector('[data-action="entregar"]');
  if (btnEntregar) return;
  btnEntregar = document.createElement('button');
  btnEntregar.className = 'btn';
  btnEntregar.setAttribute('data-action', 'entregar');
  btnEntregar.textContent = 'Marcar como Entregue';
  btnEntregar.addEventListener('click', async function(){ await entregarPedido(btnEntregar); });
  actions.appendChild(btnEntregar);
}

// Injeta o botão "Deixar Pendente" quando o pedido está PRONTO
function ensurePendenteButton(card){
  const actions = card.querySelector('.actions');
  if (!actions) return;
  let btnVoltar = actions.querySelector('[data-action="voltar-pendente"]');
  if (btnVoltar) return;
  btnVoltar = document.createElement('button');
  btnVoltar.className = 'btn secondary';
  btnVoltar.setAttribute('data-action', 'voltar-pendente');
  btnVoltar.textContent = 'Deixar Pendente';
  btnVoltar.addEventListener('click', async function(){ await deixarPendente(btnVoltar); });
  actions.appendChild(btnVoltar);
}

async function deixarPendente(btn){
  const card = btn.closest('.pedido');
  const id = card.getAttribute('data-id');
  if (!id) return;
  btn.disabled = true;
  const original = btn.textContent;
  btn.textContent = 'Reabrindo...';
  try{
    const resp = await fetch('finalizaPedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
      body: new URLSearchParams({ id, acao: 'PENDENTE' })
    });
    const data = await resp.json();
    if (data && data.success && String(data.status || '').toUpperCase() === 'PENDENTE'){
      marcarComoPendente(card);
    } else {
      const msg = (data && (data.message || data.error)) || 'Falha ao reabrir';
      if (String(msg || '').toLowerCase().includes('pendente')){
        marcarComoPendente(card);
      } else {
        throw new Error(msg);
      }
    }
  } catch(e){
    alert('Erro ao reabrir o pedido: ' + e.message);
    btn.disabled = false;
    btn.textContent = original;
  }
}

function marcarComoPendente(card){
  const badge = card.querySelector('.status-badge');
  if (badge){
    badge.textContent = 'Pendente';
    badge.classList.remove('status-pronto');
    badge.classList.add('status-pendente');
  }
  // Habilita o botão principal de finalizar
  const btnFinal = card.querySelector('.actions .btn:not(.secondary):not([data-action])');
  if (btnFinal){ btnFinal.textContent = 'Finalizar'; btnFinal.disabled = false; }
  // Remove botões específicos de PRONTO
  const btnEntregar = card.querySelector('[data-action="entregar"]');
  if (btnEntregar) btnEntregar.remove();
  const btnVoltar = card.querySelector('[data-action="voltar-pendente"]');
  if (btnVoltar) btnVoltar.remove();
  card.classList.remove('is-pronto');
  card.classList.remove('is-entregue');
  card.classList.add('is-pendente');
  card.setAttribute('data-status', 'PENDENTE');
  try { if (window.__activeFilter) aplicarFiltro(window.__activeFilter); } catch(e){}
}

async function entregarPedido(btn){
  const card = btn.closest('.pedido');
  const id = card.getAttribute('data-id');
  if (!id) return;
  btn.disabled = true;
  const original = btn.textContent;
  btn.textContent = 'Entregando...';
  try{
    const resp = await fetch('finalizaPedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
      body: new URLSearchParams({ id })
    });
    const data = await resp.json();
    if (data && data.success && String(data.status || '').toUpperCase() === 'ENTREGUE'){
      marcarComoEntregue(card, btn);
    } else {
      const msg = (data && (data.message || data.error)) || 'Falha ao entregar';
      if (msg.toLowerCase().includes('entregue')){
        marcarComoEntregue(card, btn);
      } else {
        throw new Error(msg);
      }
    }
  } catch(e){
    alert('Erro ao marcar como entregue: ' + e.message);
    btn.disabled = false;
    btn.textContent = original;
  }
}

function marcarComoEntregue(card, btn){
  const badge = card.querySelector('.status-badge');
  if (badge){
    badge.textContent = 'Entregue';
    badge.classList.remove('status-pendente');
    badge.classList.add('status-pronto');
  }
  if (btn){
    btn.textContent = 'Entregue';
    btn.disabled = true;
  }
  // também desabilita o botão original de finalizar, se existir
  const btnFinal = card.querySelector('.actions .btn:not(.secondary):not([data-action])');
  if (btnFinal) { btnFinal.disabled = true; btnFinal.textContent = 'Entregue'; }
  // remove botões de ações de PRONTO
  const btnEntregar = card.querySelector('[data-action="entregar"]');
  if (btnEntregar) btnEntregar.remove();
  const btnVoltar = card.querySelector('[data-action="voltar-pendente"]');
  if (btnVoltar) btnVoltar.remove();

  card.classList.remove('is-pendente');
  card.classList.remove('is-pronto');
  card.classList.add('is-entregue');
  card.setAttribute('data-status', 'ENTREGUE');
  try { if (window.__activeFilter) aplicarFiltro(window.__activeFilter); } catch(e){}
}

// Filtros de pedidos
function aplicarFiltro(filtro){
  window.__activeFilter = filtro; // memoriza filtro atual
  const cards = document.querySelectorAll('.lista-pedidos .pedido');
  cards.forEach(card => {
    const st = (card.getAttribute('data-status') || '').toUpperCase();
    let vis = true;
    if (filtro && filtro !== 'ALL') {
      vis = (st === filtro);
    }
    card.style.display = vis ? '' : 'none';
  });
}

function inicializarFiltros(){
  const barra = document.querySelector('.filtros-pedidos');
  if (!barra) return;
  const botoes = barra.querySelectorAll('.filtro-btn');
  botoes.forEach(btn => {
    btn.addEventListener('click', () => {
      botoes.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filtro = btn.getAttribute('data-filter') || 'ALL';
      aplicarFiltro(filtro.toUpperCase());
    });
  });
  // aplica filtro inicial (Todos)
  aplicarFiltro('ALL');
  // prepara cards já PRONTO ao carregar
  document.querySelectorAll('.pedido[data-status="PRONTO"]').forEach(card => {
    const btnFinal = card.querySelector('.actions .btn:not(.secondary):not([data-action])');
    if (btnFinal){ btnFinal.disabled = true; btnFinal.textContent = 'Pronto'; }
    ensureEntregarButton(card);
    ensurePendenteButton(card);
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', inicializarFiltros);
} else {
  inicializarFiltros();
}
