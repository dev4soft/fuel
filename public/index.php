<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

$container = $app->getContainer();

$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer('../templates');
};

$container['db'] = function ($container) {
    return new \Novokhatsky\Db\DbConnect(new \Fuel\ConfigDb);
};

$app->add(
    new \Slim\Middleware\Session([
        'autorefresh' => true,
        'lifetime' => '2 minutes',
    ])
);

$container['session'] = function() {
    return new \SlimSession\Helper();
};

$container['cookie'] = function() {
    return new \Fuel\Cookie;
};

$app->group('', function () {
    $this->get('/', '\Fuel\Controllers\Form:OutMenu');
    $this->get('/add', '\Fuel\Controllers\Form:OutForm');
    $this->post('/add', '\Fuel\Controllers\Form:SavePurchase');
    $this->get('/history', '\Fuel\Controllers\History:OutSales');
    $this->get('/logout', '\Fuel\Controllers\Login:LogOut');
})->add(new \Fuel\Auth($container));

$app->get('/login', '\Fuel\Controllers\Login:LoginForm');
$app->post('/login', '\Fuel\Controllers\Login:LoginCheck');

$app->run();

