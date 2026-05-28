<?php
require_once __DIR__ . '/../lib/telemetry.php';
cwald_telemetry_send('rostock_loaded');

$canonical = 'https://c-wald.eu/rostock/';
$title = 'C-Wald · Kohlenstoffmarkt für Ihren Wald · Rostock 2026';
$description = 'C-Wald bündelt kleine und mittlere Waldflächen zu zertifizierungsfähigen Kohlenstoff-Portfolios. Tragen Sie sich auf die Warteliste ein.';
?>
<!doctype html>
<html lang="de">
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
<meta property="og:image" content="https://c-wald.eu/assets/logo.svg">
<meta name="theme-color" content="#00361a">
<link rel="icon" type="image/svg+xml" href="../assets/logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body>

<a class="skip-link" href="#warteliste">Direkt zur Warteliste</a>

<header class="site-header">
  <div class="wrap">
    <a class="brand-lockup" href="https://c-wald.eu/" aria-label="C-Wald Startseite">
      <span class="brand-mark" aria-hidden="true">C</span>
      <span class="brand-name">C-WALD</span>
    </a>
    <a href="#warteliste" class="header-cta">Auf die Warteliste</a>
  </div>
</header>

<main>

<section class="hero">
  <div class="wrap">
    <div class="eyebrow">Forstvereinstagung Rostock 2026 · Für Waldbesitzer</div>
    <h1>Der Kohlenstoffmarkt war bisher nur etwas für <em>große</em> Flächen.</h1>
    <p class="hero-sub">C-Wald bündelt kleine und mittlere Waldflächen zu zertifizierungsfähigen Kohlenstoff-Portfolios. Sie behalten Ihren Wald – und erhalten bis zu 70 % des Erlöses aus den Kohlenstoffzertifikaten. Ohne eigene Zertifizierungskosten, ohne MRV-Know-how.</p>
    <div class="hero-actions">
      <a href="#warteliste" class="btn-primary">Platz im ersten Cluster sichern</a>
      <a href="#so-funktionierts" class="btn-ghost">Wie funktioniert das?</a>
    </div>
    <div class="hero-note">Die EU-Methoden für Wald-Kohlenstoff werden Sommer 2026 erwartet. Wir bauen die Cluster jetzt auf.</div>
  </div>
</section>

<section class="problem">
  <div class="wrap">
    <div class="section-label">Das Problem</div>
    <h2 class="section-title">Allein zu klein für den Markt.</h2>
    <p class="section-intro">Der Kohlenstoffmarkt verlangt projektfähige Flächengrößen und hohe Vorab-Investitionen. Für den durchschnittlichen Waldbesitzer ist das allein nicht zu stemmen.</p>
    <div class="stat-grid">
      <article class="stat-card">
        <div class="barrier">Hürde 01</div>
        <div class="figure">3 ha</div>
        <p class="desc">Durchschnittliche Größe eines Privatwald-Besitzes in Deutschland – zu klein, um als eigenständiges Carbon-Projekt zu funktionieren.</p>
      </article>
      <article class="stat-card">
        <div class="barrier">Hürde 02</div>
        <div class="figure">50.000 €+</div>
        <p class="desc">Typische Mindestkosten für eine Zertifizierung nach gängigen Standards. Eine Vorab-Investition, die sich für einzelne Flächen nie rechnet.</p>
      </article>
      <article class="stat-card">
        <div class="barrier">Hürde 03</div>
        <div class="figure">~2.000 ha</div>
        <p class="desc">Flächengröße, ab der ein Kohlenstoff-Portfolio projektfähig wird. Erreichbar nur durch Bündelung vieler kleiner Flächen.</p>
      </article>
    </div>
  </div>
</section>

