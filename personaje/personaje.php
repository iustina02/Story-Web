<?php
    session_start();
    
    include_once '../auth/auth.model.php';
    include_once './personaje.model.php';
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

    $idStory = $parti[0];

    $characters = getPersInfo($idStory);

    $index = explode("=", $parti[1]);

    $index1 = $index[1];

    $storyInfo = getStoryInfo($idStory);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Story Web</title>

        <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
        <link href="personaje.css" rel="stylesheet">    

        <style>
            /* Button used to open the contact form - fixed at the bottom of the page */
            .open-button {
            background-color: #555;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            opacity: 0.8;
            position: fixed;
            bottom: 23px;
            right: 28px;
            width: 280px;
            }

            /* The popup form - hidden by default */
            .form-popup {
            display: none;
            position: fixed;
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
            }

            /* Add styles to the form container */
            .form-container {
            max-width: 300px;
            padding: 10px;
            background-color: white;
            }

            /* Full-width input fields */
            .form-container input[type=text], .form-container input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
            }

            /* When the inputs get focus, do something */
            .form-container input[type=text]:focus, .form-container input[type=password]:focus {
            background-color: #ddd;
            outline: none;
            }

            /* Set a style for the submit/login button */
            .form-container .btn {
            background-color: #a6c1ee;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom:10px;
            opacity: 0.8;
            }

            /* Add a red background color to the cancel button */
            .form-container .cancel {
            background-color: red;
            }

            /* Add some hover effects to buttons */
            .form-container .btn:hover, .open-button:hover {
            opacity: 1;
            }

            #doneButton {
                background: salmon;   
                position:absolute; 
                bottom:1%; 
                right:3%; 
                width: 20%;
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

            #startPage {
                background: #160480;
                position: absolute;
                width:46%;
                height: 20%;
                opacity: 0;
                top:18%;
                left: 27%;
            }
            
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
                height: 30%;
                top:3%;
                left: 35%;
                }
            }
            @media screen and (min-width: 1440px) {
                #startPage {
                height: 38%;
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
                    </form></li>
                </ul>
            </aside>

            <?php echo'
            <form action="../poveste/poveste.php?story='.$idStory.'?page='.$index1.'" method="POST">
                <button type="submit" id="menuLeftButton">Inapoi la poveste</button>
            </form>';

            foreach($storyInfo as $storyI){
                if($storyI['autor'] == ''.$user -> nume.' '.$user-> prenume.'')
                    {
                        echo '
                        <button class="open-button" onclick="openForm()">Adauga personaj</button>

                        <div class="form-popup" id="myForm">
                        <form action="./personaje.controller.php?story='.$idStory.'?page='.$index1.'" class="form-container" method="POST">
                            <h1>Adauga</h1>

                            <label for="nume"><b>Nume</b></label>
                            <input type="text" placeholder="Nume.." name="nume" required>

                            <label for="descriere"><b>Descriere</b></label>
                            <input type="text" placeholder="Descriere.." name="descriere">

                            <label for="image"><b>Url imagine </b></label>
                            <input type="text" placeholder="Img url.." name="image">

                            <button type="submit" class="btn" value="Add" id="submit" name="submit">Adauga</button>
                            <button type="button" class="btn cancel" onclick="closeForm()">Inchide</button>
                        </form>
                        </div>';
                    }
                    
            }
            ?>

            <a href="../home/startPage.php" id="startPage">Ceva</a>
            <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo" >

            <div class="container">
                <?php 
                    foreach($characters as $character){
                        echo '
                        <div class="card">
                        <h1 id="TitluButton">'.$character['name'].'</h1>';
                        if($character['description'] !== NULL) {
                            echo '
                            <p>'.$character['description'].'</p>
                            ';
                        }
                        if($character['img'] !== NULL) {
                            echo '
                            <img src="'.$character['img'].'" alt="PersImg" class="storyImg">
                            ';
                        } 
                        
                        echo '</div>';
                    }
                ?>
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

        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>
    </body>
</html>