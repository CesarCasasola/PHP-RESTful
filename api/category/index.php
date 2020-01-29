<?php

require_once("../../slim/vendor/autoload.php");
include_once '../../config/Database.php';
include_once '../../models/Post.php';

//Instantiate DB
$database = new Database();
$db = $database->connect();

$app = new \Slim\App([
    'settings'  =>  [
        'displayErrorDetails' => true
    ]
]);

//DI container
$container = $app->getContainer();

$container["db"] = $db;
	
$app->get('/hello/{name}', function ($request, $response) {
    echo "Hello ".$request->getAttribute('name');
    print_r($this->db);
    
});

$app->run();