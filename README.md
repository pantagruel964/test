```
echo '0.0.0.0 test.localhost.test' | sudo tee -a /etc/hosts
```
```
git clone https://github.com/pantagruel964/test.git test25032021
cd test25032021

docker-compose up -d --build
docker exec -it dev1-php bash

composer install
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

[https://test.localhost.test:444/api/doc](https://test.localhost.test:444/api/doc)
