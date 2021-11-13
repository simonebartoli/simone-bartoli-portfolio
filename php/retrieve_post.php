<?php
    if(isset($_GET['postid'])){
        $postid = $_GET['postid'];
        $query = "SELECT users.name, users.surname, post.title, post.message, post.date, post.postID, post.banned
        FROM users INNER JOIN post ON users.accountID=post.accountID WHERE post.postID='$postid'";

        $result = mysqli_fetch_assoc(mysqli_query($db, $query));

        if(!empty($result)){
            $_SESSION['postID'] = $result['postID'];
            $data = $result;
            if(isset($_GET['reply'])){
                $selected = $_GET['reply'];
            }
        }
        else{
            redir("/blog.php");
        }
    }
    else{
        redir("/blog.php");
    }

?>