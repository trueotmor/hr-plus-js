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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
}

$router->post('/api/auth/login', ['\App\Controllers\Auth', 'login']);
$router->post('/api/auth/register', ['\App\Controllers\Auth', 'register']);

// $router->get('/crm', function() use ($modx) {
//     return viewx("crm.tpl");
// });


$router->get('/login', function() use ($modx) {
    zoomx()->autoloadResource = false;
    return viewx("auth.tpl");
});

$router->get('/register', function() use ($modx) {
    zoomx()->autoloadResource = false;
    return viewx("auth.tpl");
});

$router->get('/', function() use ($modx) {
    zoomx()->autoloadResource = false;
    return viewx("auth.tpl");
});