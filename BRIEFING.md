# Briefing: Rostock-Landingpage für Waldbesitzer auf c-wald.eu

> Lies dieses Dokument komplett, bevor du Code schreibst. Es beschreibt
> Kontext, Inhalt, Design und Akzeptanzkriterien. Beachte besonders
> Abschnitt 2 (Exploration) und Abschnitt 8 (Workflow): erst Findings
> und Vorschlag, dann mein OK, dann implementieren. Frag lieber einmal
> zu viel als zu wenig – ich bin Förster mit etwas Python-/React-Erfahrung,
> kein professioneller Entwickler. Erklär mir Entscheidungen kurz.

---

## 1 · Kontext

**C-Wald** bündelt fragmentierten Privat- und Körperschaftswald im DACH-Raum zu
projektfähigen Kohlenstoff-Portfolios. Die bestehende Website c-wald.eu richtet
sich aktuell an **institutionelle Käufer/Investoren** (englisch, „Making Forests
Investable").

Diese Aufgabe baut die **andere Marktseite** an: eine **deutschsprachige
Landingpage für Waldbesitzer** (Privat, Kommunal, Stiftung, Landesforst),
gedacht für die **Forstvereinstagung Rostock 2026**. Conversion-Ziel ist eine
**Warteliste** – Waldbesitzer tragen sich ein, damit C-Wald sie aufnimmt, sobald
der EU-Rahmen (CRCF) Wald-Zertifizierungen erlaubt (Sommer 2026 erwartet).

**Wichtig**: Die Investoren-Hauptseite bleibt unangetastet. Die Waldbesitzer-
Landingpage ist eine **eigene Strecke** (eigene Route/URL), weil Zielgruppe,
Sprache und Tonfall komplett anders sind.

**Tonfall**: sachlich, glaubwürdig, kein Greenwashing-Vokabular, Sie-Form.
„C-Wald ist Infrastruktur, kein Versprechen."

---

## 2 · Bevor du Code schreibst: explorieren

Verschaff dir ein Bild vom Repo und stell mir deine Findings vor:

1. Wie ist die Single Page technisch gebaut? Statisches HTML/CSS/JS? Ein
   Framework (Astro, Vue, React, SvelteKit, 11ty, plain Vite)? Ein Generator?
2. Gibt es ein Build-Tool? Wie wird gebaut und deployed (CI, Skript, manuell)?
3. Gibt es Routing, oder ist alles eine einzige `index.html` mit Anker-Sektionen?
   → Daraus folgt, ob die Rostock-Seite eine eigene Route (`/rostock`), eine
   eigene Datei (`rostock.html`) oder eine eigene Seite im Framework wird.
4. Wo liegen die bestehenden Design-Tokens / Styles / Komponenten?
   **Die neue Seite soll die vorhandene Designsprache wiederverwenden** –
   gleiche Schriften, gleiche Farbvariablen, gleiche Button-/Section-Patterns.
5. Welche Farb- und Font-Definitionen nutzt die Seite aktuell? (theme-color ist
   `#00361a` Dunkelgrün – prüfe, ob es CSS-Variablen dafür gibt.)
6. Gibt es schon ein Formular irgendwo? Ein Backend? Eine Serverless-Funktion?
   Einen Mail-Versand? → entscheidend für die Warteliste (siehe Abschnitt 6).
7. Wie ist das Hosting? Statisch (Netlify, Cloudflare Pages, GitHub Pages, S3)?
   Eigener Server? Docker? Das beeinflusst die Formular-Optionen.
8. Branch-Workflow: direkt auf main oder Feature-Branch + Review?

**Stop nach der Exploration. Fass zusammen, was du gefunden hast, und schlag vor,
(a) wie die Rostock-Seite eingehängt wird und (b) wie das Warteliste-Formular
gespeichert/verschickt wird. Dann gebe ich OK.**

---

## 3 · Was zu bauen ist

Eine eigenständige Landingpage, erreichbar unter einer eigenen URL
(Vorschlag: `c-wald.eu/rostock` – finale Form richtet sich nach dem Routing,
das du in der Exploration findest).

Inhalt, Reihenfolge der Sektionen, Texte und grobe Optik sind in der
**Referenz-Implementierung** vollständig vorgegeben:

> **`REFERENCE-cwald-rostock.html`** (liegt im selben Ordner)

Diese Datei ist eine funktionierende, self-contained HTML-Version. Sie ist die
**verbindliche Vorlage für Inhalt und Aufbau**. Übernimm:
- die Sektionsreihenfolge (Hero → Problem → So funktioniert C-Wald → Nutzen →
  Warteliste-Begründung → Team → Warteliste-Formular → Footer)
- alle Texte **wortwörtlich** (sie sind abgestimmt)
- die inhaltliche Struktur der Stat-Karten, Schritte, Nutzen-Liste

**Aber**: Setz das Design mit den **bestehenden Mitteln der C-Wald-Codebase** um
(deren CSS-Variablen, Schriften, Komponenten), statt die Inline-Styles aus der
Referenz blind zu kopieren. Die Referenz zeigt das Ziel; die Umsetzung soll sich
sauber in die vorhandene Architektur einfügen und konsistent mit der Hauptseite
wirken. Wenn die Hauptseite z. B. schon eine `Section`- oder `Button`-Komponente
hat, nutze die.

---

## 4 · Designrichtung

- Primärfarbe Dunkelgrün `#00361a` (Theme-Color), plus hellere Grünabstufungen
  und ein warmes, sehr sparsam eingesetztes Sand/Ocker als Akzent (in der
  Referenz `#b89a5a`, nur am Border der „Warum jetzt"-Sektion).
- Seriöse Anmutung: Serif-Headlines (Referenz nutzt „Source Serif 4"), saubere
  Sans für Fließtext. Falls die bestehende Seite eigene Marken-Schriften hat,
  nutze die – Konsistenz mit der Hauptseite geht vor.
- Großzügiger Weißraum, ruhige Abschnitte, keine verspielten Effekte. C-Wald
  ist Investment-grade-glaubwürdig, nicht Tech-Startup-bunt.
- Mobile-first. Die meisten Tagungsbesucher öffnen die Seite am Handy.

---

## 5 · Conversion-Ziel: Warteliste

Das Formular ist der eigentliche Zweck der Seite. Felder:

| Feld | Pflicht | Hinweis |
|---|---|---|
| Name | ja | |
| E-Mail | ja | für die Rückmeldung |
| Waldfläche (ha, ungefähr) | ja | Platzhalter „z. B. 12 ha" |
| Region / Bundesland | ja | |
| Art der Trägerschaft | nein | Dropdown: Privatwald, Kommunalwald, Stiftung, Landesforst, Sonstige |
| Einwilligung (DSGVO) | ja | „Ich bin mit der Speicherung meiner Angaben zur Kontaktaufnahme einverstanden." |

Nach erfolgreichem Absenden: kurze Bestätigung statt Formular, z. B.
„Danke. Sie stehen auf der Liste. Wir melden uns, sobald wir Ihre Region und
Flächengröße in ein Portfolio aufnehmen können."

---

## 6 · Backend für die Warteliste (Entscheidung nötig)

Bei einer statischen Single Page gibt es kein eingebautes Backend. Kläre in der
Exploration, was vorhanden ist, und schlag mir eine Option vor:

- **Externer Form-Service** (Formspree, Web3Forms, Tally-Embed) – am schnellsten,
  DSGVO-konform konfigurierbar, kein eigener Server nötig.
- **Serverless-Funktion** (Netlify Functions, Cloudflare Workers) – falls das
  Hosting das hergibt und du die Daten selbst halten willst.
- **Bestehendes Backend / Mailversand** – falls in der Exploration etwas
  Passendes auftaucht.

Was ich **nicht** will:
- Mailto-Link als „Formular"
- Speicherung der Daten bei einem Dienst ohne AV-Vertrag / außerhalb EU ohne
  Prüfung (DSGVO – das ist bei Waldbesitzerdaten heikel)

Empfehlung gerne mit kurzer Begründung. Ich entscheide dann.

---

## 7 · Tracking

- Falls Analytics auf der Seite existiert (prüfen): Seitenaufruf für die
  Rostock-URL und ein Event für erfolgreichen Formular-Submit ergänzen.
- Keine UTM-Parameter nötig – die Seite ist per Definition der Rostock-
  Einstieg.

---

## 8 · Workflow

1. Exploration (Abschnitt 2) → Findings + Vorschlag → **mein OK abwarten**.
2. Feature-Branch anlegen, z. B. `feature/rostock-landing`.
3. Seite bauen, lokal testen (Dev-Server).
4. Formular-Anbindung testen: echter Submit → Daten kommen an / Mail trifft ein.
5. Mir zeigen, wie ich es lokal oder auf einer Preview-URL ansehen kann.
6. Erst nach meinem OK → Merge → Deployment.

---

## 9 · Akzeptanzkriterien

- [ ] Eigene URL (z. B. `/rostock`), Direktaufruf funktioniert (kein 404)
- [ ] Investoren-Hauptseite unverändert
- [ ] Design konsistent mit bestehender C-Wald-Seite (Farben, Schriften, Komponenten)
- [ ] Alle Texte wortwörtlich aus der Referenz übernommen
- [ ] Sektionsreihenfolge wie in der Referenz
- [ ] Formular submittet → Daten landen wo vereinbart → Bestätigung erscheint
- [ ] Pflichtfeld-Validierung inkl. DSGVO-Checkbox
- [ ] Mobile sauber, Hero-CTA springt zum Formular
- [ ] Lighthouse Mobile: Performance ≥ 85, Accessibility ≥ 95
- [ ] Keine Console-Errors, kein Dead Code
- [ ] Commit-Messages nach Repo-Konvention

---

## 10 · Nicht-Ziele

- Keine Änderung an der englischen Investoren-Hauptseite
- Kein Rebranding, keine neuen Markenfarben über die Landingpage hinaus
- Kein Login/Auth (Seite ist öffentlich)
- Keine Verkaufsversprechen im Text, die über die Referenz hinausgehen
  (Greenwashing-Risiko – Ton bleibt „Infrastruktur, kein Versprechen")
