<?php

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'epic_blog',
            'user'      => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
        ),
    )
);

$app->get('/', function() use ($app) {
    $sql = "SELECT * FROM posts";
    $posts = $app['db']->fetchAll($sql);

    return $app['twig']->render('index.html.twig', [
        'posts' => $posts,
    ]);
});

$app->get('/post/{id}', function($id) use ($app) {
    $sql = "SELECT * FROM posts WHERE id = :id";
    $post = $app['db']->fetchAssoc($sql, [
        ':id' => $id,
    ]);

    return $app['twig']->render('post.html.twig', [
        'post' => $post,
    ]);
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