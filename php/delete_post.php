<?php
    session_start();
    $path = "/login.php";
    $redir = true; //only redirect if it is in private access only page

    require $_SERVER['DOCUMENT_ROOT']. '/php/init_connection.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php';
    if($_SESSION['role']!="admin"){
        redir("/blog.php");
    }
    
    if(isset($_GET['post']) && isset($_GET['name']) && isset($_GET['date'])){
        $info = explode(" ", $_GET['name']);
        $name = $info[0];
        $surname = $info[1];
        $date = $_GET['date'];

        if($_GET['post']=="true"){
            $query = "SELECT accountID FROM users WHERE name='$name' AND surname='$surname'";
            $accountID = mysqli_fetch_assoc(mysqli_query($db, $query))['accountID'];
            $query = "SELECT postID FROM post WHERE accountID='$accountID' AND date='$date'";
            $postID = mysqli_fetch_assoc(mysqli_query($db, $query))['postID'];

            mysqli_query($db, "DELETE FROM post WHERE postID='$postID'");
            redir("/blog.php");
        }
        elseif($_GET['post']=="false"){
            $query = "SELECT accountID FROM users WHERE name='$name' AND surname='$surname'";
            $accountID = mysqli_fetch_assoc(mysqli_query($db, $query))['accountID'];

            $query = "DELETE FROM reply WHERE accountID='$accountID' AND date='$date'";
            mysqli_query($db, $query);
            redir("/blog.php");
        }
        else{
            redir("/blog.php");
        }
    }
    else{
        redir("/blog.php");
    }
    

?>