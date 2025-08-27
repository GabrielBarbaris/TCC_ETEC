const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebar-toggle');
const items = Array.from(sidebar.querySelectorAll('.sidebar-item'));

let collapsed = false;
function setSidebarState() {
    if (collapsed) {
        sidebar.style.height = '54px';
        sidebar.style.width = '54px';
        sidebar.style.padding = '0';
        items.forEach(item => item.style.display = 'none');
        toggleBtn.style.display = 'flex';
        toggleBtn.style.margin = '0';
    } else {
        sidebar.style.height = '300px';
        sidebar.style.width = '54px';
        sidebar.style.padding = '20px 0';
        items.forEach(item => item.style.display = '');
        toggleBtn.style.display = 'flex';
        toggleBtn.style.margin = '';
    }
}
toggleBtn.addEventListener('click', function (e) {
    e.preventDefault();
    collapsed = !collapsed;
    setSidebarState();
});

collapsed = false;
setSidebarState();

const moreBtn = document.getElementById('more-button');
const hiddenCards = document.querySelectorAll('.feature-card.hidden');
let expanded = false;

hiddenCards.forEach(card => {
    if (expanded) {
        card.classList.add('show');
    } else {
        card.classList.remove('show');
    }
});


moreBtn.addEventListener('click', function (e) {
    e.preventDefault();
    expanded = !expanded;

    hiddenCards.forEach(card => {
        card.classList.toggle('show', expanded);
    });

    moreBtn.textContent = expanded ? 'Ver menos' : 'Ver mais';
});

const userBtn = document.getElementById('user-btn');
const userPanel = document.getElementById('user-panel');

userBtn.addEventListener('click', () => {
    userPanel.classList.toggle('open');
});

document.getElementById('profile-btn').addEventListener('click', () => {
    alert('Indo para a página de perfil...');
});

