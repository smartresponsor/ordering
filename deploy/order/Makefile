helm-lint:
	helm lint charts/ordercomponent

helm-package:
	mkdir -p deploy/order/dist
	helm package charts/ordercomponent -d deploy/order/dist
