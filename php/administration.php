<?php
    $query = "SELECT users.name, users.surname, users.email, users.accountID, terms.date
    FROM users INNER JOIN terms ON users.accountID=terms.accountID WHERE users.accountID != 1 ORDER BY surname ASC";
    $result = mysqli_query($db, $query);
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }

    if(isset($_POST['search'])){
        require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 

        $id = $_POST['idsearch'];
        $query = "SELECT users.name, users.surname, limiteduser.rule, limiteduser.status, limiteduser.date
        FROM users LEFT JOIN limiteduser ON users.accountID=limiteduser.accountID WHERE users.accountID = '$id'";
        $result = mysqli_query($db, $query);

        reset($data);
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
            if($data['status'] == "banned"){
                echo json_encode("The user has been banned");
                exit;
            }
        }
        echo json_encode($data);
    }

    if(isset($_POST['find_post'])){
        require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
        $id = $_POST['idsearch'];

        $query = "SELECT title, date, postID, banned FROM post WHERE accountID='$id'";
        $result = mysqli_query($db, $query);
        reset($data);
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        $query = "SELECT message, date, replyID, postID, banned FROM reply WHERE accountID='$id'";
        $result = mysqli_query($db, $query);
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }

        array_multisort(array_column($data, 'date'), SORT_DESC, $data);
        for($i=0; $i<count($data); $i++){
            if(isset($data[$i]['replyID'])){
                $data[$i]['type'] = "REPLY";
            }
            else{
                $data[$i]['type'] = "POST";
            }
        }

        echo json_encode($data);
    }

    if(isset($_POST['submit'])){
        require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
        date_default_timezone_set('Europe/London');
        $date = date("Y-m-d H:i:s");

        $accountID = $_POST['idsearch'];
        $postexist = false;
        $replyexist = false;

        if(isset($_POST['postsearch']) && !empty($_POST['postsearch'])){
            $postexist = true;
            $postID = $_POST['postsearch'];
        }
        if(isset($_POST['replysearch']) && !empty($_POST['replysearch'])){
            $replyexist = true;
            $replyID = $_POST['replysearch'];
        }
        $limit = $_POST['limit'];
        $rule = $_POST['rule'];
        $reportN = $_POST['reportN'];

        if($limit == "ban"){
            $query = "UPDATE users SET banned=1 WHERE accountID='$accountID'";
            mysqli_query($db, $query);

            if($replyexist){
                $query = "INSERT INTO limiteduser (accountID, postID, replyID, status, rule, date) VALUES ('$accountID', '$postID', '$replyID', 'banned', '$rule', '$date')";
            }elseif($postexist){
                $query = "INSERT INTO limiteduser (accountID, postID, status, rule, date) VALUES ('$accountID', '$postID', 'banned', '$rule', '$date')";
            }else{
                $query = "INSERT INTO limiteduser (accountID, status, rule, date) VALUES ('$accountID', 'banned', '$rule', '$date')";
            }
            mysqli_query($db, $query);


            $query = "UPDATE post SET banned=1 WHERE accountID='$accountID'";
            mysqli_query($db, $query);
            $query = "UPDATE reply SET banned=1 WHERE accountID='$accountID'";
            mysqli_query($db, $query);

        }elseif($limit == "suspend"){
            if($reportN > 2){
                $query = "UPDATE users SET banned=1 WHERE accountID='$accountID'";
                mysqli_query($db, $query);

                if($replyexist){
                    $query = "INSERT INTO limiteduser (accountID, postID, replyID, status, rule, date) VALUES ('$accountID', '$postID', '$replyID', 'suspended', '$rule', '$date')";
                }elseif($postexist){
                    $query = "INSERT INTO limiteduser (accountID, postID, status, rule, date) VALUES ('$accountID', '$postID', 'suspended', '$rule', '$date')";
                }else{
                    $query = "INSERT INTO limiteduser (accountID, status, rule, date) VALUES ('$accountID', 'suspended', '$rule', '$date')";
                }
                mysqli_query($db, $query);

                $query = "INSERT INTO limiteduser (accountID, status, rule, date) VALUES ('$accountID', 'banned', 'A15', '$date')";
                mysqli_query($db, $query);

                $query = "UPDATE post SET banned=1 WHERE accountID='$accountID'";
                mysqli_query($db, $query);
                $query = "UPDATE reply SET banned=1 WHERE accountID='$accountID'";
                mysqli_query($db, $query);

            }else{
                $query = "UPDATE users SET limited=1 WHERE accountID='$accountID'";
                mysqli_query($db, $query);
                if($reportN == 1){
                    $expiry = date("Y:m:d H:i:s", strtotime("+2 days", time()));
                }else{
                    $expiry = date("Y:m:d H:i:s", strtotime("+7 days", time()));
                }

                if($replyexist){
                    $query = "INSERT INTO limiteduser (accountID, postID, replyID, status, rule, date, expirydate) VALUES ('$accountID', '$postID', '$replyID', 'suspended', '$rule', '$date', '$expiry')";
                }elseif($postexist){
                    $query = "INSERT INTO limiteduser (accountID, postID, status, rule, date, expirydate) VALUES ('$accountID', '$postID', 'suspended', '$rule', '$date', '$expiry')";
                }else{
                    $query = "INSERT INTO limiteduser (accountID, status, rule, date, expirydate) VALUES ('$accountID', 'suspended', '$rule', '$date', '$expiry')";
                }
                mysqli_query($db, $query);

                if($replyexist){
                    $query = "UPDATE reply SET banned=1 WHERE accountID='$accountID' AND postID='$postID' AND replyID='$replyID'";
                }else{
                    $query = "UPDATE post SET banned=1 WHERE accountID='$accountID' AND postID='$postID'";
                }
                mysqli_query($db, $query);
            }
        }

        echo json_encode("Execute correctly");
    }

?>