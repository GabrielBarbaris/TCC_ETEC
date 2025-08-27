(() => {

    // === USER SIDEBAR ===
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const items = Array.from(sidebar?.querySelectorAll('.sidebar-item') || []);
    let collapsed = false;

    function setSidebarState() {
        if (!sidebar || !toggleBtn) return;
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

    toggleBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        collapsed = !collapsed;
        setSidebarState();
    });

    collapsed = false;
    setSidebarState();

    // === USER PANEL ===
    const userBtn = document.getElementById('user-btn');
    const userPanel = document.getElementById('user-panel');
    userBtn?.addEventListener('click', () => {
        userPanel?.classList.toggle('open');
    });

    document.getElementById('profile-btn')?.addEventListener('click', () => {
        alert('Indo para a página de perfil...');
    });


    // CONFIG
    const USE_DEV_JSON = true;
    const PER_PAGE = 5;
    let page = 1;
    let lastTotal = 0;

    const DEV_JSON_PATH = (() => {
        try {
            if (!window.location.origin || window.location.origin === 'null') {
                return new URL('data/freelancers.json', window.location.href).href;
            }
            return window.location.origin + '/data/freelancers.json';
        } catch {
            return 'data/freelancers.json';
        }
    })();

    // DOM refs
    const container = document.querySelector('.freelancers-list');
    const searchInput = document.querySelector('#main-search') || document.querySelector('.search-bar input');
    const widgetSearchInput = document.querySelector('.widget-search-bar input');
    const sortSelect = document.querySelector('#sort-select');
    const clearBtn = document.querySelector('#clear-filters') || document.querySelector('.clear-btn');
    const openFiltersBtn = document.querySelector('#open-filters');
    const paginationContainer = document.querySelector('.pagination');

    if (!container) {
        console.warn('scriptsFreelancers: container .freelancers-list não encontrado.');
        return;
    }

    const initialCards = Array.from(container.children);

    // helper DOM utilities
    const qs = (sel, root = document) => root.querySelector(sel);
    const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    // Normalize, escape, parse
    const normalize = str => String(str || '').trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const escapeHtml = str => String(str || '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    const debounce = (fn, wait = 300) => { let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); }; };

    function parsePrice(item) {
        if (typeof item.price_numeric === 'number' && !isNaN(item.price_numeric)) return item.price_numeric;
        const num = parseFloat(String(item.price || '').replace(/[^\d,.-]/g, '').replace(',', '.'));
        return isNaN(num) ? 0 : num;
    }

    // CATEGORY helpers: prefer checkboxes; if no checkboxes, fall back to selects if present
    function getCategoryCheckboxes() {
        return qsa('.category-checkbox');
    }
    function getCategorySelects() {
        return qsa('.category-select');
    }

    function getSelectedCategories() {
        const out = [];
        // first: checkboxes
        const boxes = getCategoryCheckboxes();
        if (boxes.length) {
            boxes.forEach(cb => { if (cb.checked && cb.value) out.push(cb.value.trim()); });
        } else {
            // fallback: selects (supports multiple)
            const sels = getCategorySelects();
            sels.forEach(sel => {
                for (const opt of Array.from(sel.selectedOptions || [])) {
                    if (opt.value) out.push(opt.value.trim());
                }
            });
        }
        return out;
    }

    // RENDER
    function renderBadge(skill) {
        return `<span class="skill-badge">${escapeHtml(skill)}</span>`;
    }

    function renderFreelancerCard(f) {
        const skills = Array.isArray(f.skills) ? f.skills : (typeof f.skills === 'string' ? f.skills.split(/[\/,|;]/).map(s => s.trim()) : []);
        const avatar = f.avatar || '/assets/images/usuario.png';
        const price = f.price || (f.price_numeric ? `R$${Number(f.price_numeric).toFixed(2)}/hora` : '');

        const art = document.createElement('article');
        art.className = 'freelancer-card';
        art.innerHTML = `
      <div class="info-left">
        <h3 class="freelancer-name">${escapeHtml(f.name || '')}</h3>
        <img src="${escapeHtml(avatar)}" alt="${escapeHtml(f.name || '')}" class="avatar">
        <div class="skills">
          <p class="skills-title">Conhecimento em:</p>
          <div class="skills-badges">${skills.map(renderBadge).join('')}</div>
        </div>
        <div class="price">
          <p class="price-title">Preço médio:</p>
          <p class="price-value">${escapeHtml(price)}</p>
        </div>
      </div>
      <div class="info-right">
        <p class="description">${escapeHtml(f.description || '')}</p>
        <div class="meta-row">
          ${typeof f.rating === 'number' ? `<span class="rating">⭐ ${Number(f.rating).toFixed(1)}</span>` : ''}
          <a href="#" class="details-btn">Quero conhecer mais</a>
        </div>
      </div>
    `;
        return art;
    }

    function renderFreelancers(list) {
        container.innerHTML = '';
        if (!list || !list.length) {
            container.innerHTML = '<p class="no-results">Nenhum freelancer encontrado.</p>';
            return;
        }
        const frag = document.createDocumentFragment();
        list.forEach(f => frag.appendChild(renderFreelancerCard(f)));
        container.appendChild(frag);
    }

    // PAGINATION UI & delegation
    function updatePagination(total) {
        lastTotal = total ?? lastTotal;
        if (!paginationContainer) return;

        paginationContainer.innerHTML = '';
        const totalPages = Math.max(1, Math.ceil((lastTotal || 1) / PER_PAGE));

        const makeArrow = dir => {
            const a = document.createElement('a');
            a.href = '#';
            a.className = 'page-arrow';
            a.dataset.action = dir === 'prev' ? 'prev' : 'next';
            a.innerHTML = `<img src="../assets/images/icon_próximo.png" alt="${dir}" ${dir === 'prev' ? 'style="transform: rotate(180deg);"' : ''}>`;
            return a;
        };

        paginationContainer.appendChild(makeArrow('prev'));

        const windowSize = 5;
        let start = Math.max(1, page - Math.floor(windowSize / 2));
        let end = Math.min(totalPages, start + windowSize - 1);
        if (end - start < windowSize - 1) start = Math.max(1, end - windowSize + 1);

        if (start > 1) {
            const s = document.createElement('span'); s.className = 'page-ellipsis'; s.textContent = '...';
            paginationContainer.appendChild(s);
        }

        for (let i = start; i <= end; i++) {
            const a = document.createElement('a');
            a.href = '#';
            a.className = 'page-number' + (i === page ? ' active' : '');
            a.dataset.page = i;
            a.textContent = i;
            paginationContainer.appendChild(a);
        }

        if (end < totalPages) {
            const s = document.createElement('span'); s.className = 'page-ellipsis'; s.textContent = '...';
            paginationContainer.appendChild(s);
        }

        paginationContainer.appendChild(makeArrow('next'));
    }

    if (paginationContainer) {
        paginationContainer.addEventListener('click', (e) => {
            const a = e.target.closest('a');
            if (!a) return;
            e.preventDefault();
            if (a.dataset.action === 'prev') page = Math.max(1, page - 1);
            else if (a.dataset.action === 'next') {
                const totalPages = Math.max(1, Math.ceil((lastTotal || 1) / PER_PAGE));
                page = Math.min(totalPages, page + 1);
            } else if (a.dataset.page) page = Number(a.dataset.page);
            applyFiltersImmediate();
        });
    }

    // FILTERS
    function getFilters() {
        const search = (searchInput?.value || '').trim();
        const categories = getSelectedCategories();
        const sort = sortSelect?.value || '';
        return { search, categories, sort, page, perPage: PER_PAGE };
    }

    function applyLocalFilter(all, filters) {
        const q = normalize(filters.search);

        const filtered = (all || []).filter(item => {
            const name = normalize(item.name || '');
            const desc = normalize(item.description || '');
            const skillsArr = Array.isArray(item.skills) ? item.skills.map(s => normalize(s)) : [normalize(item.skills || '')];
            const catsArr = Array.isArray(item.categories) ? item.categories.map(s => normalize(s)) : (item.category ? [normalize(item.category)] : []);
            const combined = [...new Set([...skillsArr, ...catsArr])];

            const matchesSearch = !q || name.includes(q) || desc.includes(q) || combined.join(' ').includes(q);

            let matchesCategories = true;
            if (filters.categories && filters.categories.length) {
                matchesCategories = filters.categories.some(cat => combined.includes(normalize(cat)));
            }

            return matchesSearch && matchesCategories;
        });

        // sort
        if (filters.sort) {
            if (filters.sort === 'price_asc' || filters.sort === 'price_desc') {
                filtered.sort((a, b) => (filters.sort === 'price_asc' ? parsePrice(a) - parsePrice(b) : parsePrice(b) - parsePrice(a)));
            } else if (filters.sort === 'name_asc' || filters.sort === 'name_desc') {
                filtered.sort((a, b) => a.name.localeCompare(b.name) * (filters.sort === 'name_asc' ? 1 : -1));
            }
        }

        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / filters.perPage));
        if (filters.page > totalPages) filters.page = totalPages;

        const start = (filters.page - 1) * filters.perPage;
        const paged = filtered.slice(start, start + filters.perPage);

        renderFreelancers(paged);
        updatePagination(total);

        return { freelancers: paged, total };
    }

    // FETCH JSON
    async function tryFetch(url) {
        const resp = await fetch(url, { cache: 'no-cache' });
        if (!resp.ok) throw new Error('status ' + resp.status);
        return await resp.json();
    }

    async function fetchFreelancers(filters) {
        if (USE_DEV_JSON) {
            const urls = [DEV_JSON_PATH, '/data/freelancers.json', 'data/freelancers.json'];
            for (const url of urls) {
                try {
                    const data = await tryFetch(url);
                    return applyLocalFilter(data, filters);
                } catch (err) {
                    // try next
                }
            }
            // fallback -> static DOM snapshot
            const snapshot = initialCards.map(c => ({
                name: qs('.freelancer-name', c)?.textContent || '',
                skills: (qs('.skills-list', c)?.textContent || '').split(/[\/,|;]/).map(s => s.trim()),
                description: qs('.description', c)?.textContent || '',
                price: qs('.price-value', c)?.textContent || '',
                avatar: qs('.avatar', c)?.src || ''
            }));
            return applyLocalFilter(snapshot, filters);
        } else {
            // future backend path
            return applyLocalFilter(initialCards.map(c => ({
                name: qs('.freelancer-name', c)?.textContent || '',
                skills: (qs('.skills-list', c)?.textContent || '').split(/[\/,|;]/).map(s => s.trim()),
                description: qs('.description', c)?.textContent || '',
                price: qs('.price-value', c)?.textContent || '',
                avatar: qs('.avatar', c)?.src || ''
            })), filters);
        }
    }

    // EVENTS
    const applyFiltersImmediate = async () => {
        const f = getFilters();
        await fetchFreelancers(f);
    };
    const applyFiltersDebounced = debounce(() => { page = 1; applyFiltersImmediate(); }, 250);

    // search inputs
    if (widgetSearchInput && searchInput) {
        widgetSearchInput.addEventListener('input', () => { searchInput.value = widgetSearchInput.value; applyFiltersDebounced(); });
        searchInput.addEventListener('input', () => { widgetSearchInput.value = searchInput.value; applyFiltersDebounced(); });
    } else if (searchInput) {
        searchInput.addEventListener('input', applyFiltersDebounced);
    }

    // sort
    sortSelect?.addEventListener('change', () => { page = 1; applyFiltersImmediate(); });

    // checkboxes: delegate to document to handle dynamically rendered sets too
    document.addEventListener('change', (e) => {
        const t = e.target;
        if (t && t.classList && (t.classList.contains('category-checkbox') || t.classList.contains('category-select'))) {
            page = 1;
            applyFiltersImmediate();
        }
    });

    // clear button
    clearBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        if (searchInput) searchInput.value = '';
        if (widgetSearchInput) widgetSearchInput.value = '';
        if (sortSelect) sortSelect.selectedIndex = 0;
        // clear checkboxes
        getCategoryCheckboxes().forEach(cb => cb.checked = false);
        // clear selects fallback
        getCategorySelects().forEach(s => { Array.from(s.options).forEach(o => o.selected = false); });
        page = 1;
        applyFiltersImmediate();
    });

    // open filters toggle
    openFiltersBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelector('.sidebara .categories-widget')?.classList.toggle('visible');
    });

    // category-group accordion (open/close)
    document.querySelectorAll('.category-group-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('.category-group');
            if (!group) return;
            const children = group.querySelector('.group-children');
            const isOpen = group.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (isOpen) {
                children.style.maxHeight = children.scrollHeight + 'px';
            } else {
                children.style.maxHeight = '0';
            }
        });
        // set initial collapsed state
        const g = btn.closest('.category-group');
        const ch = g?.querySelector('.group-children');
        if (ch) ch.style.maxHeight = '0';
    });

    (async () => {
        const filters = getFilters();
        await fetchFreelancers(filters);
    })();

    window.__BW = { applyFiltersImmediate, getFilters, fetchFreelancers, DEV_JSON_PATH };

})();




//!LOGIN
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
