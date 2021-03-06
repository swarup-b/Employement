<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };
    // Database connection
    $container['db'] = function ($c) {
        $settings = $c->get('settings')['db'];
        $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'],
            $settings['user'], $settings['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    };

     // Database connection for FileMaker Database
    $container['fmdb'] = function ($c) {
        $settings = $c->get('settings')['fmdb'];

        include __DIR__ .'/library/FileMakerCWP/FileMaker.php';

        $fm = new FileMaker();
        $fm->setProperty('database', $settings['dbname']);
        $fm->setProperty('hostspec', $settings['host']);
        $fm->setProperty('username', $settings['user']);
        $fm->setProperty('password', $settings['pass']);
        return $fm;
    };
   
    $container['ContactController']=function ($c) {
       return new Src\Controller\ContactController($c);
    };
    $container['FmModel']=function ($c) {
       return new Src\Model\FmModel($c);
    };

    $container['ActivityController']=function ($c) {
       return new Src\Controller\ActivityController($c);
    };

    $container['FmUserController']=function ($c) {
       return new Src\Controller\FmUserController($c);
    };

    $container['UserService']=function ($c) {
       return new Src\Services\UserService($c);
    };
    $container['Validation']=function ($c) {
       return new Src\Services\Validation($c);
    };

};
