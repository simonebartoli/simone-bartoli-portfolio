<?php
    require $_SERVER['DOCUMENT_ROOT']. '/php/redirect.php';    
    if(!isset($_COOKIE['error_connection'])){
        $db = mysqli_connect('host', 'user', 'pass', 'dbname');
        mysqli_set_charset($db , "utf8mb4");
    }
    if (mysqli_connect_errno() || isset($_COOKIE['error_connection'])) {
        if(!isset($_COOKIE['error_connection'])){
            setcookie("error_connection", true, time()+30, "/");
        }
        if($_SERVER['REQUEST_URI'] != "/index.php"){
            redir("/index.php");
            exit;
        }
    }
?>