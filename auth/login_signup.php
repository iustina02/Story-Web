<?php
 include_once 'auth.controller.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <title>Log In Story Web</title> 

        <link href="https://fonts.googleapis.com/css?family=Dosis:400,700" rel="stylesheet">
        <link href="login_signup.css" rel="stylesheet">    
    
    </head>

    <body>

        <div id="root"> 

        <img src="../imgs/Logo.gif" alt="LogoImage" class ="logo">

        <div class="container">
            <div class="card">
                <button type="button" id="SignUpButton">Inregistreaza-te</button>
                <form action="./auth.controller.php" method="post" class="formSignUp">
                    <p>
                        <label for="Nume" >Nume</label>
                        <input id="Nume" name="Nume" type="text" required>
                    </p>
                    <p>
                        <label for="Prenume" >Prenume</label>
                        <input id="Prenume" name="Prenume" type="text" required>
                    </p>
                    <p>
                        <label for="Username" >Username</label>
                        <input id="Username" name="Username" type="text" required>
                    </p>
                    <p>
                        <label for="Email" >Email</label>
                        <input id="Email" name="Email" type="text" required>
                    </p>
                    <p>
                        <label for="password" >Parola</label>
                        <input id="password" name="password" type="password" required>
                    </p>
                    <p>
                        <label for="confirm_password" >Confirma parola</label>
                        <input id="confirm_password" name="confirm_password" type="password" required>
                    </p>
                    <button type="submit" value="REGISTER" id="submit" name="submit">Creaza contul</button>
                    <?php
                        if($registerStatus === 1) {
                            echo '<p class="success" style="font-size: 20px;"> Register success!</p>';
                        } else if($registerStatus === 0) {
                            echo '<p class="error" style="color: red; font-size: 20px;"> Username taken!</p>';
                        } else if($registerStatus === -1) {
                            echo '<p class="error" style="color: red; font-size: 20px;"> Email taken!</p>';
                        }
                    ?>
                </form>

            </div>

            <div class="card">
                    <button type="button" id="LogInButton">Log In</button>
                <form action="./auth.controller.php" method="post" class="formLogIn">
                    <p>
                        <label for="Username" >Username</label>
                        <input id="Username2" name="Username" type="text" required>
                    </p>
                    <p>
                        <label for="password" >Parola</label>
                        <input id="password2" name="password" type="password" required>
                    </p>
                    <button type="submit" value="LOGIN" id="submit2" name="submit">Log In</button>
                    <?php
                        if(isset($loginStatus) && $loginStatus === false) {
                            echo '<p class="error" style="color: red; font-size: 20px;"> Login error!</p>';
                        } 
                    ?>
                </form>
            </div>
        </div>

        </div>

    <script>
        let SignUpButton = document.getElementById('SignUpButton');
        let formSignUp = document.querySelector('.formSignUp');

        let LogInButton = document.getElementById('LogInButton');
        let formLogIn = document.querySelector('.formLogIn');

        SignUpButton.addEventListener('click', () => {
            formSignUp.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
            formSignUp.classList.remove('is--open');
            }
        });

        LogInButton.addEventListener('click', () => {
            formLogIn.classList.add('is--open');
        });

        document.addEventListener('keyup', (e) => {
            if(e.key === 'Escape' || e.keyCode ===27) {
                formLogIn.classList.remove('is--open');
            }
        });
    </script>
    </body>
</html>