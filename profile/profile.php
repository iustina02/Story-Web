<?php
    session_start();
    
    include_once '../auth/auth.model.php';
    include_once 'profile.model.php';

    $user = NULL;

    if(isset($_SESSION['username']) && isset($_SESSION['hashedPassword'])) {
        $user = getLoggedUser($_SESSION['username'], $_SESSION['hashedPassword']);
    } else {
        header('Location: ../auth/auth.controller.php');
    }

    $myReadings = myReading($user -> id);

    $readBooks = readBooks($user -> id);

    $myBooks = myBooks($user -> id);

?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Story Web</title>

        <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
        <link href="profile.css" rel="stylesheet">  
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  

        <style>
        #TitluButton {
            width: 100%;
            text-align: center;
            padding: 1.5rem 2.5rem;
            background-image: linear-gradient(120deg, #fbc2eb 0%, #a6c1ee 100%);
            font-size: 1.7rem;
            color: white;
            border: none;
            cursor: pointer;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: 'Dosis', sans-serif;
        }

        .card:nth-child(2n) #TitluButton {
            background-image: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
        }
        
        .card:nth-child(4n) #TitluButton {
            background-image: linear-gradient(120deg, #ff9a9e 0%, #fecfef 100%);
        }
        
        .card:nth-child(5n) #TitluButton {
            background-image: linear-gradient(120deg, #ffc3a0 0%, #ffafbd 100%);
        }

        .storyImg {
            border-radius: 10%;
            border-style: dotted;
            border-width: 5px;
            border-color: white;
            display: block;
            margin: 0.5rem auto;
            width: 70%;
        }

        .progress {
            padding: 6px;
            background: none;
            border-radius: 6px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.25), 0 1px rgba(255, 255, 255, 0.08);
            }

            .progress-bar {	
            height: 18px;
            background-color: #ee303c;  
            border-radius: 4px; 
            transition: 0.4s linear;  
            transition-property: width, background-color;    
            }

            .progress-striped .progress-bar { 	
            background-color: #12c7a0; 
            background-image: linear-gradient(
                    45deg, #0e9477 25%, 
                    transparent 25%, transparent 50%, 
                    #0e9477 50%, #0e9477 75%,
                    transparent 75%, transparent); 
            color:white;
            }

            #myBooksButton{
                width: 98%;
                padding: 2%;
                margin-left: 1%;
                margin-top: 7%;
                background:  #ffb977;
                border: 2px solid #ff9838;
                color: white;
                text-align: center;
            }

            #myBooksButton:hover{
            background: #ff9838;
            }

            .myBooks{
            display: none;
            }

            .myBooks.is--open{
            display: flex;
            flex-wrap: wrap;
            }

            @media screen and (min-width: 768px) {
                #myBooksButton{
                width: 98%;
                padding: 2%;
                margin-left: 1%;
                margin-top: 1%;
                }
            }

        </style>
    
    </head>

    <body>

        <div id="root">
            
            <aside class="menuRightA">
                <h2>Buna,  <?php echo $user -> prenume; ?> </h2>
                <ul>
                    <li><p style="padding: 8px; font-size:20px;"><?php echo $user -> nume; ?></p></li>
                    <li><p style="padding: 8px; font-size:20px;"><?php echo $user -> prenume; ?></p></li>
                    <li><p style="padding: 8px; font-size:20px;"><?php echo $user -> email; ?></p></li>
                    <li>
                    <form action="../auth/auth.controller.php" method="POST">
                        <button type="submit" value="LOG OUT" name="submit" class ="MenusButtons">Deconectare</button>
                    </form>
                    </li>
                </ul>
            </aside>

            <form action="../writestory/writeForum.php" method="POST">
                <button type="submit" id="menuLeftButton">Scrie o poveste</button>
            </form>
            <a href="../home/startPage.php" id="startPageA" style="cursor:pointer;">CEVA AICI</a>
            <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo">

            <div class="container">

                <button id="myReadingsButton">Povestile mele incepute</button>
                <div class="myReadings">
                    <?php
                    foreach($myReadings as $result){
                        $storyInfo = getStoryInfo($result['id_story_prez']);
                        $pageSt = $result['started']-1;
                        foreach($storyInfo as $storyInf){
                            $small = substr($storyInf['description'], 0, 50);
                            echo '
                                <div class="card">
                                <form action="../poveste/poveste.php?story='.$storyInf['id'].'?page='.$pageSt.'" method="POST">
                                    <button type="submit" id="TitluButton" value="'.$storyInf['id'].'">'.$storyInf['title'].' by '.$storyInf['autor'].'</button>
                                </form>
                                <div class="progress progress-striped">
                                    <div class="progress-bar" style="width:'.$result['progress'].'%">'.$result['progress'].'%
                                    </div>                       
                                </div> 
                                <p style="padding-bottom:0;">
                                    '.$small.'..
                                </p>';
                            if($storyInf['img'] !== NULL) {
                                echo '<img src="'.$storyInf['img'].'" alt="StoryImg" class="storyImg">
                                </div>';
                            } else {
                                echo '</div>';
                            }
                        }
                    }
                    ?>

                </div>
                
                <button id="readBooksButton">Povesti terminate</button>
                <div class="readBooks">

                    <?php
                        foreach($readBooks as $result){
                            $storyInfo = getStoryInfo($result['id_story_prez']);
                            $pageSt = $result['started']-1;
                            foreach($storyInfo as $storyInf){
                                $small = substr($storyInf['description'], 0, 50);
                                echo '
                                    <div class="card">
                                    <form action="../poveste/poveste.php?story='.$storyInf['id'].'?page='.$pageSt.'" method="POST">
                                        <button type="submit" id="TitluButton" value="'.$storyInf['id'].'">'.$storyInf['title'].' by '.$storyInf['autor'].'</button>
                                    </form>
                                    <div class="progress progress-striped2">
                                        <div class="progress-bar" style="width:'.$result['progress'].'%;    
                                        background-color: #5239e2; 
                                        background-image: linear-gradient(
                                                45deg, #2f20bb 25%, 
                                                transparent 25%, transparent 50%, 
                                                #2f20bb 50%, #2f20bb 75%,
                                                transparent 75%, transparent); 
                                        color:white;">'.$result['progress'].'%
                                        </div>                       
                                    </div> 
                                    <p style="padding-bottom:0;">
                                        '.$small.'..
                                    </p>';
                                if($storyInf['img'] !== NULL) {
                                    echo '<img src="'.$storyInf['img'].'" alt="StoryImg" class="storyImg">
                                    </div>';
                                } else {
                                    echo '</div>';
                                }
                            }
                        }
                    ?>
                    
                </div>

                <button id="myBooksButton">Povestile scrise de mine</button>
                <div class="myBooks">

                    <?php
                        foreach($myBooks as $result){
                            $storyInfo = getStoryInfo($result['id_story_prez']);
                            $pageSt = $result['started']-1;
                            foreach($storyInfo as $storyInf){
                                $small = substr($storyInf['description'], 0, 50);
                                echo '
                                    <div class="card">
                                    <form action="../poveste/poveste.php?story='.$storyInf['id'].'?page='.$pageSt.'" method="POST">
                                        <button type="submit" id="TitluButton" value="'.$storyInf['id'].'">'.$storyInf['title'].' by '.$storyInf['autor'].'</button>
                                    </form> 
                                    <p style="padding-bottom:0;">
                                        '.$small.'..
                                    </p>';
                                if($storyInf['img'] !== NULL) {
                                    echo '<img src="'.$storyInf['img'].'" alt="StoryImg" class="storyImg">
                                    </div>';
                                } else {
                                    echo '</div>';
                                }
                            }
                        }
                    ?>
                    
                </div>

            </div>

        </div>
    <script>
        let myReadingsButton = document.getElementById('myReadingsButton');
        let myReadings = document.querySelector('.myReadings');

        let readBooksButton = document.getElementById('readBooksButton');
        let readBooks = document.querySelector('.readBooks');

        let myBooksButton = document.getElementById('myBooksButton');
        let myBooks = document.querySelector('.myBooks');

        myReadingsButton.addEventListener('click', () => {
            myReadings.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
                myReadings.classList.remove('is--open');
            }
        })

        readBooksButton.addEventListener('click', () => {
            readBooks.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
                readBooks.classList.remove('is--open');
            }
        });

        myBooksButton.addEventListener('click', () => {
            myBooks.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
                myBooks.classList.remove('is--open');
            }
        });

    </script>
    </body>
</html>