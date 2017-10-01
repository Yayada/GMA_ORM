<?php
    // Include the Router class    
    require_once __DIR__.'/../../vendor/bramus/router/src/Bramus/Router/Router.php';    

    // Create a Router
    $router = new \Bramus\Router\Router();

    // Before Router Middleware
    $router->before('GET', '/.*', function () {
        header('X-Powered-By: bramus/router');
    });

    // Static route: / (homepage)
    $router->get('/test', function () {
        require __DIR__.'/../Controllers/userController.php';
        $c = new userController();
        $c->getAllusers();
    });

    // Run    
    $router->run();