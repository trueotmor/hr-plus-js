<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
// }

$router->get('/api/auth/is-auth', ['\App\Controllers\Auth', 'is_auth']);
$router->post('/api/auth/login', ['\App\Controllers\Auth', 'login']);
$router->get('/api/auth/logout', ['\App\Controllers\Auth', 'logout']);
$router->post('/api/auth/register', ['\App\Controllers\Auth', 'register']);
$router->post('/api/auth/forgot', ['\App\Controllers\Auth', 'forgot']);
$router->get('/api/auth/confirm-register', ['\App\Controllers\Auth', 'confirm_register']);
$router->get('/api/auth/reset-password', ['\App\Controllers\Auth', 'reset_password']);

// $router->get('/crm', function() use ($modx) {
//     return viewx("crm.tpl");
// });


$router->get('/login', function() use ($modx) {
    return viewx("auth.tpl");
});

$router->get('/register', function() use ($modx) {
    return viewx("auth.tpl");
});

$router->get('/', function() use ($modx) {
    return viewx("auth.tpl");
});