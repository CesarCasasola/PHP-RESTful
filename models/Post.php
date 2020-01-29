<?php

class Post{

    //DB
    private $conn;
    private $table = "posts";

    //Model
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        //Create query
        $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                '.$this->table.' p
            LEFT JOIN
                categories c 
            ON p.category_id = c.id
            ORDER BY
                p.created_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
        //Create query
        $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                '.$this->table.' p
            LEFT JOIN
                categories c 
            ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    //Create post
    public function create(){
        $query = 'INSERT INTO '.
            $this->table. '(title, body, author, category_id)'.
            ' VALUES(
                :title,
                :body,
                :author,
                :category_id)';

        //Clean the data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->cateogry_id = htmlspecialchars(strip_tags($this->category_id));


        //Prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);
        
        //Execute query
        if($stmt->execute()){
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}