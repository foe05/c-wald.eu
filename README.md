# c-wald.eu

One-page website for **C-Wald** — aggregating fragmented private forest holdings
in the DACH region into certified carbon credit portfolios.

## Repo layout

```
.
├── .github/workflows/deploy.yml   # CI: rsync to Hetzner on push to main
├── public_html/c-wald.eu/         # The actual site — mirrors server layout
│   ├── index.php                  # one-pager (hero, problem, flow, team, contact)
│   ├── send.php                   # contact form JSON handler
│   ├── style.css
│   ├── cookie-banner.js           # GDPR consent (necessary only, extensible)
│   ├── impressum.html             # § 5 TMG legal notice (has PLACEHOLDERs)
│   ├── datenschutz.html           # GDPR privacy notice (has PLACEHOLDERs)
│   └── assets/
└── README.md
```

No build step. Plain PHP + vanilla JS + CSS. Targets PHP 7.4+ (Hetzner shared hosting).

## Deployment

Every push to `main` triggers `.github/workflows/deploy.yml`, which rsyncs
`public_html/c-wald.eu/` from the repo to `~/public_html/c-wald.eu/` on the
Hetzner server over SSH. `rsync --delete` keeps the server in sync, so files
removed from the repo are also removed from the server.

To deploy: **push to `main`**. To re-run manually: GitHub → Actions → *Deploy to
Hetzner* → *Run workflow*.

### Required GitHub Secrets

Repo → Settings → Secrets and variables → Actions → *New repository secret*:

| Secret            | Value                                                        |
| ----------------- | ------------------------------------------------------------ |
| `SSH_PRIVATE_KEY` | Private key (full contents, incl. `-----BEGIN…-----` lines)  |
| `SSH_HOST`        | Hetzner server hostname or IP (e.g. `u1234.your-storagebox.de` or `c-wald.eu`) |
| `SSH_USER`        | SSH username on the Hetzner webspace                         |

### SSH key setup on the server

On a local machine (one-time):

```bash
ssh-keygen -t ed25519 -f ~/.ssh/cwald_deploy -C "github-actions-deploy" -N ""
```

Append the **public** key to the server's `authorized_keys`:

```bash
ssh SSH_USER@SSH_HOST "mkdir -p ~/.ssh && chmod 700 ~/.ssh && cat >> ~/.ssh/authorized_keys" < ~/.ssh/cwald_deploy.pub
ssh SSH_USER@SSH_HOST "chmod 600 ~/.ssh/authorized_keys"
```

Paste the contents of `~/.ssh/cwald_deploy` (the **private** key, including the
`BEGIN`/`END` lines) into the `SSH_PRIVATE_KEY` GitHub secret. Delete the
private key from your local machine afterwards if you don't need it locally.

Optional hardening — restrict the key to rsync-only in `~/.ssh/authorized_keys`:

```
command="rsync --server -vlogDtprze.iLsfxC . public_html/c-wald.eu/",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-ed25519 AAAA…
```

## Before going live

Replace every `[PLACEHOLDER: …]` marker in:

- `public_html/c-wald.eu/impressum.html` — company name, address, registry, VAT ID, § 55 RStV responsible party, etc.
- `public_html/c-wald.eu/datenschutz.html` — controller identity, last-updated date.

Replace `public_html/c-wald.eu/assets/logo.svg` with the real logo (or drop a
`logo.png` alongside it and update the `<img src>` references in `index.php`,
`impressum.html`, `datenschutz.html`).

## Contact form

`send.php` uses PHP's `mail()` and sends to `hallo@c-wald.de` with `Reply-To`
set to the submitter's address. Ensure the Hetzner domain's SPF record permits
Hetzner's outbound MTAs, otherwise messages may be filtered.
