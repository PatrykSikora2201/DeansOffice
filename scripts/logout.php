<?php
    session_start();
    
    session_unset();

    unset($_SESSION['logged']);
    unset($_SESSION['loggedStudent']);

    header("Location: ../index.php");

?>
