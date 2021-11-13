<?php
    session_start();
    if(!isset($_COOKIE['session_log']) && isset($_SESSION['accountID'])){
        session_unset();
        session_destroy();
        redir($path);
    }
    
    else if(isset($_COOKIE['session_log'])){
        $validate = false;
        $access = true;

        $cookie = $_COOKIE['session_log'];
        $query = "SELECT users.*, phone.verified FROM users LEFT JOIN phone ON users.accountID=phone.accountID";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            if(password_verify($row['email']."Ciao@123456", $cookie)){
                if(!$row['banned']){
                    if($row['limited']){
                        $accountID = $row['accountID'];
                        $query = "SELECT * FROM limiteduser WHERE accountID='$accountID' ORDER BY reportID DESC LIMIT 1";
                        $limit = mysqli_fetch_assoc(mysqli_query($db, $query));
                        if(time()>strtotime($limit['expirydate'])){
                            $query = "UPDATE users SET limited=0 WHERE accountID='$accountID'";
                            mysqli_query($db, $query);
                        }else{
                            $access = false;
                        }
                    }
                    if($access){
                        if(!isset($_SESSION['accountID'])){
                            $_SESSION['accountID'] = $row['accountID'];
                            $_SESSION['email'] = $row['email'];
                            $_SESSION['name'] = $row['name'];
                            $_SESSION['surname'] = $row['surname'];
                            $_SESSION['dob'] = $row['dob'];
                            $_SESSION['role'] = $row['role'];
                        }
                        $_SESSION['verified'] = $row['verified'];
                        session_regenerate_id();
                        $validate = true;
                        break;
                    }
                }
            }
        }
        
        if(!$validate){
            if(isset($_SESSION['accountID'])){
                session_unset();
                session_destroy();
            }
            setcookie("session_log", "", time() -60, "/");
            redir("/index.php");
        }
        
    }
    
    elseif(!isset($_COOKIE['session_log']) && !isset($_SESSION['accountID']) && $redir){
        redir($path);
    }    
?>