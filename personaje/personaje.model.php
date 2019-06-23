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

    function getPersInfo ($idStory) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare('SELECT * FROM story_pers WHERE id_pov_prez = ?');
        $getStmt -> bind_param('d',$idStory);

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        return $results;
    }

    function addCharacter ($idStory, $nume, $descriere, $image) {
        GLOBAL $conn;

        if($insertStmt = $conn -> prepare('INSERT INTO story_pers (id_pov_prez, name, img, description) VALUES (?,?,?,?);')) {
            $insertStmt -> bind_param('dsss', $idStory, $nume, $image, $descriere);

            $insertStmt -> execute();

            $insertStmt -> close();

            echo 'da';

            return true;
        } else {
            return false;
        }
        
    }

?>