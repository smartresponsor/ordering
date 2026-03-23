.PHONY: deploy-hpa purge-run migrate-zero shed-test provider-smoke capacity-calc
deploy-hpa:
	kubectl apply -f k8s/hpa-api.yaml
	kubectl apply -f k8s/hpa-worker.yaml
purge-run:
	php bin/purge-pii.php
migrate-zero:
	bash bin/migrate-zero.sh
shed-test:
	@echo 'Simulate load and observe 429 with Retry-After (see Gate.php)'
provider-smoke:
	@echo 'Instantiate ProviderRouter with Stripe+Adyen+PayPal stubs and call authorize()'
capacity-calc:
	@echo 'See docs/capacity-model.md and dashboards/grafana/scale-saturation.json'
