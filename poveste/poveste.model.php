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

    function getStory($idStory, $index){
        GLOBAL $conn;

        $getStmt = $conn -> prepare('SELECT * FROM story WHERE id_story_prez = ? AND page = ?');
        $getStmt -> bind_param('dd', $idStory, $index);

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        return $results;
    }

    function storyStateInsert ($idStory, $pages, $idUser) {
        GLOBAL $conn;

        $totalP = NULL;
        $totalPages = getStoryInfo($idStory);
        $finished = 0;

        foreach($totalPages as $totalPage) {
            $totalP = $totalPage['pages'];
        }

        $progress = $pages * 100 / $totalP ;

        if($progress == 100){
            $finished = 1;
        }

        
        $insertStmt = $conn -> prepare("INSERT INTO story_state (id_user, id_story_prez, started, finished, my_story, progress) VALUES ('$idUser' , '$idStory', '$pages', 0, 0,'(int)$progress')");
        $insertStmt -> execute();
        
    }

    function storyStateUpdate ($idStory, $pages, $idUser) {
        GLOBAL $conn;

        $totalP = NULL;
        $totalPages = getStoryInfo($idStory);
        $finished = 0;

        foreach($totalPages as $totalPage) {
            $totalP = $totalPage['pages'];
        }

        $progress = $pages * 100 / $totalP ;

        if($progress == 100){
            $finished = 1;
        }

        $updateStmt = $conn -> prepare("UPDATE story_state SET started = '$pages', finished = '$finished', progress = '$progress' where id_user = '$idUser' and id_story_prez = '$idStory'");
        $updateStmt -> execute();
    }

    function storyStateFirst ($idStory, $pages, $idUser) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare("SELECT * FROM story_state WHERE id_user ='$idUser' and id_story_prez = '$idStory'");

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        foreach ($results as $result){
            if($result['id'] !== NULL) {
                return true;
            } 
        }
        return false;
    }

    function storyStateFinished ($idStory, $pages, $idUser) {
        GLOBAL $conn;

        $getStmt = $conn -> prepare("SELECT * FROM story_state WHERE id_user ='$idUser' and id_story_prez = '$idStory'");

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt ->close();

        foreach ($results as $result){
            if($result['finished'] !== 0) {
                return false;
            } 
        }
        return true;
    }
?>