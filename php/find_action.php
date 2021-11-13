<?php
    session_start();
    $page_number = 0;
    if(isset($_GET['page'])){
        $page_number = $_GET['page'];
    }

    $accountID = $_SESSION['accountID'];
    $query = "SELECT title, date, postID FROM post WHERE accountID = '$accountID'";
    $result = mysqli_query($db, $query);

    $index = 0;
    while($row = mysqli_fetch_assoc($result)){
        $post_entry[$index] = $row;
        $post_entry[$index]['type'] = "post";
        $index++;
    } 


    $query = "SELECT message, date, postID, replyID FROM reply WHERE accountID = '$accountID'";
    $result = mysqli_query($db, $query);

    while($row = mysqli_fetch_assoc($result)){
        $post_entry[$index] = $row;
        $post_entry[$index]['type'] = "reply";

        $post_entry[$index]['message'] = substr($post_entry[$index]['message'], 0, 20). "...";
        $index++;
    } 
    if(isset($post_entry)){
        array_multisort(array_column($post_entry, 'date'), SORT_DESC, $post_entry);
        $page = ceil(count($post_entry)/4);
    }


?>