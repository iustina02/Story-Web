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

    function storyPrezInsert ($title, $autor, $description, $image, $age, $idUser) {
        GLOBAL $conn;

        $index0 = 4;

        $insertStmt = $conn -> prepare('INSERT into story_prez (title, autor, description, img, age, pages) VALUES(?,?,?,?,?,?);');    
        $insertStmt -> bind_param('ssssdd',$title, $autor, $description, $image, $age, $index0);

        $success = $insertStmt -> execute();

        $insertStmt -> close();

        $idStoryPrezStmt = $conn -> prepare('SELECT * FROM story_prez WHERE title = ? and autor = ? and description = ? and img = ? and age = ?');
        $idStoryPrezStmt -> bind_param('ssssd',$title, $autor, $description, $image, $age);

        $idStoryPrezStmt -> execute();
        $results = $idStoryPrezStmt -> get_result();
        $idStoryPrezStmt -> close();

        
        if($results -> num_rows  > 0) {
            $firstRow = $results -> fetch_assoc();
                $idStory = $firstRow['id'];
                if($insertStmt2 = $conn -> prepare('INSERT into story_state (id_user, id_story_prez) VALUES(?,?);')) {    
                    $insertStmt2 -> bind_param('dd',$idUser,$idStory);
            
                    $success = $insertStmt2 -> execute();
                    $insertStmt2 -> close();

                    $index0 = 0;
                    $index1 = 1;

                    if($updateStmt = $conn -> prepare("UPDATE story_state SET started = ?, finished = ?, progress = ?, my_story = ? where id_user = ? and id_story_prez = ?")) {
                        $updateStmt -> bind_param('dddddd', $index0,$index0, $index0, $index1, $idUser, $idStory);
                        $updateStmt -> execute();
                        return $firstRow['id'];
                    }  else {
                        $error = $conn->errno . ' ' . $conn->error;
                        echo $error; // 1054 Unknown column 'foo' in 'field list'
                    }
                    
                }
        } 

    }

    function storyPageInsert($nrPage, $text_cont, $img_cont,  $idStory) {
        GLOBAL $conn;


        $PageStmt = $conn -> prepare('SELECT * FROM story WHERE id_story_prez = ? and page = ?');
        $PageStmt -> bind_param('dd',$idStory, $nrPage);

        $PageStmt -> execute();
        $results = $PageStmt -> get_result();
        $PageStmt -> close();

        
        if($results -> num_rows  > 0) {
            if($updateStmt = $conn -> prepare('UPDATE story SET content_text = ?, content_img= ? where id_story_prez = ? and page = ?')){    
                $updateStmt -> bind_param('ssdd',$text_cont, $img_cont, $idStory, $nrPage);

                $success = $updateStmt -> execute();

                $updateStmt -> close();

                $idStoryPrezStmt = $conn -> prepare('SELECT pages FROM story_prez WHERE id = ?');
                $idStoryPrezStmt -> bind_param('d',$idStory);
        
                $idStoryPrezStmt -> execute();
                $results = $idStoryPrezStmt -> get_result();
                $idStoryPrezStmt -> close();
                
                if($results -> num_rows  > 0) {
                    $firstRow = $results -> fetch_assoc();
                        $storyPages = $firstRow['pages'];
                        if($nrPage > $storyPages){
                            if($updateStmt = $conn -> prepare("UPDATE story_prez SET pages = ? where id = ? ")) {
                                $updateStmt -> bind_param('dd', $nrPage, $idStory);
                                $updateStmt -> execute();
                                return true;
                            }  else {
                                $error = $conn->errno . ' ' . $conn->error;
                                echo $error; // 1054 Unknown column 'foo' in 'field list'
                            }
                        }
                }
                return true;
            } else {
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list' 
            }

        } else {
            if($insertStmt = $conn -> prepare('INSERT into story (page, content_text, content_img, id_story_prez) VALUES(?,?,?,?);')){    
                $insertStmt -> bind_param('dssd',$nrPage, $text_cont, $img_cont, $idStory);

                $success = $insertStmt -> execute();

                $insertStmt -> close();

                if($updateStmt = $conn -> prepare("UPDATE story_prez SET pages = ? where id = ? ")) {
                    $updateStmt -> bind_param('dd', $nrPage, $idStory);
                    $updateStmt -> execute();
                    return true;
                }  else {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'
                }
            } else {
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list' 
            }
        }
    }

?>