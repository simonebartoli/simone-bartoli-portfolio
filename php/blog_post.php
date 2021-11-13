<?php
    session_start();
    $errors = array();
    $empty = isEmpty();
    if(!$empty){
        $data = getData();
        if(isset($_POST['month'])){
            while($row = mysqli_fetch_assoc($data)){
                if(date("F - Y", strtotime($row['date'])) == $_POST['month']){
                    $info[] = $row;
                }
            }
        }
        else{
            while($row = mysqli_fetch_assoc($data)){
                $info[] = $row;
            }
        }
        array_multisort(array_column($info, 'date'), SORT_DESC, $info);

        $date_post = getDatePost();
    }

    if(isset($_POST['post_submit']) && $_SESSION['verified']){
        if(empty($_POST['title'])){array_push($errors, "- A title is required");}
        if(empty($_POST['message'])){array_push($errors, "- A message is required");}

        if(count($errors)==0){
            $accountID = $_SESSION['accountID'];
            $message = mysqli_real_escape_string($db, $_POST['message']);
            $title = mysqli_real_escape_string($db, $_POST['title']);
            date_default_timezone_set('Europe/London');
            $date = date("Y-m-d H:i:s");

            $query = "INSERT INTO post (accountID, title, message, date) VALUES ('$accountID', '$title', '$message', '$date')";
            mysqli_query($db, $query);

            $ip = retrieveIP();
            $query = "INSERT INTO ip_log (accountID, IP, date) VALUES ('$accountID', '$ip', '$date')";
            mysqli_query($db, $query);
            redir('/blog.php');
        }
    }


    function isEmpty(){
        global $db;
        $query = "SELECT * FROM post";
        $result = mysqli_fetch_assoc(mysqli_query($db, $query));
        if(is_null($result['postID'])){
            return true;
        }
        else{
            return false;
        }
    }

    function getData(){
        global $db;
        $query =    "SELECT users.name, users.surname, post.title, post.message, post.date, post.postID
                    FROM post INNER JOIN users ON users.accountID=post.accountID WHERE post.banned=false";
        return mysqli_query($db, $query);
    }

    function getReplyNumber($index){
        global $db;
        global $info;

        $postID = $info[$index]['postID'];

        $query =    "SELECT replyID FROM reply
                    WHERE reply.postID ='$postID' AND banned=false";
        
        $result = mysqli_query($db, $query);
        while($row = mysqli_fetch_assoc($result)){
            $temp[] = $row;
        }

        $counter = count($temp);
        return $counter;
    }

    function getDatePost(){
        global $db;
        $query = "SELECT date FROM post WHERE banned=false ORDER BY date DESC";
        $result = mysqli_query($db, $query);
        while($row = mysqli_fetch_assoc($result)){
            $final[] = date("F - Y", strtotime($row['date']));
        }
        return array_unique($final);
    }

    function retrieveIP(){
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        return $ip;
    }


?>