
<?php

session_start();
    
include_once '../auth/auth.model.php';
include_once 'write.model.php';
include_once '../poveste/poveste.model.php';

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

$storyInfo = getStoryInfo($idStory);

$storyWr = getStory($idStory, $index1);

$txt = NULL;
$img = NULL;

foreach($storyWr as $storyW){
    $txt = $storyW['content_text'];
    $img = $storyW['content_img'];
}

?>
<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story Web</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
    <link href="writePages.css" rel="stylesheet">    
    

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

    <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo">

    <div class="container">
        <div class="card">
            <?php
            foreach($storyInfo as $storyI){
                    echo '
                    <form action="writePages.controller.php?story='.$idStory.'?page='.$index1.'" method="POST">
                        <h1>'.$storyI['title'].' de '.$storyI['autor'].'</h1>
                        <p>
                            <label for="Text" >Povestea ta</label>
                            <textarea id="Text" name="Text" placeholder="Scrie ceva..">'.$txt.'</textarea>
                        </p>
                        <p>
                            <label for="Image" >Image</label>
                            <textarea id="Image" name="Image" placeholder="URL imagine..">'.$img.'</textarea>
                        </p>
                        <p id="WriteAP" >Scrie o noua pagina</p>
                        <button class="nextPage" type="submit" name="submit" value="nextPage"><i class="arrow right"></i></button>
                        <button id="doneButton" style="width: 10%; height: 7%;" type="submit" name="submit" value="done">Termina</button>
                    </form>';
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