<?php

    include_once '../auth/auth.model.php';
    include_once './personaje.model.php';

    $user = NULL;

    $queries = [];
    parse_str($_SERVER['QUERY_STRING'], $queries);

    $parti = explode("?",$queries['story']);

    $idStory = $parti[0];

    if(isset($_POST['submit'])) {
        switch($_POST['submit']) {
            case 'Add':
                if(addCharacter($idStory, $_POST['nume'], $_POST['descriere'], $_POST['image'])) {
                    header('Location: ./personaje.php?story='.$idStory.'?page='.$index1.'');
                } else { echo 'Nu'; }
                break;
            case 'LOG OUT':
                session_unset();
                header('Location: ../index.php');
                break;
            default:
                break;
        }
    }

    include_once './personaje.php';
?>