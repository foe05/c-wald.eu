(function(){
  'use strict';

  var STORAGE_KEY = 'cwald_cookie_consent_v1';
  var CATEGORIES = [
    { key: 'necessary', label: 'Strictly necessary', description: 'Required for core site functionality (e.g. storing your consent preference). Cannot be disabled.', locked: true, defaultOn: true },
    { key: 'analytics', label: 'Analytics', description: 'Helps us understand how the site is used.', locked: true, comingSoon: true, defaultOn: false },
    { key: 'marketing', label: 'Marketing', description: 'Used to tailor content and advertising.', locked: true, comingSoon: true, defaultOn: false }
  ];

  function loadConsent(){
    try {
      var raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return null;
      var parsed = JSON.parse(raw);
      if (!parsed || typeof parsed !== 'object') return null;
      return parsed;
    } catch (e){ return null; }
  }

  function saveConsent(consent){
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify({
        consent: consent,
        timestamp: new Date().toISOString(),
        version: 1
      }));
    } catch (e){}
    window.dispatchEvent(new CustomEvent('cwald:consent', { detail: consent }));
  }

  function defaultConsent(){
    var c = {};
    CATEGORIES.forEach(function(cat){ c[cat.key] = cat.defaultOn; });
    return c;
  }

  function createEl(tag, attrs, children){
    var el = document.createElement(tag);
    if (attrs){
      Object.keys(attrs).forEach(function(k){
        if (k === 'class') el.className = attrs[k];
        else if (k === 'html') el.innerHTML = attrs[k];
        else if (k === 'onclick') el.addEventListener('click', attrs[k]);
        else el.setAttribute(k, attrs[k]);
      });
    }
    if (children){
      children.forEach(function(c){
        if (typeof c === 'string') el.appendChild(document.createTextNode(c));
        else if (c) el.appendChild(c);
      });
    }
    return el;
  }

  function buildBanner(){
    if (document.getElementById('cookie-banner')) return;

    var banner = createEl('div', { id: 'cookie-banner', class: 'cookie-banner', role: 'dialog', 'aria-live': 'polite', 'aria-label': 'Cookie consent' }, [
      createEl('h3', null, ['Cookies on c-wald.eu']),
      createEl('p', null, [
        'We use only strictly necessary cookies to operate this site. No tracking or analytics are active. See our ',
        createEl('a', { href: 'datenschutz.html' }, ['privacy notice']),
        ' for details.'
      ]),
      createEl('div', { class: 'cookie-actions' }, [
        createEl('button', { type: 'button', class: 'cookie-btn cookie-btn-primary', onclick: function(){ acceptNecessary(); } }, ['Accept necessary only']),
        createEl('button', { type: 'button', class: 'cookie-btn cookie-btn-secondary', onclick: function(){ openModal(); } }, ['Manage preferences'])
      ])
    ]);
    document.body.appendChild(banner);
  }

  function removeBanner(){
    var b = document.getElementById('cookie-banner');
    if (b) b.parentNode.removeChild(b);
  }

  function acceptNecessary(){
    saveConsent(defaultConsent());
    removeBanner();
  }

  function openModal(){
    closeModal();
    var existing = loadConsent();
    var current = (existing && existing.consent) ? existing.consent : defaultConsent();

    var catNodes = CATEGORIES.map(function(cat){
      var labelText = cat.label + (cat.comingSoon ? ' — Coming soon' : '');
      var checkbox = createEl('input', { type: 'checkbox' });
      checkbox.dataset.cat = cat.key;
      if (current[cat.key]) checkbox.checked = true;
      if (cat.locked) checkbox.disabled = true;

      var toggle = createEl('label', { class: 'cookie-toggle', 'aria-label': labelText }, [
        checkbox,
        createEl('span', { class: 'slider' })
      ]);

      return createEl('div', { class: 'cookie-cat' }, [
        createEl('div', null, [
          createEl('strong', null, [labelText]),
          createEl('small', null, [cat.description])
        ]),
        toggle
      ]);
    });

    var modal = createEl('div', { class: 'cookie-modal-backdrop', id: 'cookie-modal', role: 'dialog', 'aria-modal': 'true', 'aria-labelledby': 'cookie-modal-title' }, [
      createEl('div', { class: 'cookie-modal' }, [
        createEl('h3', { id: 'cookie-modal-title' }, ['Cookie preferences']),
        createEl('p', null, ['Analytics and marketing categories are reserved for future use and currently inactive.'])
      ].concat(catNodes).concat([
        createEl('div', { class: 'cookie-modal-actions' }, [
          createEl('button', { type: 'button', class: 'cookie-btn cookie-btn-secondary', onclick: closeModal }, ['Cancel']),
          createEl('button', { type: 'button', class: 'cookie-btn cookie-btn-primary', onclick: function(){
            var newConsent = {};
            CATEGORIES.forEach(function(c){
              var el = modal.querySelector('input[data-cat="' + c.key + '"]');
              newConsent[c.key] = !!(el && el.checked);
            });
            newConsent.necessary = true;
            saveConsent(newConsent);
            closeModal();
            removeBanner();
          } }, ['Save preferences'])
        ])
      ]))
    ]);

    modal.addEventListener('click', function(e){
      if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', escClose);
    document.body.appendChild(modal);
  }

  function closeModal(){
    var m = document.getElementById('cookie-modal');
    if (m) m.parentNode.removeChild(m);
    document.removeEventListener('keydown', escClose);
  }

  function escClose(e){ if (e.key === 'Escape') closeModal(); }

  function init(){
    var existing = loadConsent();
    if (!existing) buildBanner();

    var link = document.getElementById('cookie-settings-link');
    if (link){
      link.addEventListener('click', function(e){
        e.preventDefault();
        openModal();
      });
    }

    window.CWaldCookies = {
      open: openModal,
      get: function(){ var c = loadConsent(); return c ? c.consent : null; },
      reset: function(){ try { localStorage.removeItem(STORAGE_KEY); } catch (e){} buildBanner(); }
    };
  }

  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
