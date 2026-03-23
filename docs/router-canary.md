# Canary & Weighted Routing

- Настройте кандидата и процент: `make canary-on PROVIDER=adyen REGION=us PCT=5`.
- В логах `var/router/decisions.ndjson` поле `reason=canary|weighted`.
- Продвижение кандидата: увеличивайте `PCT` постепенно (1→5→25→50→100), глядя на success/error и health_score.
