<?php
    require $_SERVER['DOCUMENT_ROOT']. '/php/redirect.php';
    if(isset($_SESSION['accountID'])){
        setcookie("session_log", "", time() - 60, "/");
        session_unset();
        session_destroy();
    }
    redir('/index.php');
?>