// ../scripts/script.js
(() => {
  const LOGIN_BTN_SELECTOR = '.login-btn';
  const SIGNUP_BTN_SELECTOR = '.signup-link';
  const BODY_LOCK_CLASS = 'bw-modal-open';

  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // injeta CSS do modal
  const injectStyles = () => {
    if ($('#bw-modal-style')) return;
    const css = `
      .${BODY_LOCK_CLASS}{ overflow:hidden; }
      .bw-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.45);
        display:flex; align-items:center; justify-content:center; z-index:9999;
        opacity:0; pointer-events:none; transition:opacity .2s ease; }
      .bw-overlay.open{ opacity:1; pointer-events:auto; }
      .bw-modal{ width:min(560px, 92vw); background:#fff; border-radius:16px;
        box-shadow:0 20px 60px rgba(0,0,0,.25); overflow:hidden; transform:translateY(12px);
        transition:transform .2s ease; font-family:'Inter','Helvetica Neue',sans-serif; }
      .bw-overlay.open .bw-modal{ transform:translateY(0); }
      .bw-header{ background:var(--secondary-color,#FFC700); padding:14px 18px;
        display:flex; align-items:center; justify-content:space-between; }
      .bw-title{ margin:0; font-size:18px; font-weight:700; color:var(--text-dark,#000); }
      .bw-close{ background:transparent; border:none; font-size:22px; cursor:pointer; line-height:1; }
      .bw-tabs{ display:flex; gap:8px; padding:10px 18px; background:#fff; border-bottom:1px solid rgba(0,0,0,.06); }
      .bw-tab{ appearance:none; border:none; background:transparent; padding:10px 14px; border-radius:10px;
        font-weight:700; cursor:pointer; color:var(--text-dark,#000); }
      .bw-tab[aria-selected="true"]{ background:rgba(90,36,143,.1); color:var(--primary-color,#5a248f); }
      .bw-content{ padding:18px; }
      .bw-field{ margin-bottom:12px; }
      .bw-label{ display:block; font-size:13px; margin-bottom:6px; color:#333; }
      .bw-input{ width:100%; height:42px; border-radius:10px; border:1px solid rgba(0,0,0,.15);
        background:#fff; padding:0 12px; font-size:14px; }
      .bw-hint{ font-size:12px; color:#666; margin-top:4px; }
      .bw-row{ display:flex; gap:10px; }
      .bw-row > .bw-field{ flex:1; }
      .bw-actions{ display:flex; align-items:center; justify-content:space-between; margin-top:14px; }
      .bw-btn{ display:inline-flex; align-items:center; justify-content:center; gap:8px; border:none; cursor:pointer;
        height:42px; padding:0 18px; border-radius:10px; font-weight:700; }
      .bw-btn-primary{ background:var(--primary-color,#5a248f); color:var(--text-light,#fff); }
      .bw-btn-ghost{ background:transparent; border:1px solid rgba(0,0,0,.15); }
      .bw-switch{ margin-top:14px; font-size:13px; text-align:center; }
      .bw-link{ color:var(--primary-color,#5a248f); font-weight:700; cursor:pointer; }
      .bw-error{ color:#b00020; font-size:12px; margin-top:4px; display:none; }
      .bw-error.show{ display:block; }
      @media (max-width:420px){ .bw-actions{ flex-direction:column; align-items:stretch; gap:10px; } }
    `.trim();
    const style = document.createElement('style');
    style.id = 'bw-modal-style';
    style.textContent = css;
    document.head.appendChild(style);
  };

  // template do modal
  const template = () => `
    <div class="bw-overlay" role="dialog" aria-modal="true" aria-labelledby="bw-modal-title">
      <div class="bw-modal">
        <div class="bw-header">
          <h3 id="bw-modal-title" class="bw-title">Entrar</h3>
          <button class="bw-close" aria-label="Fechar modal">&times;</button>
        </div>
        <div class="bw-tabs" role="tablist" aria-label="entrar ou cadastrar">
          <button class="bw-tab" role="tab" aria-selected="true" id="tab-login" aria-controls="panel-login">Login</button>
          <button class="bw-tab" role="tab" aria-selected="false" id="tab-signup" aria-controls="panel-signup">Cadastro</button>
        </div>
        <div class="bw-content">
          <form id="panel-login" role="tabpanel" aria-labelledby="tab-login">
            <div class="bw-field">
              <label class="bw-label" for="login-email">Email</label>
              <input class="bw-input" type="email" id="login-email" name="email" autocomplete="email" required />
              <div class="bw-error" id="err-login-email">Informe um email válido.</div>
            </div>
            <div class="bw-field">
              <label class="bw-label" for="login-pass">Senha</label>
              <input class="bw-input" type="password" id="login-pass" name="password" autocomplete="current-password" required />
              <div class="bw-error" id="err-login-pass">Informe sua senha.</div>
            </div>
            <div class="bw-actions">
              <label style="font-size:13px;">
                <input type="checkbox" id="remember-me" /> Manter conectado
              </label>
              <button type="submit" class="bw-btn bw-btn-primary" id="login-submit">Entrar</button>
            </div>
            <p class="bw-switch">Não tem conta? <span class="bw-link" data-switch="signup">Cadastre-se</span></p>
          </form>

          <form id="panel-signup" role="tabpanel" aria-labelledby="tab-signup" hidden>
            <div class="bw-field">
              <label class="bw-label" for="su-name">Nome completo</label>
              <input class="bw-input" type="text" id="su-name" name="name" autocomplete="name" required />
              <div class="bw-error" id="err-su-name">Informe seu nome.</div>
            </div>
            <div class="bw-row">
              <div class="bw-field">
                <label class="bw-label" for="su-email">Email</label>
                <input class="bw-input" type="email" id="su-email" name="email" autocomplete="email" required />
                <div class="bw-error" id="err-su-email">Informe um email válido.</div>
              </div>
              <div class="bw-field">
                <label class="bw-label" for="su-phone">Telefone (opcional)</label>
                <input class="bw-input" type="tel" id="su-phone" name="phone" autocomplete="tel" />
              </div>
            </div>
            <div class="bw-row">
              <div class="bw-field">
                <label class="bw-label" for="su-pass">Senha</label>
                <input class="bw-input" type="password" id="su-pass" name="password" autocomplete="new-password" required />
                <div class="bw-hint">Mínimo 6 caracteres.</div>
                <div class="bw-error" id="err-su-pass">Senha muito curta.</div>
              </div>
              <div class="bw-field">
                <label class="bw-label" for="su-pass2">Confirmar senha</label>
                <input class="bw-input" type="password" id="su-pass2" name="password2" autocomplete="new-password" required />
                <div class="bw-error" id="err-su-pass2">As senhas não conferem.</div>
              </div>
            </div>
            <div class="bw-actions">
              <label style="font-size:13px;">
                <input type="checkbox" id="su-terms" required/> Aceito os termos de uso
              </label>
              <button type="submit" class="bw-btn bw-btn-primary" id="signup-submit">Cadastrar</button>
            </div>
            <p class="bw-switch">Já tem conta? <span class="bw-link" data-switch="login">Entrar</span></p>
          </form>
        </div>
      </div>
    </div>
  `.trim();

  const bindModalEvents = (overlay) => {
    const closeModal = () => {
      overlay.classList.remove('open');
      document.body.classList.remove(BODY_LOCK_CLASS);
    };

    $('.bw-close', overlay).addEventListener('click', closeModal);
    overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', (e) => {
      if ($('.bw-overlay.open') && e.key === 'Escape') closeModal();
    });

    const switchPanel = (target) => {
      const isLogin = target === 'login';
      $('#tab-login', overlay).setAttribute('aria-selected', isLogin ? 'true' : 'false');
      $('#tab-signup', overlay).setAttribute('aria-selected', !isLogin ? 'true' : 'false');
      $('#panel-login', overlay).hidden = !isLogin;
      $('#panel-signup', overlay).hidden = isLogin;
      $('#bw-modal-title', overlay).textContent = isLogin ? 'Entrar' : 'Cadastrar';
      (isLogin ? $('#login-email', overlay) : $('#su-name', overlay))?.focus();
    };

    overlay.switchPanel = switchPanel;

    $('#tab-login', overlay).addEventListener('click', () => switchPanel('login'));
    $('#tab-signup', overlay).addEventListener('click', () => switchPanel('signup'));
    overlay.addEventListener('click', (e) => {
      const t = e.target.closest('.bw-link');
      if (!t) return;
      e.preventDefault();
      if (t.dataset.switch === 'signup') switchPanel('signup');
      if (t.dataset.switch === 'login') switchPanel('login');
    });

    const emailOk = (v) => /\S+@\S+\.\S+/.test(v);

    $('#panel-login', overlay).addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = $('#login-email', overlay).value.trim();
      const pass = $('#login-pass', overlay).value.trim();
      $('#err-login-email', overlay).classList.toggle('show', !emailOk(email));
      $('#err-login-pass', overlay).classList.toggle('show', pass.length === 0);
      if (!emailOk(email) || !pass) return;

      const btn = $('#login-submit', overlay);
      const prev = btn.textContent;
      btn.disabled = true; btn.textContent = 'Entrando...';
      try {
        console.log('LOGIN ->', { email, pass, remember: $('#remember-me', overlay).checked });
        closeModal();
      } catch (err) {
        alert(err.message || 'Erro ao fazer login');
      } finally {
        btn.disabled = false; btn.textContent = prev;
      }
    });

    $('#panel-signup', overlay).addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = $('#su-name', overlay).value.trim();
      const email = $('#su-email', overlay).value.trim();
      const phone = $('#su-phone', overlay).value.trim();
      const pass = $('#su-pass', overlay).value;
      const pass2 = $('#su-pass2', overlay).value;
      const terms = $('#su-terms', overlay).checked;

      $('#err-su-name', overlay).classList.toggle('show', name.length < 2);
      $('#err-su-email', overlay).classList.toggle('show', !emailOk(email));
      $('#err-su-pass', overlay).classList.toggle('show', pass.length < 6);
      $('#err-su-pass2', overlay).classList.toggle('show', pass !== pass2);
      if (name.length < 2 || !emailOk(email) || pass.length < 6 || pass !== pass2 || !terms) return;

      const btn = $('#signup-submit', overlay);
      const prev = btn.textContent;
      btn.disabled = true; btn.textContent = 'Cadastrando...';
      try {
        console.log('SIGNUP ->', { name, email, phone, pass });
        closeModal();
      } catch (err) {
        alert(err.message || 'Erro ao cadastrar');
      } finally {
        btn.disabled = false; btn.textContent = prev;
      }
    });

    overlay.addEventListener('keydown', (e) => {
      if (e.key !== 'Tab') return;
      const f = $$('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])', overlay)
        .filter(el => !el.hasAttribute('disabled') && !el.getAttribute('aria-hidden'));
      if (!f.length) return;
      const first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { last.focus(); e.preventDefault(); }
      else if (!e.shiftKey && document.activeElement === last) { first.focus(); e.preventDefault(); }
    });
  };

  const ensureModal = () => {
    let overlay = $('.bw-overlay');
    if (overlay) return overlay;
    injectStyles();
    const wrapper = document.createElement('div');
    wrapper.innerHTML = template();
    overlay = wrapper.firstElementChild;
    document.body.appendChild(overlay);
    bindModalEvents(overlay);
    return overlay;
  };

  const openModal = (tab = 'login') => {
    const overlay = ensureModal();
    overlay.classList.add('open');
    document.body.classList.add(BODY_LOCK_CLASS);
    overlay.switchPanel(tab);
  };

  document.addEventListener('click', (e) => {
    if (e.target.closest(LOGIN_BTN_SELECTOR)) {
      e.preventDefault();
      openModal('login');
    }
    if (e.target.closest(SIGNUP_BTN_SELECTOR)) {
      e.preventDefault();
      openModal('signup');
    }
  });

  if (location.hash === '#login') window.addEventListener('load', () => openModal('login'));
  if (location.hash === '#signup') window.addEventListener('load', () => openModal('signup'));
})();
