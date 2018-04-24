<?php
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=' . (getenv('DB_HOST_TEST') ?: 'localhost') . ';dbname=' . (getenv('MYSQL_DATABASE_TEST') ?: 'restapi_test'),
            ]
        ],
    ]
);
