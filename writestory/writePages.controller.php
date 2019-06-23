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

    $queries = [];
    parse_str($_SERVER['QUERY_STRING'], $queries);

    $parti = explode("?",$queries['story']);

    $index = explode("=", $parti[1]);

    $idStory = $parti[0];

    $index1 = $index[1];

    $index2 = $index1 +1;

    if(isset($_POST['submit'])) {
        switch($_POST['submit']) {
            case 'nextPage':
                if(storyPageInsert($index1, $_POST['Text'], $_POST['Image'],  $idStory)) {
                    header('Location: ./writePages.php?story='.$idStory.'?page='.$index2.'');
                }
                break;
            case 'done': 
                if(storyPageInsert($index1, $_POST['Text'], $_POST['Image'],  $idStory)) {
                    header('Location: ../home/startPage.php');
                }
                break;
            default:
                break;
            }
    }
?>