<?php
    $CONFIG = [
        'servername' => "localhost",
        'username' => "root",
        'password' => '',
        'db' => 'storyweb'
    ];

    /** Regular statement */
    $conn = new mysqli($CONFIG["servername"], $CONFIG["username"], $CONFIG["password"], $CONFIG["db"]);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    function getStoryInfo ($idStory) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id = ?');
        $getStmt -> bind_param('d',$idStory);

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        return $results;
    }

    function myReading ($idUser) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare("SELECT * FROM story_state where id_user ='$idUser' and finished = 0");

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        return $results;
    }

    function readBooks ($idUser) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare("SELECT * FROM story_state where id_user ='$idUser' and finished = 1");

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        return $results;
    }

    function myBooks ($idUser) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare("SELECT * FROM story_state where id_user ='$idUser' and my_story = 1");

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        return $results;
    }
?>