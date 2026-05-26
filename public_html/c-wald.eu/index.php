<?php
$canonical = 'https://c-wald.eu/';
$title = 'C-Wald — Making European Forests Investable, with Integrity';
$description = 'C-Wald aggregates fragmented private forest holdings in DACH into certified carbon credit portfolios. MRV-automated, CRCF-ready.';

// NOTE: hero image URL is a placeholder sourced from Stitch template (Google AIDA CDN).
// Replace with a locally-hosted forest photo in assets/ before going live.
$heroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAlX0v3EDzi63Z8xStyPBWzihX0oC4Aefcw_92rTe0hDb-UvlkP523pyYnCzXQU5-1DWPmYn70v6GLOb5nXpBDwm4JMtOJoRwL3VjUBXs-Q7KlVD5XVlaJ_OcKZyXbz1BcDZ2Rc_D8lNAGLEQ--YrwgIn9srRMwhkcO5Zv_1r1odmUiLAvlP2w3uqXAQJ_MrVxh-sJLsIoXHNPEdQl8c5zd68wMRXsAGLPj3lDf72Ae0hGemxNQpaMimX8-6zncbYDxy1ipzsnr-ufw';
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
<meta name="theme-color" content="#00361a">
<link rel="icon" type="image/svg+xml" href="assets/logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="nav" id="nav">
  <div class="nav-inner">
    <a href="#top" class="nav-logo" aria-label="C-Wald home">
      <img src="assets/logo.svg" alt="" width="36" height="36">
      <span>C-WALD</span>
    </a>
    <nav class="nav-menu" id="nav-menu" aria-label="Primary">
      <a href="#about">Forest Assets</a>
      <a href="#how">How It Works</a>
      <a href="#team">Team</a>
      <a href="#contact">Contact</a>
    </nav>
    <a href="#contact" class="btn btn-primary nav-cta">Get in Touch</a>
    <button class="nav-toggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="nav-menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<main id="main">

<section class="hero" id="top">
  <div class="hero-image" style="background-image:url('<?= htmlspecialchars($heroImage) ?>')" aria-hidden="true"></div>
  <div class="hero-overlay" aria-hidden="true"></div>
  <div class="hero-inner container">
    <span class="eyebrow reveal">The Digital Arboretum · DACH · CRCF-ready</span>
    <h1 class="hero-title reveal">Making European Forests <em>Investable</em> — with Integrity.</h1>
    <p class="hero-lead reveal">C-Wald aggregates fragmented private forest holdings in DACH into certified carbon credit portfolios. MRV-automated, CRCF-ready.</p>
    <div class="hero-cta reveal">
      <a href="#contact" class="btn btn-primary">Get in Touch</a>
      <a href="#how" class="btn btn-ghost">Explore Assets</a>
    </div>
  </div>
</section>

<section class="section section-problem" id="about">
  <div class="container">
    <div class="problem-grid">
      <div class="problem-head reveal">
        <span class="eyebrow">The Problem</span>
        <h2>The Fragmentation Bottleneck.</h2>
        <p>A structural supply gap — against rapidly growing institutional demand for high-integrity European removals.</p>
      </div>
      <div class="stat-grid">
        <article class="stat-card reveal">
          <span class="stat-label-tiny">Market Barrier 01</span>
          <div class="stat-number">3<span class="stat-unit">ha</span></div>
          <p class="stat-label">Average private forest holding in Germany</p>
          <p class="stat-note">Too small for institutional investors to manage individually.</p>
        </article>
        <article class="stat-card reveal">
          <span class="stat-label-tiny">Market Barrier 02</span>
          <div class="stat-number">€50k<span class="stat-unit">+</span></div>
          <p class="stat-label">Minimum VCS certification cost</p>
          <p class="stat-note">Prohibitive upfront cost for standard MRV processes.</p>
        </article>
        <article class="stat-card reveal">
          <span class="stat-label-tiny">Market Barrier 03</span>
          <div class="stat-number">0</div>
          <p class="stat-label">CRCF-certified forest carbon farming projects in Europe today</p>
          <p class="stat-note">No existing supply to meet emerging regulatory demand.</p>
        </article>
      </div>
    </div>
  </div>
</section>

