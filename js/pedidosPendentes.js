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
  btn.textContent = 'Pronto';
  btn.disabled = true;
  card.classList.add('is-pronto');
}

function removerCard(card){
  card.style.transition = 'opacity .2s ease, transform .2s ease';
  card.style.opacity = '0';
  card.style.transform = 'scale(0.98)';
  setTimeout(() => card.remove(), 220);
}
