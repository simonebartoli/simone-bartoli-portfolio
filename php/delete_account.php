<?php
    session_start();
    $password = "";
    $errors_delete = array();

    if(isset($_POST['delete_submit'])){
        $password = $_POST['password'];
        if(empty($password)){array_push($errors_delete, "- Password cannot be empty");}
        else{
            $email = $_SESSION['email'];
            $query = "SELECT password FROM users WHERE email='$email'";
            $result = mysqli_query($db, $query);
            
            $result = mysqli_fetch_assoc($result);
            if(password_verify($password, $result['password'])){
                $query = "DELETE FROM users WHERE email='$email'";
                mysqli_query($db, $query);

                setcookie("session_log", false, time() - 60, "/");
                session_unset();
                session_destroy();
                
                redir('/index.php');
            }
            else{
                array_push($errors_delete, "- Password doesn't match");
            }
        }
    }
?>