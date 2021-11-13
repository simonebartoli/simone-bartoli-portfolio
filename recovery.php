<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    if(isset($_COOKIE['session_log']) || isset($_SESSION['accountID'])){
        redir("/account.php");
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@200&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link rel="icon" href="/images/favicon.ico" type="image/icon type">

        <script src="https://kit.fontawesome.com/17b6708155.js" crossorigin="anonymous"></script>
        <script defer type="text/javascript" src="js/navToggle.js" charset="utf-8"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $("form").submit(function(){
                    if($(".success").length || $(".error").length){
                        $("p").remove(".accepted")
                        $("p").remove(".error")
                    }
                    var info = {
                        recovery_submit: "Submit",
                        email: $("#email").val(),
                    }
                    $.ajax({
                        type: "POST",
                        url: "/php/recovery_pass",
                        data: info,
                        dataType: "json",
                        encode: true
                    })

                    .done(function(data){
                        if(data.status == "success"){
                            $(".content").append("<p class='success'>" + data.message + "</p>")
                            $("#recovery_submit").prop("disabled", true)
                            $("#recovery_submit").val("Submit (LOCKED)")
                            $("#recovery_submit").css({"background-color":"gray", "cursor":"not-allowed"});
                        }else{
                            $(".content").append("<p class='error'>" + data.message + "</p>")
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown){
                        $(".content").append("<p class='error'>" + "The server cannot be reached" + "</p>")
                        setTimeout(function(){window.location.replace("/index.php")}, 2500);
                    })
                    
                    
                    event.preventDefault()
                })
                
            })
        </script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/effect.css">
        <link rel="stylesheet" href="css/recovery.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/footer.css">
        <title>Simone Bartoli Portfolio</title>
    </head>
    <body>
    <nav>
            <label><a href="index.php">Simone Bartoli</a></label>
            <i class="fa fa-bars"></i>
            <ul>
                <div class="dropdown">
                    <li class="homelink">Home<i class="fa fa-caret-down"></i></li>
                    <div class="dropdown-content">
                    <li><a href="/index.php#about-myself">About Me</a></li>
                        <li><a href="/index.php#skills">My Skylls</a></li>
                        <li><a href="/index.php#qualification">My Degree</a></li>
                        <li><a href="/index.php#project">My Projects</a></li>
                        <li><a href="/index.php#experience">My Experience</a></li>
                    </div>
                </div>
                <li><a href="/index.php#contact">Contact</a></li>
                <li><a href="/blog.php">Blog</a></li>
                <?php 
                    if(isset($_COOKIE['session_log'])){
                        echo '<li><a href="/account.php">Your Account</a></li>';
                        echo '<li><a href="/php/logout.php">Log Out</a></li>';
                    }
                    else{
                        echo '<li><a href="/login.php">SignIn/SignUp</a></li>';
                    }
                ?>
            </ul>
        </nav>
        <div class="nav-resp" style="display: none;"></div>

        <div>
            <div class="content">
                <h2>Recovery Password</h2>
                <p>Enter the email with which you registered</p>
                <form action="" method="POST">
                    <input type="email" name="email" id="email" placeholder="Insert your email..." required>
                    <input type="submit" name="recovery_submit" id="recovery_submit" value="Submit">
                </form>
            </div>
        </div>
        <footer>
            <div class="flex-wrap">
                <p>Structured & Designed by <span>Simone Bartoli</span></p>
                <p class="no-mobile"> | </p>
                <p>Every right reserved&reg;</p>
            </div>
            <p class="small">The site was structured, designed and published online by Simone Bartoli. All rights relating to the contents, functionality and code used are reserved to Simone Bartoli.</p>
        </footer>
    </body>
</html>