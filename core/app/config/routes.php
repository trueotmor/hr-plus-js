<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');


$router->get('/api/user', ['\App\Controllers\User', 'get']);

$router->post('/api/auth/login', ['\App\Controllers\Auth', 'login']);
$router->get('/api/auth/logout', ['\App\Controllers\Auth', 'logout']);
$router->post('/api/auth/register', ['\App\Controllers\Auth', 'register']);
$router->post('/api/auth/forgot', ['\App\Controllers\Auth', 'forgot']);
$router->get('/api/auth/confirm-register', ['\App\Controllers\Auth', 'confirm_register']);
$router->get('/api/auth/reset-password', ['\App\Controllers\Auth', 'reset_password']);

$router->get('/api/company/{id}', ['\App\Controllers\Company', 'get']);
$router->post('/api/company/new', ['\App\Controllers\Company', 'new']);
$router->post('/api/company/list', ['\App\Controllers\Company', 'list']);
$router->post('/api/company/update', ['\App\Controllers\Company', 'update']);
$router->post('/api/company/delete', ['\App\Controllers\Company', 'delete']);

$router->get('/', function() use ($modx) {
    return viewx("base.tpl");
});