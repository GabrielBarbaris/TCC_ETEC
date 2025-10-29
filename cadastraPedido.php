<?php 
    include 'cadastraPedidoBD.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastroPedido.css" />
    <title>Pedido</title>
</head>

<body>
    <script src="script.js" defer></script>
    <?php
    include "menuAdm.php";
    ?>

    <div class="container" style ="width: 100%;
  max-width: 1200px;
  padding: 96px 16px 40px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 16px;">
        <div class="tela1">
            <h3 class="titulo">Cadastro de Pedido</h3>

            <form id="form" class="form" novalidate>
            <div class="form_content" >
                <span id="mensagem">Menssagem</span>
            </div>
                <section class="produto">
                    <div class="form_content">
                        <label for="produto"> produto</label>
                        <input type="text" id="produto" name="produto" placeholder="Digite o produto">
                        <a>mensagem de erro</a>
                    </div>
                    <div class="form_content">
                        <label for="quantidade"> quantidade</label>
                        <input type="text" id="quantidade" name="quantidade" placeholder="quantidade">
                        <a>mensagem de erro</a>
                    </div>

                    <div class="form_content">
                    <label for="corte">Corte</label>
                    <select id="corte" name="corte">
                        <option value="">Escolha o corte</option>
                    </select>
                    <a>mensagem de erro</a>
                    </div> 

                    <div class="form_content">
                        <label for="observacao">Observações (opcional)</label>
                        <input type="text" id="observacao" name="observacao" placeholder="Alguma observação sobre o item">
                    </div>

                    <button type="button" id="btnAdicionar">Adicionar</button>
                </section>
                <section class="pedido">

                    <div class="form_content">
                        <label for="horario"> Horario de Retirada/Entrega</label>
                        <input type="time" id="horario" name="horario" placeholder="horario">
                        <a>mensagem de erro</a>
                    </div>
                    <div class="form_content">
                        <label for="cliente"> cliente</label>
                        <input type="text" id="cliente" name="cliente" placeholder="cliente">
                        <input type="hidden" id="cliente_id" name="cliente_id" value="">
                        <a>mensagem de erro</a>
                    </div>
                    <div class="form_content">
                        <label for="recebimento">Recebimento</label>
                        <div class="radio">
                            <div class="item_radio">
                            <input type="radio" id="entrega" value="entrega" name="recebimento">
                            <label for="entrega">Entrega</label>
                            </div>
                            <div class="item_radio">
                            <input type="radio" id="retirada" value="retirada" name="recebimento" checked>
                            <label for="retirada">Retirada</label>
                            </div>
                        </div>

                        <div id="endereco" class="hidden">
                            <div class="form_content">
                                <label for="cep">CEP</label>
                                <input type="text" id="cep" name="cep" placeholder="Digite o CEP (apenas números)">
                                <a>mensagem de erro</a>
                            </div>

                            <div class="linha-endereco" style="display:flex; align-items:flex-end; gap:8px;">
                                <div class="form_content" style="max-width: 120px;">
                                    <label for="numero_endereco">Número</label>
                                    <input type="text" id="numero_endereco" name="numero" placeholder="Número">
                                    <a>mensagem de erro</a>
                                </div>
                                <div class="form_content" style="flex: 1 1 auto; min-width:0;">
                                    <label for="campo_endereco">Endereço</label>
                                    <input type="text" id="campo_endereco" name="endereco" placeholder="Rua, bairro, cidade - UF">
                                    <a>mensagem de erro</a>
                                </div>
                            </div>

                            <div class="form_content">
                                <label for="complemento_endereco">Complemento (opcional)</label>
                                <input type="text" id="complemento_endereco" name="complemento" placeholder="Apartamento, bloco, referência">
                            </div>
                        </div>
                        </div>
                </section>


                <button type="submit" id="cadastrar">Cadastrar</button>
            </form>
        </div>
        <aside class="painel-itens" id="painel-itens">
            <div class="painel-itens__header">
                <h4>Itens do pedido</h4>
                <span id="contador-itens" class="badge">0</span>
            </div>
            <ul id="lista-itens" class="lista-itens"></ul>
            <div class="painel-itens__footer" id="painel-itens-footer">Nenhum item adicionado.</div>
        </aside>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="./js/cadastraPedido.js"></script>

    <style>
      /* Estilos básicos do modal de cadastro (como no index) */
      #cadastroDialog::backdrop { background: rgba(0,0,0,.45); }
     
      #cadastroDialog .header { background: linear-gradient(120deg,#600E0E,#440D0D); padding:20px; text-align:center; color:#fff; font-size:30px; }
      #cadastroDialog .form { padding:18px; }
      #cadastroDialog .form_content { margin-bottom:8px; padding-bottom:18px; position:relative; color:#807a75; }
      #cadastroDialog .form_content label { display:inline-block; margin-bottom:0; }
      #cadastroDialog .form_content input { display:block; width:100%; border-radius:3px; padding:10px; border:2px solid #dfdfdf; }
      #cadastroDialog .form_content a { position:absolute; bottom:0; left:0; visibility:hidden; }
      #cadastroDialog .form button { background-color:#600E0E; color:#fff; width:100%; border:0; border-radius:10px; padding:8px; font-family: Baloo, Arial, sans-serif; font-size:16px; cursor:pointer; margin-top:14px; }
      #cadastroDialog .form_content.error input { border-color:#fc5e5e; }
      #cadastroDialog .form_content span { display:block; text-align:center; padding:10px; color:#fff; border:3px solid rgba(243,4,4,.156); background-color:rgba(105,23,23,.593); border-radius:13px; }
      #cadastroDialog .form_content.error a { color:#fc5e5e; visibility:visible; }
    </style>

    <!-- Modal Cadastro (igual ao index) -->
    <dialog id="cadastroDialog" style="position:fixed; inset:0; margin:auto; width:min(400px,67vw); border:none; padding:0; border-radius:16px; overflow:hidden;">
      <button id="closeCadastroDialog" title="Fechar" style="position:absolute; top:8px; right:12px; z-index:2; background:#800000; color:#fff; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">&times;</button>
      <div class="container2">
        <section class="header"><h2>Nova Conta</h2></section>
        <form id="cadForm" class="form">
          <div class="form_content"><span id="cadMensagem">Menssagem</span></div>
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
          <button type="submit" id="btnCadastrar">Cadastrar</button>
        </form>
      </div>
    </dialog>

    <script>
      (function(){
        const clienteEl = document.getElementById('cliente');
        const clienteIdEl = document.getElementById('cliente_id');
        const formEl = document.getElementById('form');

        function openCadDialog(){
          const dlg = document.getElementById('cadastroDialog');
          if (!dlg) return;
          if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open','open');
        }
        function closeCadDialog(){
          const dlg = document.getElementById('cadastroDialog');
          if (!dlg) return;
          if (typeof dlg.close === 'function') dlg.close(); else dlg.removeAttribute('open');
        }

        // Busca cliente no servidor (tenta diferentes parâmetros)
        async function buscaCliente(term){
          term = (term||'').trim();
          if (!term) return [];
          const bases = ['buscaClientes.php?q=','buscaClientes.php?nome=','buscaClientes.php?termo='];
          for (const b of bases){
            try{
              const r = await fetch(b + encodeURIComponent(term), { headers: { 'Accept': 'application/json' }});
              if (!r.ok) continue;
              const data = await r.json();
              if (Array.isArray(data) && data.length){ return data; }
            }catch(e){ /* tenta próxima */ }
          }
          return [];
        }

        // tenta resolver ID do cliente com base no termo digitado
        async function resolveCliente(){
          const term = clienteEl ? clienteEl.value : '';
          clienteIdEl && (clienteIdEl.value = '');
          if (!term || term.trim().length < 2) return false;
          const lista = await buscaCliente(term);
          if (Array.isArray(lista) && lista.length){
            // se vier apenas um, captura o id
            const c = lista[0] || {};
            const idGuess = c.id || c.id_usuario || c.id_cliente || c.cliente_id;
            if (idGuess){
              clienteIdEl.value = String(idGuess);
            }
            return true;
          }
          return false;
        }

        clienteEl && clienteEl.addEventListener('blur', async function(){
          const ok = await resolveCliente();
          if (!ok){
            // Não encontrado: abrir cadastro
            openCadDialog();
          }
        });

        formEl && formEl.addEventListener('submit', async function(e){
          // exige cliente válido
          if (!clienteIdEl || !clienteIdEl.value){
            e.preventDefault(); e.stopPropagation();
            const ok = await resolveCliente();
            if (!ok){ openCadDialog(); }
          }
        });

        // Configura modal de cadastro (validação rápida + envio)
        const cadDlg = document.getElementById('cadastroDialog');
        const closeCadBtn = document.getElementById('closeCadastroDialog');
        closeCadBtn && closeCadBtn.addEventListener('click', closeCadDialog);
        cadDlg && cadDlg.addEventListener('cancel', function(ev){ ev.preventDefault(); closeCadDialog(); });

        (function(){
          const dlg = document.getElementById('cadastroDialog');
          if (!dlg) return;
          const form = dlg.querySelector('#cadForm');
          const nome = dlg.querySelector('#cadNome');
          const sobrenome = dlg.querySelector('#cadSobrenome');
          const telefone = dlg.querySelector('#cadTelefone');
          const senha = dlg.querySelector('#cadSenha');
          const senhaConf = dlg.querySelector('#cadSenhaConf');
          const msg = dlg.querySelector('#cadMensagem');
          if (msg) { try { $(msg).fadeOut(0); } catch(e){ msg.style.display='none'; } }

          function setOk(el){ const it = el.parentElement; if (it) it.className = 'form_content'; }
          function setErr(el,m){ const it = el.parentElement; if (!it) return; const a = it.querySelector('a'); if (a) a.innerText = m; it.className = 'form_content error'; }
          function maskTel(v){ v=(v||'').replace(/\D/g,''); if (v.length>0) v='('+v; if (v.length>3) v=v.slice(0,3)+') '+v.slice(3); if (v.length>10) v=v.slice(0,10)+'-'+v.slice(10); if (v.length>15) v=v.slice(0,15); return v; }

          telefone && telefone.addEventListener('input', function(){ telefone.value = maskTel(telefone.value); });
          nome && nome.addEventListener('blur', function(){ if (!nome.value.trim()) setErr(nome,'preencha um nome de usuario'); else setOk(nome); });
          sobrenome && sobrenome.addEventListener('blur', function(){ if (!sobrenome.value.trim()) setErr(sobrenome,'preencha seu sobrenome'); else setOk(sobrenome); });
          telefone && telefone.addEventListener('blur', function(){ const v=telefone.value; if (!v) setErr(telefone,'preencha seu telefone'); else if (v.length!==15) setErr(telefone,'preencha seu numero completo'); else setOk(telefone); });
          senha && senha.addEventListener('blur', function(){ const v=senha.value; if (!v) setErr(senha,'digite uma senha'); else if (v.length<8) setErr(senha,'minimo de 8 caracteres'); else setOk(senha); });
          senhaConf && senhaConf.addEventListener('blur', function(){ const v=senhaConf.value; if (!v) setErr(senhaConf,'repita sua senha'); else if (v!==senha.value) setErr(senhaConf,'sua senha não esta igual'); else setOk(senhaConf); });

          function isValid(){ nome.dispatchEvent(new Event('blur')); sobrenome.dispatchEvent(new Event('blur')); telefone.dispatchEvent(new Event('blur')); senha.dispatchEvent(new Event('blur')); senhaConf.dispatchEvent(new Event('blur')); const items=form.querySelectorAll('.form_content'); return Array.from(items).every(it=>it.className==='form_content'); }

          form && form.addEventListener('submit', function(e){
            e.preventDefault();
            if (!isValid()) return;
            const payload = { nome: nome.value.trim(), sobrenome: sobrenome.value.trim(), telefone: telefone.value.trim(), senha: senha.value };
            $.ajax({
              url: 'cadastraLogin.php', method: 'POST', data: payload,
              success: function(response){
                const resp = (response||'').toString().trim();
                if (resp==='ok'){
                  if (msg){ $(msg).html('Cadastrado com sucesso'); $(msg).fadeIn(300).delay(2000).fadeOut(400); }
                  setTimeout(function(){ try{ form.reset(); }catch(e){} closeCadDialog(); clienteEl && clienteEl.focus(); }, 2200);
                } else {
                  if (msg){ $(msg).html('Essa conta já existe ou ocorreu um erro'); $(msg).fadeIn(300).delay(2000).fadeOut(400); }
                }
              },
              error: function(){ if (msg){ $(msg).html('Falha na comunicação com o servidor'); $(msg).fadeIn(300).delay(2000).fadeOut(400); } }
            });
          });
        })();
      })();
    </script>
</body>

</html>