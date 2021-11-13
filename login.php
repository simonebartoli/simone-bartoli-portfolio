<?php 
    session_start();
    require $_SERVER['DOCUMENT_ROOT']. "/php/redirect.php";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="/images/favicon.ico" type="image/icon type">

    <script defer type="text/javascript" src="/js/validation_login.js" charset="utf-8"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        $(document).ready(function(){
            $("form").submit(function(){
                event.preventDefault()
                grecaptcha.reset();
                grecaptcha.execute();
            })
            onSubmit = function(token){
                $("#log_submit").empty()
                $("#log_submit").html("<div class='lds-ring'><div></div><div></div><div></div><div></div></div>")
                $("#log_submit").css("background-color", "transparent")
                $("#log_submit").prop("disabled", true)

                if($(".error").length){
                    $(".error").remove();
                }
                var info = {
                    log_submit: "LogIn",
                    email: $("#email").val(),
                    password: $("#password").val(),
                    'g-recaptcha-response': token
                }

                $.ajax({
                    type: "POST",
                    url: "/php/server",
                    data: info,
                    dataType: "json",
                    encode: true
                })

                .done(function(data){
                    $("#log_submit").empty()
                    $("#log_submit").removeAttr('style')
                    $("#log_submit").prop("disabled", false)
                    $("#log_submit").text("LogIn")
                    if(data.length>0 && data != true){
                        $("#login").append("<ul class='error'>");
                        for(let i=0; i<data.length; i++){
                            $(".error").append("<li>" + data[i] + "</li>");                        
                        }
                        $("#login").append("</ul>");
                    }
                    else{
                        window.location.replace("/account.php");
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    $("#log_submit").empty()
                    $("#log_submit").removeAttr('style')
                    $("#log_submit").prop("disabled", false)
                    $("#log_submit").text("LogIn")
                    $("#login").append("<ul class='error'><li>" + "The server cannot be reached" + "</li></ul>")
                })
            }
            
        })
    </script>
    <script defer type="text/javascript" src="js/navToggle.js" charset="utf-8"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/effect.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Blog LogIn</title>
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

    
    <div class="content">
      <form method="post" action="">
          <fieldset id="login">
            <h2>Log-In</h2>
            <label for="login_email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Insert your Email" required>

            <label for="pass_email">Password</label>
            <input type="password" name="password" id="password" placeholder="Insert your Password" required>
            <div class="g-recaptcha"
                data-sitekey="6LfcaL8aAAAAAOLgTuQOzCNZYjm_FFQFg_wRR_rC"
                data-callback="onSubmit"
                data-size="invisible">
            </div>
            <button type="submit" name="log_submit" value="LogIn" id="log_submit">LogIn</button>
          </fieldset>
          <fieldset id="recovery">
            <h2>Don't you remember the Password?</h2>
            <a href="/recovery.php">Click here to recover it</a>
          </fieldset>
          <fieldset id="signup">
            <h2>Still not registered?</h2>
            <label for="register">Click the button to register</label>
            <button type="button" id="register" onclick="location.href='register.php';">Register</button>
          </fieldset>
      </form>
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