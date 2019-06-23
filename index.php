<?php 

    // $TWO_HOURS = 60 * 60 * 2;

        ini_set('session.cookie_lifetime', $TWO_HOURS);
        ini_set('session.gc_maxlifetime', $TWO_HOURS);
        
        session_start();

        if(isset($_SESSION['username']) && isset($_SESSION['hashedPassword'])) {
            header('Location: ../home/startPage.php');
        } else {
            header('Location: ../auth/auth.controller.php');
        }
?>