<?php
$canonical = 'https://c-wald.eu/';
$title = 'C-Wald — Making European Forests Investable, with Integrity';
$description = 'C-Wald aggregates fragmented private forest holdings in DACH into certified carbon credit portfolios. MRV-automated, CRCF-ready.';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($description) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($description) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical) ?>">
<meta property="og:image" content="<?= htmlspecialchars($canonical) ?>assets/logo.svg">
<meta name="theme-color" content="#1A4D2E">
<link rel="icon" type="image/svg+xml" href="assets/logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="nav" id="nav">
  <div class="nav-inner">
    <a href="#top" class="nav-logo" aria-label="C-Wald home">
      <img src="assets/logo.svg" alt="C-Wald" width="36" height="36">
      <span>C-Wald</span>
    </a>
    <button class="nav-toggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="nav-menu">
      <span></span><span></span><span></span>
    </button>
    <nav class="nav-menu" id="nav-menu">
      <a href="#about">About</a>
      <a href="#how">How It Works</a>
      <a href="#team">Team</a>
      <a href="#contact">Contact</a>
    </nav>
  </div>
</header>

<main id="main">

<section class="hero" id="top">
  <div class="hero-inner container">
    <span class="badge reveal">🌱 Currently in founding phase · DACH · CRCF-ready</span>
    <h1 class="reveal">Making European Forests Investable — with Integrity.</h1>
    <p class="lead reveal">C-Wald aggregates fragmented private forest holdings in DACH into certified carbon credit portfolios.</p>
    <a href="#contact" class="btn btn-primary reveal">Get in Touch</a>
  </div>
</section>

<section class="section section-light" id="about">
  <div class="container">
    <h2 class="section-title reveal">The Problem</h2>
    <div class="stat-row">
      <div class="stat reveal">
        <div class="stat-number">3 ha</div>
        <div class="stat-label">Average private forest holding in Germany</div>
      </div>
      <div class="stat reveal">
        <div class="stat-number">€50k+</div>
        <div class="stat-label">Minimum <span class="mono">VCS</span> certification cost</div>
      </div>
      <div class="stat reveal">
        <div class="stat-number">0</div>
        <div class="stat-label"><span class="mono">CRCF</span>-certified forest carbon farming projects in Europe today</div>
      </div>
    </div>
    <p class="section-note reveal">A structural supply gap &mdash; against rapidly growing institutional demand for high-integrity European removals.</p>
  </div>
</section>

<section class="section section-cream" id="how">
  <div class="container">
    <h2 class="section-title reveal">How It Works</h2>
    <ol class="flow">
      <li class="flow-step reveal">
        <div class="flow-num">01</div>
        <h3>Aggregate</h3>
        <p>Bundle fragmented holdings into portfolio-scale packages.</p>
      </li>
      <li class="flow-arrow" aria-hidden="true">&rarr;</li>
      <li class="flow-step reveal">
        <div class="flow-num">02</div>
        <h3>Monitor &middot; Report &middot; Verify</h3>
        <p>Automated <span class="mono">MRV</span> pipeline. Standard-agnostic.</p>
      </li>
      <li class="flow-arrow" aria-hidden="true">&rarr;</li>
      <li class="flow-step reveal">
        <div class="flow-num">03</div>
        <h3>Certify &amp; Sell</h3>
        <p><span class="mono">CRCF</span>/<span class="mono">VCS</span> certification. Structured offtake agreements.</p>
      </li>
    </ol>
  </div>
</section>

<section class="section section-light" id="team">
  <div class="container">
    <h2 class="section-title reveal">Team</h2>
    <div class="team-grid">
      <article class="team-card reveal">
        <h3>Johannes Brötz</h3>
        <p class="team-role">Forest · Technology</p>
        <ul>
          <li>Carbon consultant (<span class="mono">AFOLU</span>, <span class="mono">REDD+</span>)</li>
          <li>18 years forest management + <span class="mono">GIS</span> + remote sensing</li>
          <li><span class="mono">UNFCCC</span> Roster</li>
        </ul>
      </article>
      <article class="team-card reveal">
        <h3>Esther Mertens</h3>
        <p class="team-role">Carbon Markets · Methodology</p>
        <ul>
          <li>Carbon consultant (<span class="mono">AFOLU</span>, <span class="mono">REDD+</span>)</li>
          <li><span class="mono">UNFCCC</span> Roster</li>
          <li><span class="mono">CRCF</span>, <span class="mono">VCS</span>, Gold Standard expertise</li>
        </ul>
      </article>
    </div>
  </div>
