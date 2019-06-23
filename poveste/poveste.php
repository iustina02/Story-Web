
<?php

    session_start();
        
    include_once '../auth/auth.model.php';
    include_once 'poveste.model.php';

    $user = NULL;

    if(isset($_SESSION['username']) && isset($_SESSION['hashedPassword'])) {
        $user = getLoggedUser($_SESSION['username'], $_SESSION['hashedPassword']);
    } else {
        header('Location: ../auth/auth.controller.php');
    }

    $queries = [];
    parse_str($_SERVER['QUERY_STRING'], $queries);

    $parti = explode("?",$queries['story']);

    $index = explode("=", $parti[1]);

    $idStory = $parti[0];

    $index1 = $index[1];
    $index2 = $index1 +1;

    $storyInfo = getStoryInfo($idStory);
    $pages1 = getStory($idStory, $index1);

    $pages2 = getStory($idStory, $index2);

    if(storyStateFirst($idStory, $index2, $user -> id) == false ) {
        storyStateInsert($idStory, $index2, $user -> id);
    }
    if(storyStateFirst($idStory, $index2, $user -> id) == true ) {
        if(storyStateFinished ($idStory, $index2, $user -> id) == true){
            storyStateUpdate($idStory, $index2, $user -> id);
        }
    }
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="ro-Ro">
        <title>Story Web</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
        <link href="poveste.css" rel="stylesheet">    

        <style>
        #doneButton {
            background: salmon;   
            position:absolute; 
            bottom:1%; 
            right:8%; 
            width: 20%;
            }

        #aButton {
            background: #a6c1ee;   
            position:absolute; 
            bottom:1%; 
            left: 3%;
            width: 20%;
            }

        #bButton {
            background: #a6c1ee;   
            position:absolute; 
            bottom:1%; 
            right: 3%;
            width: 20%;
            }    
        p {
            padding:0;
        }
        </style>
    
    </head>

    <body>

    <div id="root"> 

        <button type="button" id="menuRightButton">Contul meu</button>
        <aside class="menuRightA">
            <h2>Contul meu</h2>
            <ul><li>
                <form action="../profile/profile.php" method="POST">
                    <button class ="MenusButtons">Profil</button>
                </form>
                </li>
                <li>
                <button class ="MenusButtons"> Povesti </button>  
                </li>
                <li>
                <form action="../auth/auth.controller.php" method="POST">
                    <button type="submit" value="LOG OUT" name="submit" class ="MenusButtons">Deconectare</button>
                </form>
                </li>
            </ul>
        </aside>
    
        <?php
        echo '
        <form action="../personaje/personaje.php?story='.$idStory.'?page='.$index1.'" method="POST">
            <button type="submit" id="menuLeftButton">Personaje</button>
        </form>
        ';
        ?>

        <a href="../home/startPage.php" id="startPage">Ceva</a>
        <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo">

        <div class="container">
                <div class="card">
                    <?php
                    foreach($storyInfo as $storyI){
                        foreach($pages1 as $page) {
                            echo '
                            <h1>'.$storyI['title'].' pagina:'.$index1.'</h1>
                            <div class="bookmark">
                                <img src="./bookmark.png" alt="bookmark">
                            </div>';
                            if($page['content_text'] !== NULL) {
                                echo '
                                <p>'.$page['content_text'].'</p>
                                ';
                            }
                            if($page['content_img'] !== NULL or $page['content_img'] !== '') {
                                echo '
                                <img src="'.$page['content_img'].'" class="storyImg">
                                ';
                            }
                        }
                        $editPage = $index1;
                        if($index1 > 2) {
                            $index1 = $index1 - 2;
                            echo '<form action="../poveste/poveste.php?story='.$storyI['id'].'?page='.$index1.'" method="POST">
                                <button class="prevPage"><i class="arrow left"></i></button>
                            </form>';
                        }
                        if($storyI['autor'] == ''.$user -> nume.' '.$user-> prenume.'')
                        {
                            echo '
                            <form action="../writestory/writePages.php?story='.$storyI['id'].'?page='.$editPage.'" method="POST">
                                <button id="doneButton" style="width: 10%; height: 7%;" type="submit" name="submit" value="edit">Edit</button>
                            </form>';
                        }
                    }
                    ?>
                </div>

                <div class="card">
                    <?php
                    foreach($storyInfo as $storyI){
                        foreach($pages2 as $page) {
                            echo '
                            <h1>'.$storyI['autor'].' pagina:'.$index2.'</h1>';
                            if($page['content_text'] !== NULL) {
                                echo '
                                <p>'.$page['content_text'].'</p>
                                ';
                            }
                            if($page['content_img'] !== NULL or $page['content_img'] !== '') {
                                echo '
                                    <img src="'.$page['content_img'].'" class="storyImg">
                                ';
                            }
                        }
                        $editPage = $index2;
                        if($index2 < $storyI['pages']){
                            $index2 = $index2 +1;
                            
                            if($storyI['id'] == 2  && $index2 == 9){
                                echo'<form action="../poveste/poveste.php?story='.$storyI['id'].'?page='.$index2.'" method="POST">
                                        <button  id="aButton"style="width: 10%; height: 7%;" type="submit" name="submit" value="A">A</button>
                                    </form>
                                    <form action="../poveste/poveste.php?story='.$storyI['id'].'?page=15" method="POST">
                                        <button  id="bButton"style="width: 10%; height: 7%;" type="submit" name="submit" value="B">B</button>
                                    </form>';
                            } else {
                                echo '<form action="../poveste/poveste.php?story='.$storyI['id'].'?page='.$index2.'" method="POST">
                                        <button class="nextPage"><i class="arrow right"></i></button>
                                    </form>';
                            }

                        }
                        if($storyI['autor'] == ''.$user -> nume.' '.$user-> prenume.'')
                        {
                            echo '
                            <form action="../writestory/writePages.php?story='.$storyI['id'].'?page='.$editPage.'" method="POST">
                            <button id="doneButton" style="width: 10%; height: 7%;" type="submit" name="submit" value="edit">Edit</button>
                            </form>';
                        }
                    }
                    ?>
                </div>
        </div>
    </div>
    <script>
        let menuRightButton = document.getElementById('menuRightButton');
        let menuRightA = document.querySelector('.menuRightA');
        
        let menuLeftButton = document.getElementById('menuLeftButton');
        let menuLeftA = document.querySelector('.menuLeftA');
        
        menuRightButton.addEventListener('click', () => {
        menuRightA.classList.add('is--open');
        });
        
        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
            menuRightA.classList.remove('is--open');
            }
        })
        
        menuLeftButton.addEventListener('click', () => {
        menuLeftA.classList.add('is--open');
        });
        
        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
            menuLeftA.classList.remove('is--open');
            }
        })
    </script>    
    </body>
</html>