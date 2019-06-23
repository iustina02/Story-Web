<?php
    session_start();
    
    include_once '../auth/auth.model.php';

    $user = NULL;

    if(isset($_SESSION['username']) && isset($_SESSION['hashedPassword'])) {
        $user = getLoggedUser($_SESSION['username'], $_SESSION['hashedPassword']);
    } else {
        header('Location: ../auth/auth.controller.php');
    }
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <title>Story Web</title> 

        <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
        <link href="writeForum.css" rel="stylesheet">    
    
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
                    </form></li>
                </ul>
            </aside>

        <a href="../home/startPage.php" id="startPage">Ceva</a>
        <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo">

        <div class="container">
            <div class="card">
                <button type="button" id="SignUpButton">Descrierea povestii</button>
                <form action="./write.controller.php" method="post" class="formSignUp">
                    <p>
                        <label for="Title" >Titlu</label>
                        <input id="Title" name="Title" type="text" required>
                    </p>
                    <p>
                        <label for="Autor" >Autor</label>
                        <?php
                        echo '<input id="Autor" name="Autor" type="text" value="'.$user -> nume.' '.$user -> prenume.'" required>';
                        ?>
                    </p>
                    <p>
                        <label for="Description" >Introducere</label>
                        <input id="Description" name="Description" type="text" required>
                    </p>
                    <p>
                        <label for="Image" >Imagine</label>
                        <input id="Image" name="Image" type="text" required>
                    </p>
                    <p>
                        <label for="Age" >Categorie de varsta</label>
                        <input id="Age" name="Age" type="text" required>
                    </p>
                    <button type="submit" value="CREATE" id="submit" name="submit">Incepe sa scrii</button>
                </form>

            </div>
        </div>

        </div>

    <script>

        let menuRightButton = document.getElementById('menuRightButton');
        let menuRightA = document.querySelector('.menuRightA');

        menuRightButton.addEventListener('click', () => {
        menuRightA.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
            menuRightA.classList.remove('is--open');
            }
        });

    </script>
    </body>
</html>