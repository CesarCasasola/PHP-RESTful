<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

//Instantiate DB
$database = new Database();
$db = $database->connect();
//Instantiate Post
$post = new Post($db);
//Get posts
$result = $post->read();
//Get row count
$row_count = $result->rowCount();

if($row_count > 0){
    //Post array
    $posts_arr = array();
    $posts_arr['data'] = array();
    while($row = $result->fetch()){
        extract($row);
        $post_item = array(
            "id" => $id,
            "title" => $title,
            "body" => $body,
            "author" => $author,
            "category_id" => $category_id,
            "category_name" => $category_name,     
            "created_at" => $created_at
        );
        array_push($posts_arr['data'], $post_item);
    }
    //Turn to JSON
    echo json_encode($posts_arr);
}else{
    //No posts
    echo json_encode(
        array("messgae" => "No posts found")
    );
}
