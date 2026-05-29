# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

A **one-page marketing website** for C-Wald (DACH forest-carbon aggregator). Plain
PHP + vanilla JS + CSS. No framework, no bundler, no package.json. Targets PHP 7.4+
on Hetzner shared hosting.

The entire website lives in `public_html/c-wald.eu/`. That path is deliberate — the
repo layout mirrors the server layout so rsync can push `public_html/c-wald.eu/`
directly into `~/public_html/c-wald.eu/` on the server.

## Architecture (the parts worth knowing)

- **`index.php`** — the one-pager. Server-side PHP is only used for the `<title>` /
  OG / canonical URL at the top; everything below that is static HTML. Reveal-on-scroll
  and the contact-form submit handler are inlined `<script>` at the bottom.
- **`send.php`** — contact form backend. POST-only, returns JSON, uses PHP's
  `mail()` to send to `hallo@c-wald.de`. Has: honeypot field (`website`), length
  caps, `FILTER_VALIDATE_EMAIL`, subject allowlist, CRLF-injection check on header
  fields, `Reply-To` set to the submitter, `From: no-reply@c-wald.eu`. Any changes
  here touch spam/abuse surface — preserve all four checks.
- **`cookie-banner.js`** — GDPR consent banner. Stores consent under
  `localStorage["cwald_cookie_consent_v1"]` and fires a `cwald:consent` CustomEvent
  on save. Analytics/marketing categories are wired but currently `comingSoon: true`
  (locked off).
- **`impressum.html`, `datenschutz.html`** — legal pages. Contain `[PLACEHOLDER: …]`
  markers that must be filled in before going live; don't remove markers silently.

## Deploy flow

`git push origin main` → `.github/workflows/deploy.yml` → rsync from
`public_html/c-wald.eu/` to `$SSH_USER@$SSH_HOST:public_html/c-wald.eu/`
using `--delete` (removing a file in the repo removes it from the server).

Requires three GitHub secrets: `SSH_PRIVATE_KEY`, `SSH_HOST`, `SSH_USER`. The
workflow `ssh-keyscan`s the host at run time, so host key changes just work.

Manual re-run: GitHub → Actions → *Deploy to Hetzner* → *Run workflow*.

There is no staging environment. `main` is production.

## Local development

No build step. To preview locally:

```bash
php -S localhost:8000 -t public_html/c-wald.eu
```

No tests, no linter configured. If changing `send.php`, sanity-check syntax with
`php -l public_html/c-wald.eu/send.php` before pushing.

## Things that will bite you

- **SPF**: `send.php` sends via `mail()` with `From: no-reply@c-wald.eu`. The
  domain's DNS must include Hetzner's outbound MTAs in SPF or mails get filtered.
- **`rsync --delete`**: anything you remove from `public_html/c-wald.eu/` is
  deleted from the server on the next push. The workflow has top-level excludes
  (`.git/`, `.github/`, `README.md`, `.env`, `*.log`) as a safeguard in case the
  source path is ever widened.
- **Logo**: `assets/logo.svg` is referenced from `index.php`, `impressum.html`, and
  `datenschutz.html`. If swapping to `.png`, update all three.
- **`.gitignore` protects deploy keys**: `c-wald.keys*`, `*.pem`, `id_rsa`,
  `id_ed25519` are ignored. Don't commit them, ever.

## What not to do

- Don't add a build step, package.json, or framework. The site is plain PHP/JS/CSS
  on purpose — it has to run on Hetzner shared hosting with zero setup.
- Don't restructure `public_html/c-wald.eu/`. The path mirrors the server; breaking
  it breaks deploy.
- Don't push directly to the server. Push to `main` and let the workflow deploy.
- Ignore the `.claude/`, `.claude-flow/`, and `.mcp.json` directories — they're
  local Claude Code tooling, gitignored, and unrelated to the website.
