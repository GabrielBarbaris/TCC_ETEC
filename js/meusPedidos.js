function toggleMais(btn){
  const card = btn.closest('.pedido');
  card.classList.toggle('open');
  btn.textContent = card.classList.contains('open') ? 'recolher' : 'saiba mais';
}

async function cancelarPedido(btn){
  const card = btn.closest('.pedido');
  const id = card.getAttribute('data-id');
  if (!id) return;

  if (!confirm('Deseja realmente cancelar este pedido?')) return;

  btn.disabled = true;
  const originalText = btn.textContent;
  btn.textContent = 'Cancelando...';

  try{
    const resp = await fetch('cancelarPedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
      body: new URLSearchParams({ id })
    });
    const data = await resp.json();

    if (data && data.success){
      // Pedido excluído no servidor: remove o card da interface
      try { card.parentElement.removeChild(card); } catch(e){ if (card && card.remove) card.remove(); }
      return;
    } else {
      if (data && (data.message || '').toLowerCase().includes('não pode ser cancelado')){
        alert(data.message);
      } else {
        throw new Error((data && (data.error || data.message)) || 'Falha ao cancelar');
      }
      btn.disabled = false;
      btn.textContent = originalText;
      return;
    }
  } catch (e){
    alert('Erro ao cancelar o pedido: ' + e.message);
    btn.disabled = false;
    btn.textContent = originalText;
    return;
  }
}

function marcarComoCancelado(card, btn){
  const badge = card.querySelector('.status-badge');
  if (badge){
    badge.textContent = 'Cancelado';
    badge.classList.remove('status-pendente');
    badge.classList.add('status-pendente');
  }
  btn.textContent = 'Cancelado';
  btn.disabled = true;
  card.classList.add('is-cancelado');
}
