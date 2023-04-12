<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->post('/api/auth/login', ['\App\Controllers\Auth', 'login']);
$router->post('/api/auth/register', ['\App\Controllers\Auth', 'register']);


$router->get('/crm', function() use ($modx) {
    return viewx("crm.tpl");
});

$router->get('/', function() use ($modx) {
    return viewx("auth.tpl");
});
