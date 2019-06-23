<?php 
    session_start();
    
    include_once '../auth/auth.model.php';

    $user = NULL;

    if(isset($_SESSION['username']) && isset($_SESSION['hashedPassword'])) {
        $user = getLoggedUser($_SESSION['username'], $_SESSION['hashedPassword']);
    } else {
        header('Location: ./auth/auth.controller.php');
    }


    include_once './write.model.php';

    if(isset($_POST['submit'])) {
        switch($_POST['submit']) {
            case 'CREATE':
                $idStoryPrez = storyPrezInsert($_POST['Title'], $_POST['Autor'], $_POST['Description'], $_POST['Image'], $_POST['Age'], $user -> id);
                header('Location: ./writePages.php?story='.$idStoryPrez.'?page=1');
                break;
            case 'LOG OUT':
                session_unset();
                header('Location: ../index.php');
                break;
            default:
                break;
        }
    }

    include_once './writeForum.php';

?>