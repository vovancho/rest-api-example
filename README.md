## Пример REST приложения на Yii2 [![Build Status](https://travis-ci.org/vovancho/rest-api-example.svg?branch=master)](https://travis-ci.org/vovancho/rest-api-example)

### rest.local

![Example REST](https://github.com/vovancho/rest-api-example/blob/master/project/view.jpg)

### api.rest.local

Запрос | Параметры | Описание
--- | --- | ---
`POST https://api.rest.local/oauth2/token` | `{"grant_type":"password", "username":"admin", "password":"123456", "client_id":"testclient", "client_secret":"testpass"}` | Авторизация по OAuth2. Логин `admin`. Пароль `123456`.
`GET https://api.rest.local/products` | | Вывести список продуктов
`POST https://api.rest.local/products` | `name` - Наименование продукта <BR> `price` - Стоимость продукта | Добавить новый продукт
`PUT https://api.rest.local/products/{productId}` | `productId` - ИД продукта <BR> `name` - Наименование продукта <BR> `price` - Стоимость продукта | Изменить запись продукта с ИД `productId`
`DELETE https://api.rest.local/products/{productId}` | `productId` - ИД продукта | Удалить запись продукта с ИД `productId`
`GET https://api.rest.local/products/{productId}` | `productId` - ИД продукта | Вывести запись продукта с ИД `productId`

### Документация API на [Swagger](https://swagger.io/)

`https://api.rest.local/docs/index.html`

![Example REST](https://github.com/vovancho/rest-api-example/blob/master/project/swagger.jpg)

### Docker

#### variables.env

В файле `variables.env` находятся настройки для `docker-compose.yml`.

#### docker2boot (Docker ToolBox)

Конфигурация виртуальной машины `docker2boot`:
  - docker-machine stop
  - *Если необходимо, добавить папку **"C:\www"***:
    - vboxmanage sharedfolder add default --name "c/www" --hostpath "C:\www" --automount
  - Добавляем порт ssl  
    - VBoxManage modifyvm "default" --natpf1 "nginx_ssl,tcp,,443,,443"
  - *Если необходимо перенаправлять http на https*: 
    - VBoxManage modifyvm "default" --natpf1 "nginx_http,tcp,,80,,80"
  - *Если необходимо, добавить порт для **XDebug***: 
    - VBoxManage modifyvm "default" --natpf1 "xdebug,tcp,,9001,,9001"
  - docker-machine start
  
#### Запуск

```
    docker-compose up -d
    docker-compose exec php-cli php init
    docker-compose exec php-cli php yii migrate   
```

#### Hosts

Добавить в файл `hosts` имена серверов:
  - <IP Docker хоста> api.restapi.local
  - <IP Docker хоста> restapi.local
  
#### docker-compose.yml

У сервиса `php-fpm` есть переменная окружения `XDEBUG_CONFIG`. Если нужен XDebug, необходимо вписать ip адрес `remote_host=<ip адрес удаленного xdebug клиента>`.
Если локальный сервер, заменить `remote_host` на `remote_connect_back=1`

#### api.restapi.local

**Авторизация:**

POST https://api.restapi.local/oauth2/token

Text:
```json
{  
   "grant_type":"password",
   "username":"admin",
   "password":"123456",
   "client_id":"testclient",
   "client_secret":"testpass"
}
```

Response:
```json
{  
   "access_token":"18e4d2f2b4bcf7f37a93d1fc8334cffbf0c8331f",
   "expires_in":86400,
   "token_type":"Bearer",
   "scope":null,
   "refresh_token":"cbf38fdeefb170913768521b3b4c4317ad77dea8"
}
```

Headers:

Name          | Value
------------- | -------------
Accept        | application/json
Cache-Control | no-cache
Content-Type  | application/json
Authorization | Bearer bff80282a641796870cd5f7de10a8224e7f70e21 `(access_token)`

### Тесты

Запуск `api` тестов:

```bash
    docker-compose up -d
    docker-compose exec php-cli php init --env=Development --overwrite=All
    docker-compose exec php-cli php yii_test migrate
    docker-compose exec php-cli vendor/bin/codecept run api -c api
```