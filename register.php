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
    <link rel="icon" href="/images/favicon.ico" type="image/icon type">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script defer type="text/javascript" src="js/navToggle.js" charset="utf-8"></script>

    <script>
        $(document).ready(function(){
            $("form").submit(function(){
                event.preventDefault()
                grecaptcha.reset();
                grecaptcha.execute();
            })

            onSubmit = function(token){
                $("#submit").empty()
                $("#submit").html("<div class='lds-ring'><div></div><div></div><div></div><div></div></div>")
                $("#submit").css("background-color", "transparent")
                $("#submit").prop("disabled", true)

                if($(".error").length){
                    $(".error").remove();
                }
                var info = {
                    reg_submit: "Submit",
                    name: $("#name").val(),
                    surname: $("#surname").val(),
                    dob: $("#dob").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    confirm: $("#confirm").val(),
                    terms: $("#terms").val(),
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
                    $("#submit").empty()
                    $("#submit").removeAttr('style')
                    $("#submit").prop("disabled", false)
                    $("#submit").text("Submit")
                    if(data.length>0 && data != true){
                        $("fieldset").append("<ul class='error'>");
                        for(let i=0; i<data.length; i++){
                            $(".error").append("<li>" + data[i] + "</li>");                        
                        }
                        $("fieldset").append("</ul>");
                    }
                    else{
                        setTimeout(function(){window.location.replace("/account.php")}, 1000);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    $("#submit").empty()
                    $("#submit").removeAttr('style')
                    $("#submit").prop("disabled", false)
                    $("#submit").text("Submit")
                    $("fieldset").append("<ul class='error'><li>" + "The server cannot be reached" + "</li></ul>")
                })
            }
            
        })
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/effect.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Blog SignUp</title>
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
    
    <div class="wrapper">
        <form action="" method="POST">
            <fieldset>
                <h2>Registration</h2>
                <div class="row">
                    <div class="column">
                        <label for="name">Name:</label> <br>
                        <input type="text" name="name" id="name" placeholder="Insert your Name" required>
                    </div>
                    <div class="column">
                        <label for="surname">Surname:</label> <br>
                        <input type="text" name="surname" id="surname" placeholder="Insert your Surname" required>
                    </div>
                </div>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" max="<?php echo date("Y-m-d", strtotime('-12 year', time())); ?>" min="<?php echo date("Y-m-d", strtotime('-115 year', time())); ?>" required>


                <label for="login_email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Insert your Email" required>
                
                <div class="row">
                    <div class="column">
                        <label for="pass_email">Password</label> <br>
                        <input type="password" name="password" id="password" placeholder="Insert your Password" required>
                    </div>
                    <div class="column">
                        <label for="pass_email">Confirm Password</label> <br>
                        <input type="password" name="confirm" id="confirm" placeholder="Insert your Password" required>
                    </div>
                </div>
                <div class="row terms">
                    <input type="checkbox" id="terms" name="terms" value="accepted" required>
                    <label for="terms">I Accept the <a href="/terms&condition.php" target="_blank">Terms&Conditions</a></label><br>
                </div>
                <div class="g-recaptcha"
                    data-sitekey="6LfcaL8aAAAAAOLgTuQOzCNZYjm_FFQFg_wRR_rC"
                    data-callback="onSubmit"
                    data-size="invisible">
                </div>
                <button id="submit" type="submit" name="reg_submit" value="Submit">Submit</button>
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