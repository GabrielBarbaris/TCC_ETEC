function toggleMais(btn){
    const card = btn.closest('.pedido');
    card.classList.toggle('open');
    btn.textContent = card.classList.contains('open') ? 'recolher' : 'saiba mais';
  }
  function finalizarPedido(btn){
    const card = btn.closest('.pedido');
    const id = card.getAttribute('data-id');
    // Integração (exemplo):
    // fetch('finalizaPedido.php', { method:'POST', body: new URLSearchParams({ id }) })
    //   .then(r => r.json())
    //   .then(() => removerCard(card))
    //   .catch(console.error);
    btn.disabled = true;
    btn.textContent = 'Finalizando...';
    setTimeout(() => removerCard(card), 500);
  }
  function removerCard(card){
    card.style.transition = 'opacity .2s ease, transform .2s ease';
    card.style.opacity = '0';
    card.style.transform = 'scale(0.98)';
    setTimeout(() => card.remove(), 220);
  }