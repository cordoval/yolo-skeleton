test:
	bin/phpspec run
	bin/behat
	bin/behat -p end-to-end

web: .PHONY
	php -S localhost:8080 -t web web/dev.php

web-prod:
	php -S localhost:8080 -t web web/prod.php

.PHONY: