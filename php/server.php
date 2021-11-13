<?php
    session_start();
    $name = "";
    $surname = "";
    $dob = "";
    $email = "";
    $errors = array(); 

    require $_SERVER['DOCUMENT_ROOT']. '/php/init_connection.php';     
    require $_SERVER['DOCUMENT_ROOT']. '/php/sendemail.php';  
    require $_SERVER['DOCUMENT_ROOT']. '/php/filter_form.php';   
    date_default_timezone_set('Europe/London');


    if(isset($_POST['reg_submit'])){
        $max_date = date("Y-m-d", strtotime('-12 year', time()));
        $min_date = date("Y-m-d", strtotime('-115 year', time()));


        $name = mysqli_real_escape_string($db, $_POST['name']);
        $surname = mysqli_real_escape_string($db, $_POST['surname']);
        $dob = mysqli_real_escape_string($db, $_POST['dob']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password = mysqli_real_escape_string($db, $_POST['password']);
        $confirm = mysqli_real_escape_string($db, $_POST['confirm']);
        $terms = mysqli_real_escape_string($db, $_POST['terms']);

        if (empty($name)) { array_push($errors, "- Name is required"); }
        else{if(!nameval($name)){array_push($errors, "- Name is invalid");}}

        if (empty($surname)) { array_push($errors, "- Surname is required"); }
        else{if(!nameval($surname)){array_push($errors, "- Surname is invalid");}}

        if (empty($email)) { array_push($errors, "- Email is required"); }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){array_push($errors, "- This email is not valid");}
        else{
            $data = verify($email);
            if(!isset($data['success'])){
                if(!$data['format_valid'] || !$data['mx_found'] || $data['disposable']){
                    array_push($errors, "- This email is not valid");
                }
            }
        }

        if (empty($password)) { array_push($errors, "- Password is required"); }
        elseif(strlen($password)<6){array_push($errors, "- Your password needs to be at least 6 chars");}

        if(empty($dob)){array_push($errors, "- Date of Birth is Required");}
        elseif($dob>$max_date){array_push($errors, "- You need to be at least 12");}
        elseif($dob<$min_date){array_push($errors, "- You cannot be more than 115 years old");}
        if(empty($terms) || $terms != "accepted"){
            array_push($errors, "- To register you need to accept the Terms&Conditions");
        }
        if ($password != $confirm) {
            array_push($errors, "- The two passwords do not match");
        }
        if(!isset($_POST['g-recaptcha-response'])){
            array_push($errors, "- Captcha not completed");
        }else{
            $captcha = $_POST['g-recaptcha-response'];
            $secretKey = "6LfcaL8aAAAAAHRcIImi3eXyFYUx8G1oBwACwLX8";
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
            if(!$responseKeys["success"]){
                array_push($errors, "- Captcha ERROR");
            }
        }
        $email = strtolower($email);
        $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        if ($user) { // if user exists
            if ($user['email'] === $email) {
                array_push($errors, "- Email already exists");
            }
        }

        if(count($errors)==0){
            $name = ucfirst(strtolower($name));
            $surname = ucfirst(strtolower($surname));
            $email = strtolower($email);
            $password = password_hash($password, PASSWORD_DEFAULT);
            $role = "normal";

            $query = "INSERT INTO users (name, surname, dob, email, password, role) VALUES('$name', '$surname', '$dob', '$email', '$password', '$role')";
            mysqli_query($db, $query);

            $query = "SELECT accountID FROM users WHERE email = '$email'";
            $result = mysqli_fetch_assoc(mysqli_query($db, $query));

            regForm($name, $surname, $email);
            setSession($result['accountID'], $email, $name, $surname, $dob, $role, false);

            $accountID = $_SESSION['accountID'];
            $date = date("Y-m-d H:i:s");
            $query = "INSERT INTO terms (accountID, date) VALUES ('$accountID', '$date')";
            mysqli_query($db, $query);

            $query = "INSERT INTO access_token (accountID) VALUES ('$accountID')";
            mysqli_query($db, $query);

            $secret = password_hash($email."Ciao@123456", PASSWORD_DEFAULT);
            setcookie("session_log", $secret, time() + 3600, "/", "", "" ,true); 
            echo json_encode(true);
        }
        else{
            echo json_encode($errors);
        }

    }

    if(isset($_POST['log_submit'])){
        $email = strtolower(mysqli_real_escape_string($db, $_POST['email']));
        $password = mysqli_real_escape_string($db, $_POST['password']);
        if (empty($email)) {
            array_push($errors, "- Email is required");
        }
        if (empty($password)) {
            array_push($errors, "- Password is required");
        }
        if(!isset($_POST['g-recaptcha-response'])){
            array_push($errors, "- Captcha not completed");
        }else{
            $captcha = $_POST['g-recaptcha-response'];
            $secretKey = "6LfcaL8aAAAAAHRcIImi3eXyFYUx8G1oBwACwLX8";
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseKeys = json_decode($response, true);
            if(!$responseKeys["success"]){
                array_push($errors, "- Captcha ERROR");
            }
        }

        if (count($errors) == 0) {
        $query = "SELECT users.*, phone.verified FROM users LEFT JOIN phone ON users.accountID=phone.accountID WHERE users.email='$email'";
        $result = mysqli_fetch_assoc(mysqli_query($db, $query));
        if (!empty($result)) {
            if(password_verify($password, $result['password']) && $email == $result['email']){
                $access = true;
                if($result['banned'] == true){
                    array_push($errors, "- Your account has been banned");
                    $access = false;
                }elseif($result['limited'] == true){
                    $accountID = $result['accountID'];
    
                    $query = "SELECT * FROM limiteduser WHERE accountID='$accountID' ORDER BY reportID DESC LIMIT 1";
                    $limit = mysqli_fetch_assoc(mysqli_query($db, $query));
                    if(time()<strtotime($limit['expirydate'])){
                        $access = false;
                        array_push($errors, "- Your account has been suspended until ". $limit['expirydate']);
                    }else{
                        $query = "UPDATE users SET limited=0 WHERE accountID='$accountID'";
                        mysqli_query($db, $query);
                    }
                }
                if($access){
                    setSession($result['accountID'], $result["email"], $result['name'], $result['surname'],  $result['dob'], $result['role'], $result['verified']);

                    $secret = password_hash($email."Ciao@123456", PASSWORD_DEFAULT);
                    setcookie("session_log", $secret, time() + 3600, "/", "", "" , true); 
                    echo json_encode(true);
                }else{
                    echo json_encode($errors);
                }
            }
            else{
                array_push($errors, "- The email/password don't match with any existing account");
                echo json_encode($errors);
            }
            
        }else {
            array_push($errors, "- The email/password don't match with any existing account");
            echo json_encode($errors);
        }
        }else{
            array_push($errors, "- The email/password don't match with any existing account");
            echo json_encode($errors);
        }
    }

    function setSession($id, $email, $name, $surname, $dob, $role, $verified){
        $_SESSION['accountID'] = $id;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['dob'] = $dob;
        $_SESSION['role'] = $role;
        $_SESSION['verified'] = $verified;
        session_regenerate_id();
    }
    
?>