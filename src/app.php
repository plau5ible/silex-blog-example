<?php

use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;

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
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->get('/', function() use ($app) {
    $sql = "SELECT * FROM posts";
    $posts = $app['db']->fetchAll($sql);

    return $app['twig']->render('index.html.twig', [
        'posts' => $posts,
    ]);
});

$app->get('/post/{id}', function($id) use ($app) {
    $sql = "
        SELECT * 
        FROM posts p
        WHERE p.id = :id
    ";
    $post = $app['db']->fetchAssoc($sql, [
        ':id' => $id,
    ]);

    $sql = "
        SELECT * 
        FROM comments c
        WHERE c.post_id = :id
    ";
    $comments = $app['db']->fetchAll($sql, [
        ':id' => $id,
    ]);

    return $app['twig']->render('post.html.twig', [
        'post' => $post,
        'comments' => $comments,
    ]);
});


$app->match('/admin/add', function() use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('title')
        ->add('content', 'textarea')
        ->getForm();
    

    $form->handleRequest($app['request']);

    if ($form->isValid()) {
        $data = $form->getData();
        
        // do something with the data
        $sql = "INSERT ..."

        // redirect somewhere
        return $app->redirect('/');
    }
    return $app['twig']->render(
        'add.html.twig', [
            'form' => $form->createView()
        ]);
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