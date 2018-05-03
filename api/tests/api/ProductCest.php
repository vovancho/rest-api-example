<?php

namespace api\tests;

use api\tests\fixtures\ProductFixture;
use api\tests\ApiTester;

class ProductCest
{
    private $token = '';

    public function _fixtures(): array
    {
        return [
            'product' => [
                'class' => ProductFixture::class,
                'dataFile' => codecept_data_dir() . 'product.php'
            ]
        ];
    }

    public function _before(ApiTester $I)
    {
        $I->sendPOST('/oauth2/token', [
            'grant_type' => 'password',
            'username' => 'admin',
            'password' => '123456',
            'client_id' => 'testclient',
            'client_secret' => 'testpass',
        ]);

        $this->token = $I->grabDataFromResponseByJsonPath('$.access_token')[0];
    }

    public function getProductsTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendGET('/products');

        $I->seeResponseContainsJson([
            [
                'id' => 1,
                'name' => 'Продукт 1',
                'price' => '6500.0000',
            ],
            [
                'id' => 2,
                'name' => 'Продукт 2',
                'price' => '7500.0000',
            ],
            [
                'id' => 3,
                'name' => 'Продукт 3',
                'price' => '8500.0000',
            ],
        ]);

        $I->seeResponseCodeIs(200);
    }

    public function getProductTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendGET('/products/1');

        $I->seeResponseContainsJson([
            'id' => 1,
            'name' => 'Продукт 1',
            'price' => '6500.0000',
        ]);

        $I->seeResponseCodeIs(200);
    }

    public function addProductTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendPOST('/products', [
            'name' => 'Product 4',
            'price' => 9500,
        ]);

        $I->seeResponseCodeIs(201);
    }

    public function addBadProduct(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendPOST('/products');

        $I->seeResponseContainsJson([
            'name' => ['Необходимо заполнить «Наименование».'],
            'price' => ['Необходимо заполнить «Стоимость».'],
        ]);

        $I->seeResponseCodeIs(400);
    }

    public function editProductTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendPUT('/products/1', [
            'price' => 5500,
        ]);

        $I->seeResponseCodeIs(200);
    }

    public function badEditProductTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendPUT('/products/1', [
            'price' => '1 dollar',
        ]);

        $I->seeResponseContainsJson([
            'price' => ['Значение «Стоимость» должно быть числом.'],
        ]);

        $I->seeResponseCodeIs(400);
    }

    public function removeProductTest(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);

        $I->sendDELETE('/products/3');

        $I->seeResponseCodeIs(204);
    }
}