<section class="section section-methodology" id="how">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Process</span>
      <h2>Our Methodology.</h2>
    </div>
    <div class="bento-grid">
      <article class="bento-card reveal">
        <span class="bento-num" aria-hidden="true">01</span>
        <div class="bento-icon" aria-hidden="true"><span class="material-symbols-outlined">layers</span></div>
        <h3>Aggregate</h3>
        <p>We bundle fragmented forest holdings into portfolio-scale packages, creating the volume required for institutional participation.</p>
      </article>
      <article class="bento-card reveal">
        <span class="bento-num" aria-hidden="true">02</span>
        <div class="bento-icon" aria-hidden="true"><span class="material-symbols-outlined">monitoring</span></div>
        <h3>Monitor, Report, Verify</h3>
        <p>Automated MRV pipeline, standard-agnostic. Continuous high-precision monitoring of forest health and carbon stock.</p>
        <div class="growth-bar" aria-hidden="true"><div class="growth-bar-fill"></div></div>
      </article>
      <article class="bento-card reveal">
        <span class="bento-num" aria-hidden="true">03</span>
        <div class="bento-icon" aria-hidden="true"><span class="material-symbols-outlined">verified</span></div>
        <h3>Certify &amp; Sell</h3>
        <p>CRCF and VCS certification. Structured offtake agreements delivering direct financial returns to forest stewards.</p>
      </article>
    </div>
  </div>
</section>

<section class="section section-team" id="team">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Leadership</span>
      <h2>Experts at the Intersection of Forestry &amp; Finance.</h2>
    </div>
    <div class="team-grid">
      <article class="team-card reveal">
        <div class="team-avatar" aria-hidden="true">JB</div>
        <div class="team-body">
          <h3>Johannes Brötz</h3>
          <p class="team-role">Forest · Technology</p>
          <ul>
            <li>Carbon consultant (AFOLU, REDD+)</li>
            <li>18 years forest management, GIS and remote sensing</li>
            <li>UNFCCC Roster</li>
          </ul>
        </div>
      </article>
      <article class="team-card reveal">
        <div class="team-avatar" aria-hidden="true">EM</div>
        <div class="team-body">
          <h3>Esther Mertens</h3>
          <p class="team-role">Carbon Markets · Methodology</p>
          <ul>
            <li>Carbon consultant (AFOLU, REDD+)</li>
            <li>UNFCCC Roster</li>
            <li>CRCF, VCS and Gold Standard expertise</li>
          </ul>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="section section-contact" id="contact">
  <div class="container">
    <div class="contact-card reveal">
      <div class="contact-info">
        <span class="eyebrow">Get in Touch</span>
        <h2>Start Your Forest Investment Journey.</h2>
        <p>Forest owner, institutional buyer, or potential partner — whether you're seeking certification or impact assets, our team is ready to guide you.</p>
        <ul class="contact-meta">
          <li>
            <span class="contact-icon" aria-hidden="true"><span class="material-symbols-outlined">mail</span></span>
            <a href="mailto:hallo@c-wald.eu">hallo@c-wald.eu</a>
          </li>
        </ul>
      </div>
      <form id="contact-form" class="contact-form" novalidate>
        <div class="form-row">
          <label>
            <span>Name <em>*</em></span>
            <input type="text" name="name" required autocomplete="name" placeholder="Jane Doe">
          </label>
          <label>
            <span>Organisation</span>
            <input type="text" name="organisation" autocomplete="organization" placeholder="Forest Holdings Ltd.">
          </label>
        </div>
        <div class="form-row">
          <label>
            <span>E-Mail <em>*</em></span>
            <input type="email" name="email" required autocomplete="email" placeholder="jane@example.com">
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
          <textarea name="message" rows="5" required placeholder="Tell us about your project…"></textarea>
        </label>
        <div class="hp" aria-hidden="true">
          <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>
        <button type="submit" class="btn btn-primary btn-send">
          Send Message
          <span class="material-symbols-outlined" aria-hidden="true">send</span>
        </button>
        <p class="form-fallback">Or write to <a href="mailto:hallo@c-wald.eu">hallo@c-wald.eu</a>.</p>
        <div class="form-status" id="form-status" role="status" aria-live="polite"></div>
      </form>
    </div>
  </div>
</section>

</main>

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="footer-wordmark">C-WALD</div>
      <p>© 2026 C-Wald · Esther Mertens &amp; Johannes Brötz · Cultivating European Forest Resilience.</p>
    </div>
    <nav class="footer-links" aria-label="Legal">
      <a href="datenschutz.html">Privacy Policy</a>
      <a href="impressum.html">Imprint</a>
      <a href="#" id="cookie-settings-link">Cookie Settings</a>
      <a href="#" aria-disabled="true">LinkedIn</a>
    </nav>
  </div>
</footer>

<script src="cookie-banner.js" defer></script>
<script>
(function(){
  var nav = document.getElementById('nav');
  var toggle = document.querySelector('.nav-toggle');
  var menu = document.getElementById('nav-menu');

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
      status.textContent = 'Network error. Please try again or email hallo@c-wald.eu.';
    }
  });
})();
</script>
</body>
</html>
