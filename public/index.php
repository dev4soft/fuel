<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

$app->add(
    new \Slim\Middleware\Session([
        'autorefresh' => true,
        'lifetime' => '2 minutes',
    ])
);

$container = $app->getContainer();

$container['configdb'] = require '../config/ConfigDb.php';

$container['view'] = function () {
    return new \Slim\Views\PhpRenderer('../templates');
};

$container['db'] = function ($container) {
    return new \Novokhatsky\DbConnect($container['configdb']);
};

$container['session'] = function () {
    return new \SlimSession\Helper();
};

$container['cookie'] = function() {
    return new \Fuel\Cookie;
};

$container['sales'] = function ($container) {
    return new \Fuel\Models\Sales($container['db']);
};

$container['userin'] = function ($container) {
    return new \Fuel\UserIn(
        $container['session'],
        $container['cookie'],
        $container['user']
    );
};

$container['user'] = function ($container) {
    return new \Fuel\User($container['db']);
};

$container['auth'] = function ($container) {
    return new \Fuel\Auth($container['userin']);
};

$app->group('', function () {
    $this->get('/', '\Fuel\Controllers\Form:OutMenu');
    $this->get('/add', '\Fuel\Controllers\Form:OutForm');
    $this->post('/add', '\Fuel\Controllers\Form:SavePurchase');
    $this->get('/history', '\Fuel\Controllers\History:OutSales');
    $this->get('/logout', '\Fuel\Controllers\LoginForm:LogOut');
})->add($container['auth']);

$app->get('/login', '\Fuel\Controllers\LoginForm:LoginForm');
$app->post('/login', '\Fuel\Controllers\LoginForm:LoginCheck');

$app->run();

