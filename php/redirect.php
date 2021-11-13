<?php
    session_start();
    function redir($path){
        header("Location: $path", TRUE, 301);
        exit();
    }
?>