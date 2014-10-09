<?php

use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

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
$app->register(new ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->register(new Silex\Provider\SecurityServiceProvider(), array(

));
$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/admin',
        'http' => true,
        'users' => array(
            // raw password is foo
            'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
        ),
    ),
);

$app->get('/', function() use ($app) {
    $sql = "SELECT * FROM posts";
    $posts = $app['db']->fetchAll($sql);

    return $app['twig']->render('index.html.twig', [
        'posts' => $posts,
    ]);
})
->bind('main');

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
})
->bind('post');


$app->match('/admin/add', function() use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('title')
        ->add('content', 'textarea')
        ->getForm();
    

    $form->handleRequest($app['request']);

    if ($form->isValid()) {
        $data = $form->getData();
        
        // do something with the data
        $sql = "INSERT ...";

        // redirect somewhere
        return $app->redirect('/');
    }
    return $app['twig']->render(
        'add.html.twig', [
            'form' => $form->createView()
        ]);
});

$app->match('/admin/edit/{id}', function($id) use ($app) {
    // получить данные о посте, который меняем
    $postData = [
        'title' => 'POst 1',
        'content' => 'asdfasdfasdf',
    ];
    // генерируем форму, подставляя данные о посте
    $form = $app['form.factory']->createBuilder('form', $postData)
        ->add('title')
        ->add('content', 'textarea')
        ->getForm();

    // обработка отправленной формы

    return $app['twig']->render(
        'add.html.twig', [
            'form' => $form->createView()
        ]);
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