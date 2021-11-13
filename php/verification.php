<?php
    session_start();
    $status = array();
    date_default_timezone_set('Europe/London');
    
    const ERROR_API = "Error during API call\n";
    const ERROR_FILE = "The specified file does not exist\n";
    const URL = "https://api.smsmode.com/http/1.6/";
    const PATH_SEND_SMS = "sendSMS.do";
    const PATH_SEND_SMS_BATCH = "sendSMSBatch.do";

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    $accountID = $_SESSION['accountID'];

    if(isset($_POST['find_info'])){
        $query = "SELECT expirydate, phoneN FROM phone WHERE accountID='$accountID'";
        $result = mysqli_fetch_assoc(mysqli_query($db, $query));
        $result['expirydate'] = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($result['expirydate'])));

        $status['phoneN'] = $result['phoneN'];
        $status['date'] = $result['expirydate'];
        echo json_encode($status);
        exit;
    }

    $result = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM phone WHERE accountID='$accountID'"));
    if(!empty($result)){
        $_SESSION['phoneN'] = $result['phoneN'];
        if($result['verified']){
            redir("/error.php");
        }
        if(time()>strtotime($result['expirydate'])){
            mysqli_query($db, "UPDATE phone SET banned=true WHERE accountID='$accountID'");
            mysqli_query($db, "UPDATE phone SET reason='You have exceeded the maximum time available to submit the code' WHERE accountID='$accountID'");
            $result = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM phone WHERE accountID='$accountID'"));
        }
        if($result['banned']){
            $status['banned'] = true;
            $status['reason'] = mysqli_fetch_assoc(mysqli_query($db, "SELECT reason FROM phone WHERE accountID='$accountID'"))['reason'];
        }else{
            if(isset($_POST['step3'])){
                verifyAccount();
            }
        }
        $status['error'] = "SMS already sent";
        echo json_encode($status);
        exit;
    }

    if(isset($_POST['find_countries'])){
        $result = mysqli_query($db, "SELECT prefix FROM prefix_number");
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        sort($data);
        echo json_encode($data);
        exit;
    }



    if(isset($_POST['step1'])){
        $exit = false;
        if(!isset($_POST['prefix']) || empty($_POST['prefix'])){
            $status['error'] = "PREFIX needs to be specified";
            $exit = true;
        }
        if(!isset($_POST['phoneN']) || empty($_POST['phoneN'])){
            $status['error'] = "PHONE NUMBER needs to be specified";
            $exit = true;
        }else if(strlen($_POST['phoneN'])<7 || strlen($_POST['phoneN'])>15){
            $status['error'] = "PHONE NUMBER is not in a valid format";
            $exit = true;
        }else if(!is_numeric($_POST['phoneN'])){
            $status['error'] = "PHONE NUMBER cannot be a string";
            $exit = true;
        }
        
        if($exit){
            echo json_encode($status);
            exit;
        }else{
            $prefix = $_POST['prefix'];
            $phoneN = $_POST['phoneN'];

            $query = "SELECT * FROM prefix_number WHERE prefix = '$prefix'";
            $result =  mysqli_fetch_assoc(mysqli_query($db, $query));

            if(!empty($result)){
                if(!is_null($result['trunk'])){
                    $trunk = $result['trunk'];
                    if($phoneN[0] == $trunk){
                        $phoneN = substr($phoneN, 1);
                    }elseif($phoneN[0].$phoneN[1] == $trunk){
                        $phoneN = substr($phoneN, 2);
                    }
                }
                if(!empty(mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM phone WHERE phoneN='$prefix$phoneN' AND verified=true")))){
                    $status['error'] = "PHONE NUMBER already used";
                    echo json_encode($status);
                    exit;
                }
                $cost = $result['cost'];
                $query = "SELECT * FROM banned_number WHERE phoneN = '$prefix$phoneN'";
                $result =  mysqli_fetch_assoc(mysqli_query($db, $query));
                if(!empty($result)){
                    $status['error'] = "PHONE NUMBER is not valid";
                }else{
                    $_SESSION['cost'] = $cost;
                    $_SESSION['prefix'] = $prefix;
                    $_SESSION['phoneN'] = $phoneN;
                    $status['success'] = $prefix.$phoneN;
                }
                echo json_encode($status);
                exit;
                
            }
            else{
                $status['error'] = "PREFIX is not valid";
                echo json_encode($status);
                exit;
            }
        }
    }

    if(isset($_POST['step2'])){
        if(isset($_SESSION['prefix']) && isset($_SESSION['phoneN'])){
            $cost = $_SESSION['cost'];
            $prefix = $_SESSION['prefix'];
            $phoneN = $_SESSION['phoneN'];

            mysqli_query($db, "DELETE FROM smsmode WHERE credit<0.5"); 
            $query = "SELECT * FROM smsmode";
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_assoc($result)){
                $smsmode[] = $row;
            }
            for($i=0; $i<count($smsmode); $i++){
                if($smsmode[$i]['credit']>=$cost){
                    $accessToken = $smsmode[$i]['token'];
                    mysqli_query($db, "UPDATE smsmode SET credit=credit-$cost WHERE token='$accessToken'");
                    break;
                }
            }
            if(isset($accessToken)){
                $code = setCode();
                sendOTP($code, $accessToken);
                $status['success'] = "The code has been sent";
                echo json_encode($status);
                $_SESSION['phoneN'] = $_SESSION['prefix'].$_SESSION['phoneN'];
                exit;
            }else{
                $status['error'] = "SERVICE cannot be reached";
                echo json_encode($status);
                exit;
            }
        }else{
            $status['error'] = "PHONE NUMBER is not valid";
            echo json_encode($status);
            exit;
        }
    }

    function verifyAccount(){
        global $status;
        global $db;
        global $accountID;

        if(!isset($_POST['code']) || empty($_POST['code'])){
            $status['error'] = "CODE cannot be empty";
            echo json_encode($status);
            exit;
        }else{
            $code = $_POST['code'];
            $query = "SELECT * FROM phone WHERE accountID='$accountID'";
            $result = mysqli_fetch_assoc(mysqli_query($db, $query));
            if($result['banned']){
                $status['error'] = "You cannot verify your phone anymore";
                $status['reload'] = true;
                echo json_encode($status);
                exit;
            }
            elseif(time()>strtotime($result['expirydate'])){
                $status['error'] = "TIME to submit the code has expired";
                mysqli_query($db, "UPDATE phone SET banned=true WHERE accountID='$accountID'");
                mysqli_query($db, "UPDATE phone SET reason='You have exceeded the maximum time available to submit the code' WHERE accountID='$accountID'");
                $status['reload'] = true;
                echo json_encode($status);
                exit;
            }

            if($code == $result['OTP']){
                mysqli_query($db, "UPDATE phone SET verified=true WHERE accountID='$accountID'");
                $phoneN = $_SESSION['phoneN'];
                mysqli_query($db, "UPDATE phone SET banned=true WHERE phoneN='$phoneN' AND accountID!='$accountID'");
                mysqli_query($db, "UPDATE phone SET reason='This phone number has been verified by someone else' WHERE phoneN='$phoneN' AND accountID!='$accountID'");

                $_SESSION['verified'] = true;
                $status['success'] = "Your account has been verified";
                echo json_encode($status);
                exit;
            }else{
                $status['error'] = "CODE is not valid";
                mysqli_query($db, "UPDATE phone SET counter=counter+1 WHERE accountID='$accountID'");
                if(mysqli_fetch_assoc(mysqli_query($db, "SELECT counter FROM phone WHERE accountID='$accountID'"))['counter']>2){
                    mysqli_query($db, "UPDATE phone SET banned=true WHERE accountID='$accountID'");
                    mysqli_query($db, "UPDATE phone SET reason='You have exceeded the maximum 3 attemps' WHERE accountID='$accountID'");
                    $status['reload'] = true;
                    $status['error'] = "CODE is not valid and you finished your maximum attemps";
                }
                echo json_encode($status);
                exit;
            }
        }
    }

    function setCode(){
        global $db;

        $code = mt_rand(100000,999999);
        $accountID = $_SESSION['accountID'];
        $phoneN = $_SESSION['prefix'].$_SESSION['phoneN'];
        $date = date("Y-m-d H:i:s", strtotime("+1 hour", time()));

        mysqli_query($db, "INSERT INTO phone (phoneN, accountID, OTP, expirydate) VALUES ('$phoneN', '$accountID', $code, '$date')");
        return $code;
    }


    function sendOTP($code, $accessToken){
        global $status;
        global $db;

        $accountID = $_SESSION['accountID'];

        $message = iconv("UTF-8", "ISO-8859-15", "Your Security Code is ". $code);
        $destinataires = $_SESSION['prefix'].$_SESSION['phoneN'];
        $emetteur="SimoneB";

        $fields_string = 'accessToken='.$accessToken.'&message='.urlencode($message).'&numero='.$destinataires.'&emetteur='.$emetteur;
        /*
        *    Function parameters:
        *
        *    - accessToken (required)
        *    - message (required)
        *    - destinataires (required): Receivers separated by a comma
        *    - emetteur (optional): Allows to deal with the sms sender
        *    - optionStop (optional): Deal with the STOP sms when marketing send (cf. API HTTP documentation)
        *    - batchFilePath (required for batch mode): The path of CSV file for sms in Batch Mode
        */
        
        $ch = curl_init();
            
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_URL, URL.PATH_SEND_SMS);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($ch);
        if (!$result) {
            mysqli_query($db, "DELETE FROM phone WHERE accountID='$accountID'");
            $status['error'] = "SERVICE cannot be reached";
            curl_close($ch);
            echo json_encode($status);
            exit;
        }
        curl_close($ch);
    }

?>