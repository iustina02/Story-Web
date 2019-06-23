<?php
    session_start();
    
    include_once '../auth/auth.model.php';

    $user = NULL;

    if(isset($_SESSION['username']) && isset($_SESSION['hashedPassword'])) {
        $user = getLoggedUser($_SESSION['username'], $_SESSION['hashedPassword']);


    } else {
        header('Location: ../auth/auth.controller.php');
    }

    include_once 'story.model.php';
    $queries = [];
    parse_str($_SERVER['QUERY_STRING'], $queries);
    
    if(!isset($queries['tags'])) {
        $stories = getStories(isset($queries['age'])? $queries['age']: NULL);
    } else {
        $stories = getStoriesByTags(isset($queries['tags'])? $queries['tags']: NULL);
    }
?>
<!DOCTYPE html>
<html lang="ro">
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="ro-Ro">
        <meta name="language" content="romanian">
        <title>Story Web</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
        <link href="startPage.css" rel="stylesheet">    

        <style>
        @media screen and (min-width: 768px) {
            #startPage {
            width:40%;
            height: 32%;
            top:10%;
            left: 30%;
            }
        }
        @media screen and (min-width: 1024px) {
            #startPage {
            width:30%;
            height: 210px;
            top:3%;
            left: 35%;
            }

            #searchInput {
                width: 35%;
                margin-left: 32%;
                margin-bottom: 1%;
                height: 35px;
            }
        }
        @media screen and (min-width: 1440px) {
            #startPage {
            height: 20%;
            }
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
        
        #btnSearch {
            background: orange;
            width: 30%;
            margin-left: 35%;
            height: 30px;
            cursor: pointer;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: 'Dosis', sans-serif;
            font-size: 1.5rem;
            color: white;
        }

        #searchInput {
            width: 30%;
            margin-left: 30%;
            margin-bottom: 3%;
        }
        

        @media screen and (min-width: 768px) {
            #btnSearch {
                height: 40px;
            }

            #searchInput {
                width: 35%;
                margin-left: 32%;
                margin-bottom: 1%;
                height: 35px;
            }

            #searchTagButton {
                width: 5%;
                height: 5%;
                padding : 1%;
            }
        }

        @media screen and (min-width: 1024px) {

            #searchInput {
                width: 35%;
                margin-left: 32%;
                margin-bottom: 1%;
                height: 40px;
            }
        }
        
        @media screen and (min-width: 1440) {
            #searchTagButton {
                width: 5%;
                height: 3%;
                padding : 0.2%;
            }
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

            <button type="button" id="menuLeftButton">Categorii</button>
            <aside class="menuLeftA">
                <h2>Categorii</h2>
                <ul><li>
                    <form action="story.controller.php" method="POST" >
                        <button class ="MenusButtons" type="submit" name="submit" value="5-6"> 5-6 ani </button>  
                    </form>
                    </li>
                    <li>
                    <form action="story.controller.php" method="POST" >
                        <button class ="MenusButtons" type="submit" name="submit" value="7-8"> 7-8 ani </button>
                    </form>
                    </li>
                    <li>
                    <form action="story.controller.php" method="POST" >
                        <button class ="MenusButtons" type="submit" name="submit" value="9-11"> 9-11 ani </button>
                    </form>
                    </li>
                </ul>
            </aside>
        
            <a href="../home/startPage.php" id="startPage">Ceva</a>
            <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo" >

            <form action="./story.controller.php" method="POST">
                <input type="text" placeholder="Search.." name="search" id="searchInput">
                <button type="submit" name="submit" id="searchTagButton" value="tags"><i class="fa fa-search"></i></button>
            </form>


            <button id = "btnSearch">Surpriza !</button>

            <div id="myModal" class="modal">
                <span class="close">&times;</span>
                <img src="../imgs/1.png" class="modal-content" id="img01" alt ="CatPic">
                <div id="caption"></div>
            </div>

            <div class="container">
                <?php
                    foreach($stories as $story) {
                        $small = substr($story['description'], 0, 100);
                        echo '
                        <div class="card">
                            <form action="../poveste/poveste.php?story='.$story['id'].'?page=1" method="POST">
                                <button type="submit" id="TitluButton" value="'.$story['id'].'">'.$story['title'].' de '.$story['autor'].'</button>
                            </form>
                            <p style="padding-bottom:0;">
                                '.$small.'..
                            </p>';
                            if($story['img'] !== NULL) {
                                echo '<img src="'.$story['img'].'" alt="StoryImg" class="storyImg">
                                </div>';
                            } else {
                                echo '</div>';
                            }
                        ;
                    }
                ?>
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
        });

        menuLeftButton.addEventListener('click', () => {
        menuLeftA.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
            menuLeftA.classList.remove('is--open');
            }
        });

        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        let body = document.querySelector('body');
        let btnSearch = document.getElementById('btnSearch');

        btnSearch.addEventListener('click', onClickSearch);

        function onClickSearch() {
            btnSearch.disabled = true;

            SearchApi = 'https://api.thecatapi.com/v1/images/search';
            console.log(SearchApi);
            fetch(SearchApi, {
                mode: 'cors'
            })
            .then(resp => resp.json())
            .then(jsonResp => {
                modal.style.display = "block";
                console.log(jsonResp);
                modalImg.src = jsonResp[0].url;
                captionText.innerHTML = "E o pisica !";
                
                btnSearch.disabled = false;
            })
            .catch(err => {
                alert(err);
                btnSearch.disabled = false;
            });
        }

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() { 
            modal.style.display = "none";
            }

    </script>
    </body>
</html>