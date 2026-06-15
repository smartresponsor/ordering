# Ordering release

## Prerequisites
- git, composer, PHP 8.2/8.3
- GitHub CLI (`gh auth login`)
- required tokens in the environment when publishing

## Release from CLI
Linux/macOS:
```bash
chmod +x release_beta.sh
./release_beta.sh 0.3.0-rc
```

Windows PowerShell:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope Process
./release_beta.ps1 -version 0.3.0-rc
```

## GitHub Actions
- Main CI workflows live under `.github/workflows/`
- Archived root CI draft lives under `docs/root/archive/ci/ci-enhancements.root.yml`
- Release workflow: `.github/workflows/release.yml`

## Containers / Compose / Helmfile
```bash
# local or prod compose from deploy/
docker compose -f deploy/docker-compose.local.yml up -d --build

# helmfile from deploy/order/
helmfile -f deploy/order/helmfile.yaml apply -e staging
helmfile -f deploy/order/helmfile.yaml apply -e production
```
