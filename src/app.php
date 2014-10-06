<?php

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() use ($app) {
    return 'Главная';
});

$app->get('/post/{id}', function($id) use ($app) {
    return 'Пост';
});


$app->get('/admin/add', function() use ($app) {
    return '';
});

$app->get('/admin/edit/{id}', function($id) use ($app) {
    return '';
});

$app->get('/admin/delete/{id}/', function($id) use ($app) {
    return '';
});

$app->get('/add-comment', function() use ($app) {
    return '';
});

$app->get('/edit-comment/{id}', function($id) use ($app) {
    return '';
});

$app->get('/delete-comment/{id}', function($id) use ($app) {
    return '';
});


return $app;