<section class="solution" id="so-funktionierts">
  <div class="wrap">
    <div class="section-label">So funktioniert C-Wald</div>
    <h2 class="section-title">Wir schließen die Lücke.</h2>
    <p class="section-intro">C-Wald übernimmt den gesamten technischen und organisatorischen Aufwand. Sie bringen Ihre Waldfläche ein – wir kümmern uns um den Rest.</p>
    <div class="steps">
      <article class="step">
        <div class="num">01 · Bündeln</div>
        <h3>Aggregation</h3>
        <p>Wir fassen viele einzelne Waldflächen zu einem Portfolio in projektfähiger Größe zusammen. Die Klärung der Kohlenstoffrechte gehört dazu – transparent und nachvollziehbar.</p>
      </article>
      <article class="step">
        <div class="num">02 · Messen</div>
        <h3>Monitoring &amp; Verifikation</h3>
        <p>Satellitengestütztes Monitoring (Sentinel-2, ESA CCI Biomass, GEDI) kombiniert mit Feldverifikation. Eigene MRV-Software, standard-agnostisch aufgebaut – für CRCF, VCS und Gold Standard.</p>
      </article>
      <article class="step">
        <div class="num">03 · Zertifizieren</div>
        <h3>Zertifizierung &amp; Verkauf</h3>
        <p>Wir navigieren durch die Zertifizierungswege und verkaufen die verifizierten Kohlenstoff-Credits an institutionelle Käufer. Der Erlös fließt direkt an die beteiligten Waldbesitzer zurück.</p>
      </article>
    </div>
  </div>
</section>

<section class="benefit">
  <div class="wrap">
    <div class="benefit-grid">
      <div>
        <div class="section-label">Ihr Nutzen</div>
        <h2 class="section-title">Zugang zum Markt – ohne Risiko, ohne Vorkosten.</h2>
        <ul class="benefit-list">
          <li><span class="tick" aria-hidden="true">✓</span> <div><strong>Keine Zertifizierungskosten für Sie.</strong> C-Wald trägt die Vorab-Investition in Zertifizierung und MRV.</div></li>
          <li><span class="tick" aria-hidden="true">✓</span> <div><strong>Kein technisches Know-how nötig.</strong> Messung, Berichterstattung und Verifikation übernehmen wir vollständig.</div></li>
          <li><span class="tick" aria-hidden="true">✓</span> <div><strong>Sie behalten Ihren Wald.</strong> Es geht um die Vergütung der Kohlenstoffleistung, nicht um Ihr Eigentum.</div></li>
          <li><span class="tick" aria-hidden="true">✓</span> <div><strong>Faire Beteiligung.</strong> Bis zu 70 % des Credit-Erlöses gehen an Sie als Waldbesitzer.</div></li>
        </ul>
      </div>
      <aside class="benefit-figure">
        <div class="big">70<span>%</span></div>
        <p class="cap">Bis zu 70 % des Erlöses aus den Kohlenstoffzertifikaten fließen an die beteiligten Waldbesitzer.</p>
      </aside>
    </div>
  </div>
</section>

<section class="status">
  <div class="wrap-narrow">
    <div class="section-label">Warum jetzt eine Warteliste</div>
    <h2 class="section-title status-headline">Wir positionieren uns, bevor der Markt öffnet.</h2>
    <div class="status-box">
      <p>Der EU-Rahmen für die Zertifizierung von Kohlenstoffentnahmen (<strong>CRCF</strong>) ist im Aufbau. Die konkreten Methoden für Wald-Kohlenstoff werden im <strong>Sommer 2026</strong> erwartet. Bis dahin sind in Europa noch keine forstlichen Zertifizierungen möglich.</p>
      <p>Das ist kein Nachteil, sondern Ihr Vorteil: Wer jetzt im Cluster ist, steht bereit, sobald die ersten Projekte starten können. Wir nutzen die Zeit für den Aufbau der Portfolios und die Klärung der Kohlenstoffrechte.</p>
      <p><strong>C-Wald ist Infrastruktur, kein Versprechen.</strong> Wir kommunizieren offen, was heute geht und was noch nicht.</p>
    </div>
  </div>
</section>

<section class="team">
  <div class="wrap">
    <div class="section-label">Wer dahintersteht</div>
    <h2 class="section-title">Forst, Fernerkundung und Kohlenstoffmärkte.</h2>
    <div class="team-grid">
      <article class="person">
        <div class="person-head">
          <div class="person-avatar" aria-hidden="true">JB</div>
          <div>
            <div class="person-name">Johannes Brötz</div>
            <div class="person-role">Forst · Technologie</div>
          </div>
        </div>
        <ul>
          <li>18 Jahre Forstpraxis, GIS und Fernerkundung</li>
          <li>Carbon Consultant (AFOLU, REDD+)</li>
          <li>UNFCCC Roster of Experts</li>
        </ul>
      </article>
      <article class="person">
        <div class="person-head">
          <div class="person-avatar" aria-hidden="true">EM</div>
          <div>
            <div class="person-name">Esther Mertens</div>
            <div class="person-role">Carbon Markets · Methodik</div>
          </div>
        </div>
        <ul>
          <li>Carbon Consultant (AFOLU, REDD+)</li>
          <li>Expertise in CRCF, VCS und Gold Standard</li>
          <li>UNFCCC Roster of Experts</li>
        </ul>
      </article>
    </div>
  </div>
