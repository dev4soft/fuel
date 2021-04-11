<?php
session_start();

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

$app->get('/', '\Fuel\Controllers\Form:OutMenu');

$app->get('/add', '\Fuel\Controllers\Form:OutForm');

$app->post('/add', '\Fuel\Controllers\Form:SavePurchase');

$app->get('/history', '\Fuel\Controllers\History:OutSales');

$app->run();

