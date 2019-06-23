<?php 
    $CONFIG = [
        'servername' => "localhost",
        'username' => "root",
        'password' => '',
        'db' => 'storyweb'
    ];

    $conn = new mysqli($CONFIG["servername"], $CONFIG["username"], $CONFIG["password"], $CONFIG["db"]);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function getStories($age) {
        GLOBAL $conn;

        $ages = explode('-', $age);

        $getStmt = $conn -> prepare('SELECT * FROM story_prez;');
        if($age !== NULL && $age !== '') {
            $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE age between '.$ages[0].' and '.$ages[1].'');
        }

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt -> close();
        
        return $results;
    }

    function getStoriesByTags($tagInput) {
        GLOBAL $conn;

        $tags = json_decode(file_get_contents('./tags.json'),true);

        $storyTag = NULL;
        $storyId = NULL;
        
        foreach($tags as $tag => $value1) {
            foreach($tags[$tag]["tags"] as $tg => $value1){
                if($tags[$tag]["tags"][$tg] == $tagInput){
                    $storyTag = $tags[$tag]["tags"][$tg];
                    $storyId = $storyId.'-'.$tags[$tag]['id_story_prez'];
                }
            }
        }

        $getStmt = $conn -> prepare('SELECT * FROM story_prez;');

        if($storyTag !== NULL && $storyId !== NULL){
            $parti = explode("-",$storyId);
            if(count($parti) == 2){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].')');
            }
            if(count($parti) == 3){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].')');
            }
            if(count($parti) == 4){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].')');
            }
            if(count($parti) == 5){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].')');
            }
            if(count($parti) == 6){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].','.$parti[5].')');
            }
            if(count($parti) == 7 ){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].','.$parti[5].','.$parti[6].')');    
            }
            if(count($parti) == 8){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].','.$parti[5].','.$parti[6].','.$parti[7].')');
            }
            if(count($parti) == 9){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].','.$parti[5].','.$parti[6].','.$parti[7].','.$parti[8].')');
            }
            if(count($parti) == 10){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].','.$parti[5].','.$parti[6].','.$parti[7].','.$parti[8].','.$parti[9].')');
            }
            if(count($parti) == 11){
                $getStmt = $conn -> prepare('SELECT * FROM story_prez WHERE id in ('.$parti[1].','.$parti[2].','.$parti[3].','.$parti[4].','.$parti[5].','.$parti[6].','.$parti[7].','.$parti[8].','.$parti[9].','.$parti[10].')');
            }
        }

        $getStmt -> execute();
        $results = $getStmt -> get_result();
        $getStmt -> close();

        return $results;
    }
?>