<?php
    session_start();
    $path = "/login.php";
    $redir = true; //only redirect if it is in private access only page

    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    require $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php'; 
    if($_SESSION['verified'] == true){
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
        <script src="https://kit.fontawesome.com/17b6708155.js" crossorigin="anonymous"></script>
        <link rel="icon" href="/images/favicon.ico" type="image/icon type">
        <script defer type="text/javascript" src="js/navToggle.js" charset="utf-8"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                load_countries()
                step1_phone();
                step2_phone();
                step3_phone();

                
                $(".phoneverify h2").click(function(){
                    let element = $(this).parent("div").find(".row")
                    if(element.css("display") == "block"){
                        element.css("display", "none")
                    }else{
                        element.css("display", "block")
                    }
                })

                function restore(){
                    $("#step1, #step2").css("display", "none");
                    $("#step3").css("display", "block");
                    var info = {
                        find_info: true
                    }
                    $.ajax({
                        type: "POST",
                        url: "/php/verification",
                        data: info,
                        dataType: "json",
                        encode: true
                    })
                    .done(function(data){
                        $("#label_date").text(data.date)
                        $("#label_number").text("+" + data.phoneN)
                        $("#step1, #step2").css("display", "none");
                        $("#step3").css("display", "block");
                    })
                }

                function step1_phone(){
                    $("#step1").submit(function(){
                        event.preventDefault()
                        var info = {
                            step1: true,
                            prefix: $("#prefix option:selected").val(),
                            phoneN: $("#phoneN").val()
                        }
                        $.ajax({
                            type: "POST",
                            url: "/php/verification",
                            data: info,
                            dataType: "json",
                            encode: true
                        })
                        .done(function(data){
                            if(typeof data.error !== 'undefined'){
                                alert(data.error)
                            }else{
                                $("#step2 .important span").text("+" + data.success)
                                $("#step1").css("display", "none")
                                $("#step2").css("display", "block")
                            }
                        })
                    })
                    
                }
                function step2_phone(){
                    $("#goback").click(function(){
                        $("#step2").css("display", "none")
                        $("#step1").css("display", "block")
                    })
                    $("#sendmessage").click(function(){
                        if(confirm("Are you sure you want continue?")){
                            var info = {
                                step2: true
                            }
                            $.ajax({
                                type: "POST",
                                url: "/php/verification",
                                data: info,
                                dataType: "json",
                                encode: true
                            })
                            .done(function(data){
                                if(typeof data.error !== 'undefined'){
                                    alert(data.error)
                                }else{
                                    restore()
                                }
                            })
                        }
                    })
                }

                function step3_phone(){
                    $("#step3").submit(function(){
                        event.preventDefault()
                        var info = {
                            step3: true,
                            code: $("#OTP").val()
                        }
                        $.ajax({
                            type: "POST",
                            url: "/php/verification",
                            data: info,
                            dataType: "json",
                            encode: true
                        })
                        .done(function(data){
                            if(typeof data.error !== 'undefined'){
                                alert(data.error)
                                if(typeof data.reload !== 'undefined'){
                                    load_countries();
                                }
                            }else{
                                alert(data.success)
                                window.location.replace("/account.php");
                            }
                        })
                    })
                }

                function load_countries(){
                    var info = {
                        find_countries: true
                    }
                    $.ajax({
                        type: "POST",
                        url: "/php/verification",
                        data: info,
                        dataType: "json",
                        encode: true
                    })
                    .done(function(data){
                        if(typeof data.banned !== 'undefined'){
                            $("#step1, #step2, #step3").css("display", "none")
                            $(".number").append("<p class='ban'>"+ data.reason +"</p>")
                        }else if(typeof data.error !== 'undefined'){
                            restore()
                            return
                        }

                        countries = data
                        for(let i=0; i<countries.length; i++){
                            $("#prefix").append("<option value='"+ countries[i].prefix +"'>+" + countries[i].prefix + "</option>")
                        }
                    })
                }

            })
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/effect.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/verification.css">
        <link rel="stylesheet" href="css/all.css">
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


        <div class="content">
            <div class="intro">
                <h1>Account Verification</h1>
                <hr>
                <p>For security reason (avoid spam and malicious users) we need to verify your account before
                    allowing you to post. To do so you can choose either to: 
                </p>
                <ul>
                    <li>Verify Your Phone Number (quicker & RECOMMENDED)</li>
                    <li>Verify your Paypal Account (Still in Development)</li>
                </ul>
            </div>

            <div class="phoneverify">
                <h2>Phone Verification <i class="far fa-chevron-down" style="margin-left: 0.5em;"></i></h2>
                <div class="row" style="display: none;">
                    <p>You can only use this verification method if you have a phone number issued in the UK or in one of the 27 countries of the European Union. 
                    Otherwise you can only verify your account through PayPal.</p>
                    <p>Once you have entered your phone number in the field below, 
                    a text message will be sent with a verification code which will be used to verify the account.</p>
                    <hr>
                    <div class="number">
                        <form action="POST" id="step1">
                            <select name="prefix" id="prefix" required>
                                <option value="" selected>Prefix</option>
                            </select>
                            <input type="text" id="phoneN" maxlength="15" minlength="7" placeholder="Insert your phone number" required><br>
                            <button>Continue</button>
                        </form>
                        <div id="step2" style="display: none;">
                            <p class="important">Is this your Number? <br><span></span></p>
                            <p>ONLY 1 MESSAGE WILL BE SENT. If you click "Send Message" you will not be able more to change your number.</p>
                            <button id="goback">Go Back</button>
                            <button id="sendmessage">Send Message</button>
                        </div>
                        <form action="POST" id="step3" style="display: none;">
                            <p>Please insert the code you received in the field below. 
                            You have 3 attemps maximum and only one hour from the sending of the message.</p>
                            <p>The message has been sent on <label id="label_date"></label> to <label id="label_number"></label></p>
                            <div>
                                <input type="text" name="OTP" id="OTP" maxlength="6" minlength="6" placeholder="Insert the code...">
                                <button>Verify your Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="paypalverify">
                <h2>Paypal Account Verification <i class="far fa-chevron-down" style="margin-left: 0.5em;"></i></h2>
                <div style="display:none;" class="row">
                </div>
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