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
$container["post"] = new Post($db);
	
$app->get('/hello[/{name}]', function ($request, $response, $args) {
    //Squared brackets makes an attribute to be optional
    var_dump($args);
    //Attribute:  /something/attr1/attr2 ...
    echo "Hello ".$request->getAttribute('name');
    // hello/{name}?page=5 page -> param
    echo $request->getParam('page');
    print_r($this->db);    
});

$app->post('/create', function($request, $response){
    $this->post->title = $request->getParam("title");
    $this->post->body = $request->getParam("body");
    $this->post->author = $request->getParam("author");
    $this->post->category_id = $request->getParam("category_id");
    if($this->post->create()){
        echo json_encode(
            array(
                "message" => "Post created",
            )
        );
    }   else{
        echo json_encode(
            array(
                "message" => "Post not created",
            )
        );
    }
});

$app->run();