</section>

<section class="section section-dark" id="contact">
  <div class="container">
    <h2 class="section-title reveal">Get in Touch</h2>
    <p class="section-lead reveal">Forest owner, institutional buyer, or potential partner &mdash; we'd like to hear from you.</p>
    <form id="contact-form" class="contact-form reveal" novalidate>
      <div class="form-row">
        <label>
          <span>Name <em>*</em></span>
          <input type="text" name="name" required autocomplete="name">
        </label>
        <label>
          <span>Organisation</span>
          <input type="text" name="organisation" autocomplete="organization">
        </label>
      </div>
      <div class="form-row">
        <label>
          <span>E-Mail <em>*</em></span>
          <input type="email" name="email" required autocomplete="email">
        </label>
        <label>
          <span>Subject <em>*</em></span>
          <select name="subject" required>
            <option value="">Select…</option>
            <option>MRV Consulting</option>
            <option>Carbon Credit Partnership</option>
            <option>Investor Inquiry</option>
            <option>Press / Media</option>
            <option>Other</option>
          </select>
        </label>
      </div>
      <label class="full">
        <span>Message <em>*</em></span>
        <textarea name="message" rows="6" required></textarea>
      </label>
      <div class="hp" aria-hidden="true">
        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </div>
      <button type="submit" class="btn btn-primary">Send message</button>
      <p class="form-fallback">Or write to <a href="mailto:hallo@c-wald.de">hallo@c-wald.de</a>.</p>
      <div class="form-status" id="form-status" role="status" aria-live="polite"></div>
    </form>
  </div>
</section>

</main>

<footer class="footer">
  <div class="container footer-inner">
    <div class="footer-brand">
      <img src="assets/logo.svg" alt="C-Wald" width="28" height="28">
      <div>
        <strong>C-Wald</strong>
        <p>European forests, investable — with integrity.</p>
        <p class="footer-note">Currently in founding phase.</p>
      </div>
    </div>
    <nav class="footer-links" aria-label="Legal">
      <a href="impressum.html">Impressum</a>
      <a href="datenschutz.html">Datenschutz</a>
      <a href="#" id="cookie-settings-link">Cookie settings</a>
    </nav>
    <div class="footer-copy">© 2026 C-Wald | Esther Mertens &amp; Johannes Brötz</div>
  </div>
</footer>

<script src="cookie-banner.js" defer></script>
<script>
(function(){
  var nav = document.getElementById('nav');
  var toggle = document.querySelector('.nav-toggle');
  var menu = document.getElementById('nav-menu');

  function onScroll(){
    if (window.scrollY > 24) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  toggle.addEventListener('click', function(){
    var open = nav.classList.toggle('open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  menu.addEventListener('click', function(e){
    if (e.target.tagName === 'A'){
      nav.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });

  if ('IntersectionObserver' in window){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(en){
        if (en.isIntersecting){
          en.target.classList.add('visible');
          io.unobserve(en.target);
        }
      });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(function(el){ io.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function(el){ el.classList.add('visible'); });
  }

  var form = document.getElementById('contact-form');
  var status = document.getElementById('form-status');
  form.addEventListener('submit', async function(e){
    e.preventDefault();
    status.className = 'form-status';
    status.textContent = 'Sending…';
    var data = new FormData(form);
    try {
      var res = await fetch('send.php', { method: 'POST', body: data, headers: { 'Accept': 'application/json' } });
      var json = await res.json();
      if (json.success){
        status.classList.add('success');
        status.textContent = json.message || 'Thanks — we will be in touch shortly.';
        form.reset();
      } else {
        status.classList.add('error');
        status.textContent = json.message || 'Something went wrong. Please try again.';
      }
    } catch (err){
      status.classList.add('error');
      status.textContent = 'Network error. Please try again or email hallo@c-wald.de.';
    }
  });
})();
</script>
</body>
</html>
