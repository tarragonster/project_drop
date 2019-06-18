php ../vendor/zircote/swagger-php/bin/swagger --bootstrap environment/live-constants.php --output ../../swagger/live.json ./environment/live-scheme.php ./base.php ../../application/controllers/api
php ../vendor/zircote/swagger-php/bin/swagger --bootstrap environment/stage-constants.php --output ../../swagger/stage.json ./environment/stage-scheme.php ./base.php ../../application/controllers/api
php ../vendor/zircote/swagger-php/bin/swagger --bootstrap environment/local-constants.php --output ../../swagger/local.json ./environment/local-scheme.php ./base.php ../../application/controllers/api
