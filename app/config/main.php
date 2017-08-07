<?php

return [
    'root_dir' => $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR,
    'controllers_namespace' => "MyApp\\controllers\\",
    'is_logging' => false,
    'logs_path' => $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR,
    'domain' => "http://geek.loc",
    'port' => 8080,
    'guest_basket_max' => 4,
    'empty_basket_message' => "Your product basket is empty",
    'img_large_placeholder' => "/img/product/large/placeholder.png",
    'img_small_placeholder' => "/img/product/small/placeholder.png",
    'components' => [
        'mainController' =>
            [
                'class' => \MyApp\controllers\FrontController::class,
            ],
        'renderer' =>
            [
                'class' => \MyApp\services\TwigRenderer::class,
            ],
        'guest' =>
            [
                'class' => \MyApp\models\user\Guest::class,
            ],
        'customer' =>
            [
                'class' => \MyApp\models\user\Customer::class,
            ],
        'customerRep' =>
            [
                'class' => \MyApp\models\repository\CustomerRep::class,
                'table' => "customer",
            ],
        'sessionsRep' =>
            [
                'class' => \MyApp\models\repository\SessionsRep::class,
                'table' => "sessions",
            ],
        'auth' =>
            [
                'class' => \MyApp\services\Auth::class
            ],
        'order' =>
            [
                'class' => \MyApp\models\Order::class,
            ],
        'orderRep' =>
            [
                'class' => \MyApp\models\repository\OrderRep::class,
                'table' => "order",
            ],
        'request' =>
            [
                'class' => \MyApp\services\RequestManager::class
            ],
        'product' =>
            [
                'class' => \MyApp\models\Product::class
            ],
        'productRep' =>
            [
                'class' => \MyApp\models\repository\ProductRep::class,
                'table' => "product",
            ],
        'category' =>
            [
                'class' => \MyApp\models\menu\Category::class,
            ],
        'categoryRep' =>
            [
                'class' => \MyApp\models\repository\CategoryRep::class,
                'table' => "category",
            ],
        'db' =>
            [
                'class' => \MyApp\services\Db::class,
                'driver' => 'mysql',
                'host' => 'localhost',
                'dbname' => 'geek_shop',
                'user' => 'root',
                'password' => ''
            ],
    'fileLogger' =>
            [
                'class' => \MyApp\services\FileLogger::class
            ],
    ]
];
