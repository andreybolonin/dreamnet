<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() use($app) {
    return 'Index';
});

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

return $app['twig']->render('hello.twig', array(
        'name' => $name,
    ));

$app->run();
