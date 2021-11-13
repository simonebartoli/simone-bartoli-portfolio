<?php
    session_start();

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/vendor/autoload.php';
    $mail = new PHPMailer(true);

    //Instantiation and passing `true` enables exceptions
    $mail->CharSet = 'utf-8';
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'host';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'username';               //SMTP username
    $mail->Password   = 'password';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->setFrom('email', 'Simone Bartoli');
    $mail->SMTPDebug = 0;

    function conctForm($name , $surname, $message, $email, $result){
        global $mail;
        global $info;
        //Recipients
        try{
            selfSend($name , $surname, $message, $email, $result);
            $mail->addAddress($email);     //Add a recipient
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Response Email N. '. str_pad($result, 5, '0', STR_PAD_LEFT);
            $mail->Body    = bodyConctEmail($name, $surname, $message, $result);
            $mail->send();
            $info = array('status'=>"success", 'message'=>"Your Request has been received");
        }catch(Exception $e){
            $info = array('status'=>"error", 'message'=>"Your Request has been received but you will receive no email");//
        }
        
    }

    function regForm($name , $surname, $email){
        global $mail;
        try {            
            //Recipients
            $mail->addAddress($email);     //Add a recipient
    
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Registration Confirmation Email';
            $mail->Body    = bodyRegEmail($name, $surname);
    
            $mail->send();

        } catch (Exception $e) {
            //ERROR IN SENDING EMAIL - NO NEED TO HANDLE IT
        }
        
    }

    function recoveryForm($email, $link, $name, $surname){
        global $mail;
        global $info;
        try{
            $mail->addAddress($email);     //Add a recipient
    
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Recovery Password';
            $mail->Body    = bodyRecoveryEmail($link, $name, $surname);
            $mail->send();
            $info = array('status'=>"success");//
        }catch(Exception $e){
            $info = array('status'=>"error", 'message'=>"In this moment we can't send recovery email");//
        }
        
    }


    function selfSend($name , $surname, $message, $email, $result){
        global $mail;
        try {            
            //Recipients
            $mail->addAddress("info@bartolisimone.com");     //Add a recipient
    
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'New Contact Request';
            $mail->Body    = bodyConctEmailAdmin($name , $surname, $message, $email, $result);
    
            $mail->send();
            $mail->ClearAllRecipients();
        } catch (Exception $e) {
            //ERROR IN SENDING EMAIL - NO NEED TO HANDLE IT
        }
    }
    function bodyConctEmail($name, $surname, $message, $result){
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/mailForm/conct_email.php';
        $text = ob_get_contents();
        ob_clean();
        return $text;
    }
    function bodyConctEmailAdmin($name, $surname, $message, $email, $result){
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/mailForm/conct_email_admin.php';
        $text = ob_get_contents();
        ob_clean();
        return $text;
    }

    function bodyRegEmail($name, $surname){
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/mailForm/reg_email.php';
        $text = ob_get_contents();
        ob_clean();
        return $text;
    }

    function bodyRecoveryEmail($link, $name, $surname){
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/mailForm/recovery_email.php';
        $text = ob_get_contents();
        ob_clean();
        return $text;
    }
?>