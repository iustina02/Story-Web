<?php 
    session_start();
    
    include_once './auth.model.php';

    $loginStatus = NULL;
    $registerStatus = NULL;

    if(isset($_POST['submit'])) {
        switch($_POST['submit']) {
            case 'LOGIN':
                $user = login($_POST['Username'], $_POST['password']);
                if($user !== NULL) {
                    header('Location: ../home/startPage.php');
                    $_SESSION['username'] = $user -> username;
                    $_SESSION['hashedPassword'] = $user -> hashedPassword;
                } else {
                    $loginStatus = false;
                }
                break;
            case 'REGISTER':
                $registerStatus = register($_POST['Nume'], $_POST['Prenume'], $_POST['Username'], $_POST['Email'], $_POST['password']);
                break;
            case 'LOG OUT':
                session_unset();
                header('Location: ../index.php');
                break;
            default:
                break;
        }
    }

    include_once './login_signup.php';
?>