<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 

    $password = "";
    $confirm_password = "";
    $errors = array();

    if(isset($_POST['pass_submit']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if(empty($password)){array_push($errors, "- Password cannot be empty");}
        elseif(strlen($password)<6){array_push($errors, "- Your password needs to be at least 6 chars");}
        if(empty($confirm_password)){array_push($errors, "- Confirm Password cannot be empty");}

        if(count($errors)==0){
            if($password == $confirm_password){
                $password = password_hash($confirm_password, PASSWORD_DEFAULT);
                $email = $_SESSION['email'];
                $query = "UPDATE users SET password='$password' WHERE email = '$email'";
                mysqli_query($db, $query);
                echo json_encode(true);
                exit;
            }
            else{
                array_push($errors, "- The 2 Passwords don't match");
            }
        }
        echo json_encode($errors);
    }
?>