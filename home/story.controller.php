<?php 
    include_once 'story.model.php';

    $queries = [];
    parse_str($_SERVER['QUERY_STRING'], $queries);

    if(isset($_POST['submit'])) {
        
        switch($_POST['submit']) {
            case '5-6':
                header('Location: ./startPage.php?age='.'5-6');
                break;
            case '7-8':
                header('Location: ./startPage.php?age='.'7-8');
                break;
            case '9-11':
                header('Location: ./startPage.php?age='.'9-11');
                break;
            case 'tags':
                header('Location: ./startPage.php?tags='.$_POST['search']);
                break;
            default:
                break;
        }
    }
?>