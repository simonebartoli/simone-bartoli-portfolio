<?php
    require $_SERVER['DOCUMENT_ROOT']. '/php/init_connection.php'; 
    require $_SERVER['DOCUMENT_ROOT']. '/php/sendemail.php'; 
    require $_SERVER['DOCUMENT_ROOT']. '/php/filter_form.php';   
    date_default_timezone_set('Europe/London');


    if(isset($_POST['conct_submit'])){
        $info = array();
        
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $surname = mysqli_real_escape_string($db, $_POST['surname']);
        $message = mysqli_real_escape_string($db, $_POST['message']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $datetime = date("Y-m-d G:i:s");

        checkSpam();

        if (empty($name)) {$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }
        elseif(strlen($name)>20){$info = array('status'=>"error", 'message'=>"Your name is too long");}
        else{if(!nameval($name)){$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }}

        if (empty($surname)) {$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }
        elseif(strlen($surname)>20){$info = array('status'=>"error", 'message'=>"Your surname is too long");}
        else{if(!nameval($surname)){$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }}

        if (empty($email)) {$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }
        elseif(strlen($email)>100){$info = array('status'=>"error", 'message'=>"Your mail is too long");}
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }
        else{
          $data = verify($email);
          if(!isset($data['success'])){
            if(!$data['format_valid'] || !$data['mx_found'] || $data['disposable']){
                $info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); 
              }
          }
        }

        if (empty($message)) {$info = array('status'=>"error", 'message'=>"Your Data is not CORRECT"); }
        elseif(strlen($message)>500){$info = array('status'=>"error", 'message'=>"Your message is too long");}

        if(!isset($_POST['g-recaptcha-response'])){
            $info = array('status'=>"error", 'message'=>"Captcha not completed"); 
        }else{
            $captcha = $_POST['g-recaptcha-response'];
            $secretKey = "6LfcaL8aAAAAAHRcIImi3eXyFYUx8G1oBwACwLX8";
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
            if(!$responseKeys["success"]){
                $info = array('status'=>"error", 'message'=>"Captcha ERROR"); 
            }
        }



        if(count($info)==0){
            $name = ucwords(strtolower($name));
            $surname = ucwords(strtolower($surname));
            $email = strtolower($email);


            $query = "INSERT INTO contact (name, surname, message, email, date) VALUES('$name', '$surname', '$message', '$email', '$datetime')";
            if(!mysqli_query($db, $query)){
                $info = array('status'=>"error", 'message'=>"We can't connect to our database");
                echo json_encode($result);
                exit();
            }


            $query = "SELECT id FROM contact WHERE email='$email' AND date='$datetime'";
            $result = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($result);
            $result = strval($row["id"]); //convert object to associative array 
            conctForm($name, $surname, $_POST['message'], $email, $result);
            //telegram();
            if($info['status']=="success"){
                antiSpam();
            }
        }   


        echo json_encode($info);
    }
    
    function telegram(){
        global $name, $surname, $email, $message, $datetime;
        $botToken="1851460265:AAEqWJZA1oXt2GTf46pvobJzJbPtbJj-fEs";

        $website="https://api.telegram.org/bot".$botToken;
        $chatId="1702433484";  //** ===>>>NOTE: this chatId MUST be the chat_id of a person, NOT another bot chatId !!!**
        $reply = "<b>New Contact Request</b>\n--------------------------------------------\n<b>".$name.' '.$surname.
        "</b>\n".$email."\n\n".$message."\n--------------------------------------------\n<b>".$datetime.'</b>';
        $params=[
            'chat_id'=>$chatId, 
            'text'=> $reply,
            'parse_mode' => 'HTML'
        ];
        $ch = curl_init($website . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    function checkSpam(){
        global $db;
        global $info;
        date_default_timezone_set('Europe/London');

        $ip = retrieveIP();
        $data = VPN($ip);
        if($data[$ip]['proxy']=="yes"){
            $info = array('status'=>"error", 'message'=>"You cannot send message using a VPN.<br>Reload, your data are saved");
            echo json_encode($info);
            exit();
        }

        $query = "SELECT * FROM antispam_contact WHERE IP='$ip'";
        $result = mysqli_query($db, $query);
        if(mysqli_num_rows($result)>0){
            if(strtotime(mysqli_fetch_assoc($result)['date'])<time()){
                mysqli_query($db, "DELETE FROM antispam_contact WHERE IP='$ip'");
            }
            else{
                $info = array('status'=>"error", 'message'=>"You cannot send more than 1 message in a hour");
                echo json_encode($info);
                exit();
            }
        }
    }

    function antiSpam(){
        global $db;
        date_default_timezone_set('Europe/London');

        $ip = retrieveIP();
        $time = date("Y:m:d H:i:s", strtotime("1 hour", time()));
        mysqli_query($db, "INSERT INTO antispam_contact (IP, date) VALUES ('$ip', '$time')");
    }

    function retrieveIP(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
?>