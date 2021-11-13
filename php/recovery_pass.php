<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    require $_SERVER['DOCUMENT_ROOT']. '/php/sendemail.php'; 
    $info = array();
    date_default_timezone_set('Europe/London');
    if(isset($_POST['recovery_submit'])){
        if(empty($_POST['email'])){
            $info = array('status'=>"error", 'message'=>"The email cannot be blank");
            echo json_encode($info);
            exit();
        }
        else{
            $email = strtolower($_POST['email']);
            $query = "SELECT * FROM users WHERE email='$email'";
            $result = mysqli_query($db, $query);
            if(!$result){
                $info = array('status'=>"error", 'message'=>"We can't connect to our database");
                echo json_encode($info);
                exit();
            }
            $result = mysqli_fetch_assoc($result);
            if(!empty($result['email'])){
                $access = true;
                if(!$result['banned']){
                    if($result['limited']){
                        $accountID = $result['accountID'];
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
                        $token = genToken();
                        $time = date("Y:m:d H:i:s", strtotime("1 hour", time()));
                        $accountID = $result['accountID'];
        
                        $query = "UPDATE access_token SET auth_token='$token', expirydate='$time' WHERE accountID='$accountID'";
                        mysqli_query($db, $query);
        
                        $link = "https://bartolisimone.com/php/recovery_pass.php?token=". $token;
                        recoveryForm($email, $link, $result['name'], $result['surname']);
                        if($info['status']=="error"){
                            echo json_encode($info);
                            exit();
                        }
                    }
                }
       
            }
            $info = array('status'=>"success", 'message'=>"An email has been sent to the address provided");
            echo json_encode($info);
        }
    }
    else{
        if(isset($_GET['token']) && strlen($_GET['token'])==64){
            $token = $_GET['token'];
            $query = "SELECT * FROM access_token WHERE auth_token='$token'";
            $result = mysqli_fetch_assoc(mysqli_query($db, $query));
            if(!empty($result['auth_token'])){
                if(time()<strtotime($result['expirydate'])){
                    $query = "UPDATE access_token SET auth_token=NULL, expirydate=NULL WHERE auth_token = '$token'";
                    mysqli_query($db, $query);

                    $accountID = $result['accountID'];
                    $result = mysqli_fetch_assoc(mysqli_query($db, "SELECT users.*, phone.verified FROM users LEFT JOIN phone ON users.accountID=phone.accountID WHERE users.accountID='$accountID'"));
    
                    $_SESSION['accountID'] = $result['accountID'];
                    $_SESSION['email'] = $result['email'];
                    $_SESSION['name'] = $result['name'];
                    $_SESSION['surname'] = $result['surname'];
                    $_SESSION['dob'] = $result['dob'];
                    $_SESSION['role'] = $result['role'];
                    $_SESSION['verified'] = $result['verified'];
                   
                    session_regenerate_id();
                    $secret = password_hash($_SESSION['email']."Ciao@123456", PASSWORD_DEFAULT);
                    setcookie("session_log", $secret, time() + 3600, "/", "", "" ,true); 
                    redir('/account.php');
                }
                else{
                    $query = "UPDATE access_token SET auth_token=NULL, expirydate=NULL WHERE auth_token = '$token'";
                    mysqli_query($db, $query);
                    redir('/index.php');
                }
            }
            else{
                redir('/index.php');
            }
        }
        redir('/index.php');
    }



    function genToken(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 64; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

?>