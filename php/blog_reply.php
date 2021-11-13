<?php
    session_start();
    $error = 0;

    $empty = isEmpty();
    if(!$empty){
        $reply_data = getData();
        while($row = mysqli_fetch_assoc($reply_data)){
            $repl[] = $row;
        }

        array_multisort(array_column($repl, 'date'), SORT_ASC, $repl);
    }

    if(isset($_POST['reply_submit'])){
        if(empty($_POST['message'])){$error++;}
        if($error==0){
            $message = mysqli_escape_string($db, $_POST['message']);
            date_default_timezone_set('Europe/London');
            $date = date("Y-m-d H:i:s");
            $accountID = $_SESSION['accountID'];
            $postID = $_SESSION['postID'];

            $query = "INSERT INTO reply (postID, accountID, message, date) VALUES ('$postID', '$accountID', '$message', '$date')";
            mysqli_query($db, $query);

            $ip = retrieveIP();
            $query = "INSERT INTO ip_log (accountID, IP, date) VALUES ('$accountID', '$ip', '$date')";
            mysqli_query($db, $query);

            redir($_SERVER['REQUEST_URI']. $_SERVER['QUERY_STRING']);
        }
        else{
            redir("/blog.php");
        }
    }

    function isEmpty(){
        global $db;
        $postID = $_SESSION['postID'];
        $query = "SELECT * FROM reply WHERE postID ='$postID'";
        $check = mysqli_fetch_assoc(mysqli_query($db, $query));
        if(is_null($check['postID'])){
            return true;
        }
        else{
            return false;
        }
    }

    function getData(){
        global $db;
        $postID = $_SESSION['postID'];

        $query =    "SELECT users.name, users.surname, reply.message, reply.date, reply.replyID 
                    FROM users 
                    INNER JOIN reply ON users.accountID=reply.accountID
                    WHERE reply.postID ='$postID' AND reply.banned=false";
        return mysqli_query($db, $query);
    }
    
    function retrieveIP(){
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        return $ip;
    }
?>