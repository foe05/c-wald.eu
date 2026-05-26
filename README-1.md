# C-Wald Rostock-Landingpage → Build via Claude Code

## Workflow

### 1 · Auf die VM / ins Projekt kopieren

```bash
scp -r cwald-rostock-deploy/ deine-vm:/tmp/
```

### 2 · Ins c-wald.eu-Repo wechseln und Claude Code starten

```bash
ssh deine-vm
cd /pfad/zu/c-wald
claude
```

### 3 · Diesen Trigger-Prompt einfügen

```
Lies das Briefing unter /tmp/cwald-rostock-deploy/BRIEFING.md komplett durch.
Die verbindliche Inhalts- und Aufbau-Vorlage ist
/tmp/cwald-rostock-deploy/REFERENCE-cwald-rostock.html.

Beachte Abschnitt 2 (Exploration) und Abschnitt 8 (Workflow): ich will erst
eine Zusammenfassung deiner Findings über den Aufbau dieser Seite + einen
Vorschlag, wie du die Rostock-Landingpage einhängst UND wie du das
Warteliste-Formular speicherst/verschickst — BEVOR du Code schreibst.
Erklär mir Entscheidungen kurz, ich bin kein Profi-Entwickler.
```

### 4 · Im Dialog bleiben

Claude Code wird Findings vorstellen und vermutlich fragen:
- Welche **Form-Backend-Option** du willst (Formspree / Serverless / vorhandenes)
- Wie die **Route** aussehen soll (`/rostock` o. ä.)
- Ob er einen **Branch + Review** macht oder direkt deployt

Antworte, gib OK, dann baut er.

---

## Was im Ordner ist

| Datei | Zweck |
|---|---|
| `BRIEFING.md` | Vollständiger Auftrag inkl. Inhalt, Design, Backend-Optionen, Akzeptanzkriterien |
| `REFERENCE-cwald-rostock.html` | Funktionierende HTML-Vorlage – verbindlich für Texte + Aufbau, frei in der technischen Umsetzung |

---

## Wichtig vor dem Livegang

- **Investoren-Hauptseite bleibt unangetastet** – die Rostock-Seite ist eine eigene Strecke.
- **Formular wirklich testen**: einmal absenden, prüfen dass die Daten/Mail ankommen. Sonst stehst du in Rostock mit einer toten Warteliste da.
- **DSGVO**: Bei der Backend-Wahl auf EU-Hosting / AV-Vertrag achten – es geht um personenbezogene Waldbesitzerdaten.
- **Mobil testen** – die meisten Tagungsbesucher öffnen die Seite am Handy.

## Wenn was hakt

Wenn Claude Code in eine Sackgasse läuft oder eine Entscheidung trifft, die dir
komisch vorkommt: stop ihn, beschreib mir hier das Problem, ich helfe bei der
Klärung – besonders bei der Backend-/DSGVO-Frage.