</section>

<section class="waitlist" id="warteliste">
  <div class="wrap-narrow">
    <div class="section-label">Warteliste</div>
    <h2>Sichern Sie sich einen Platz im ersten C-Wald-Cluster.</h2>
    <p class="lead">Tragen Sie sich unverbindlich ein. Wir melden uns, sobald wir Ihre Region und Flächengröße in ein Portfolio aufnehmen können – und halten Sie über die Entwicklung des Marktes auf dem Laufenden.</p>

    <form id="waitlist-form" class="form-card" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" required autocomplete="name" placeholder="Vor- und Nachname">
        </div>
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input type="email" id="email" name="email" required autocomplete="email" placeholder="ihre@email.de">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="flaeche">Waldfläche <span class="opt">(ha, ungefähr)</span></label>
          <input type="text" id="flaeche" name="flaeche" required placeholder="z. B. 12 ha">
        </div>
        <div class="form-group">
          <label for="region">Region / Bundesland</label>
          <input type="text" id="region" name="region" required placeholder="z. B. Mecklenburg-Vorpommern">
        </div>
      </div>
      <div class="form-group">
        <label for="traegerschaft">Art der Trägerschaft <span class="opt">(freiwillig)</span></label>
        <select id="traegerschaft" name="traegerschaft">
          <option value="">Bitte wählen…</option>
          <option>Privatwald</option>
          <option>Kommunalwald</option>
          <option>Stiftung</option>
          <option>Landesforst</option>
          <option>Sonstige</option>
        </select>
      </div>

      <div class="form-group consent">
        <label class="checkbox-label">
          <input type="checkbox" name="einwilligung" value="ja" required>
          <span>Ich bin mit der Speicherung meiner Angaben zur Kontaktaufnahme einverstanden. <a href="../datenschutz.html" target="_blank" rel="noopener">Datenschutz</a></span>
        </label>
      </div>

      <div class="hp" aria-hidden="true">
        <label>Webseite<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </div>

      <button type="submit" class="btn-submit">Auf die Warteliste setzen</button>

      <div class="form-trust">
        <span>Unverbindlich</span>
        <span>Keine Weitergabe an Dritte</span>
        <span>Jederzeit widerrufbar</span>
      </div>

      <div class="form-status" id="form-status" role="status" aria-live="polite"></div>
    </form>
  </div>
</section>

</main>

<footer class="site-footer">
  <div class="wrap">
    <div>
      C-WALD · Esther Mertens &amp; Johannes Brötz<br>
      <a href="mailto:hallo@c-wald.eu">hallo@c-wald.eu</a>
    </div>
    <nav class="legal" aria-label="Rechtliches">
      <a href="https://c-wald.eu/">c-wald.eu</a>
      <a href="../impressum.html">Impressum</a>
      <a href="../datenschutz.html">Datenschutz</a>
    </nav>
  </div>
</footer>

<script src="../cookie-banner.js" defer></script>
<script>
(function(){
  var form = document.getElementById('waitlist-form');
  var status = document.getElementById('form-status');

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    status.className = 'form-status';
    status.textContent = 'Wird gesendet…';

    var data = new FormData(form);
    try {
      var res = await fetch('submit.php', {
        method: 'POST',
        body: data,
        headers: { 'Accept': 'application/json' }
      });
      var json = await res.json();
      if (json.success){
        // Replace the form content with a confirmation message.
        form.innerHTML =
          '<div class="form-success">' +
            '<h3>Danke. Sie stehen auf der Liste.</h3>' +
            '<p>Wir melden uns, sobald wir Ihre Region und Flächengröße in ein Portfolio aufnehmen können.</p>' +
          '</div>';
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        status.classList.add('error');
        status.textContent = json.message || 'Etwas ist schiefgelaufen. Bitte versuchen Sie es erneut.';
      }
    } catch (err){
      status.classList.add('error');
      status.textContent = 'Netzwerkfehler. Bitte versuchen Sie es erneut oder schreiben Sie an hallo@c-wald.eu.';
    }
  });
})();
</script>
</body>
</html>
