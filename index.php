<?php 
    session_start();
    $path = "/index.php";
    $redir = false; //only redirect if it is in private access only page //INDEX is not
    include $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    include $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php'; 
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <link rel="icon" href="/images/favicon.ico" type="image/icon type">
    <script src="https://kit.fontawesome.com/17b6708155.js" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>$(document).ready(function(){AOS.init()})</script>
    <script src="/js/jquery.cookie.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        $(document).ready(function(){
            if (!!$.cookie('contact')){
                var saveOBJ = JSON.parse($.cookie("contact"))
                $("#name").val(saveOBJ.name)
                $("#surname").val(saveOBJ.surname)
                $("#login_email").val(saveOBJ.email)
                $("#message").val(saveOBJ.message)
            }

            $("form").submit(function(){
                event.preventDefault()
                grecaptcha.reset();
                grecaptcha.execute();
            })
            onSubmit = function(token){
                var dt = new Date();
                if($(".accepted").length){
                    console.log("TRIGGER")
                    $("p").remove(".accepted")
                }

                $("#conct_submit").empty()
                $("#conct_submit").html("<div class='lds-ring'><div></div><div></div><div></div><div></div></div>")
                $("#conct_submit").css("background-color", "transparent")
                $("#conct_submit").prop("disabled", "true")

                var info = {
                    conct_submit: "Submit",
                    name: $("#name").val(),
                    surname: $("#surname").val(),
                    email: $("#login_email").val(),
                    message: $("#message").val(),
                    'g-recaptcha-response': token
                }
                $.ajax({
                    type: "POST",
                    url: "/php/contact",
                    data: info,
                    dataType: "json",
                    encode: true
                })
                .done(function(data){
                    $("#conct_submit").empty()
                    $("#conct_submit").text("Submit (LOCKED)")

                    $("form").append("<p class='accepted'>" + data.message + "</p>")
                    if(data.status == "error"){
                        var infoJson = JSON.stringify(info);
                        dt.setHours( dt.getHours() + 1 );
                        document.cookie = "contact=" + infoJson + "; expires=" + dt.toUTCString();
                    }else{
                        $.removeCookie("contact");
                    }
                    $("#conct_submit").prop("disabled", true)
                    $("#conct_submit").css({"background-color":"gray", "cursor":"not-allowed"});
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    $("#conct_submit").empty()
                    $("#conct_submit").text("Submit (LOCKED)")

                    $("form").append("<p class='accepted'>" + "The server cannot be reached" + "</p>")
                    var infoJson = JSON.stringify(info);
                    dt.setHours( dt.getHours() + 1 );
                    document.cookie = "contact=" + infoJson + "; expires=" + dt.toUTCString();
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
    <link rel="stylesheet" href="css/style-revisioned.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Simone Bartoli Portfolio</title>
  </head>
  <body>
    <?php if(isset($_COOKIE['error_connection'])){ ?>
    <p class="warning">In this moment we have connection problems, you can continue using the main page but the BLOG is currently unavailable.<br>Try again in 30 seconds</p>
    <?php } ?>
    <nav>
        <label><a href="index.php">Simone Bartoli</a></label>
        <i class="fa fa-bars"></i>
        <ul>
            <div class="dropdown">
                <li class="homelink">Home<i class="fa fa-caret-down"></i></li>
                <div class="dropdown-content">
                    <li><a href="#about-myself">About Me</a></li>
                    <li><a href="#skills">My Skylls</a></li>
                    <li><a href="#qualification">My Degree</a></li>
                    <li><a href="#project">My Projects</a></li>
                    <li><a href="#experience">My Experience</a></li>
                </div>
            </div>
            <li><a href="#contact">Contact</a></li>
            <li><a href="blog.php">Blog</a></li>
            <?php 
                if(isset($_COOKIE['session_log'])){
                    echo '<li><a href="account.php">Your Account</a></li>';
                    echo '<li><a href="php/logout.php">Log Out</a></li>';
                }
                else{
                    echo '<li><a href="login.php">SignIn/SignUp</a></li>';
                }
            ?>
        </ul>
    </nav>
    <div class="nav-resp" style="display: none;"></div>

    <header id="home" class="fade-in">
        <h1>Hello I'm Simone Bartoli<br>Welcome to my Portfolio</h1>
    </header>
    <article id="about-myself" class="white" data-aos="fade-up">
        <div class="flex-wrap">
            <figure class="box1">
                <img src="images/myphoto1.png" alt="This is my photo">
            </figure>
            <section class="box2">
                <h2>About Myself</h2>
                <p>
                    Hello Everyone, my name is Simone Bartoli. Currently I'm a student at Queen Mary's University of London studying
                    Computer Science with Management. I'm very fond of IT, especially related to the financial sector. I think that in
                    the next years tecnology will become more important and it will be in our life more and more. People have various skills,
                    mine is programming. Do you want to know more? Scroll down and find all the information you need.                
                </p>
            </section>
        </div>
    </article>

    <article id="skills" class="dark">
        <h2>My Skills</h2>
        <div class="flex-wrap" data-aos="fade-up">
            <p class="box1">
                My major skills are in programming.<br> Through the university and also the various online documentation I have learned over time
                3 programming languages and 2 web development languages. I can work hard and finish any project on time. 
                Obviously with the study my knowledge is obviously increasing... so stay up to date.
            </p>
            <div class="box2">
                <li>
                    <h3>HTML/CSS</h3><span class="bar"><span id="html"></span></span>
                </li>
                <li>
                    <h3>Java</h3><span class="bar"><span id="java"></span></span>
                </li>
                <li>
                    <h3>PHP</h3><span class="bar"><span id="php"></span></span>
                </li>
                <li>
                    <h3>Javascript/React</h3><span class="bar"><span id="javascript"></span></span>
                </li>
                <li>
                    <h3>Python</h3><span class="bar"><span id="python"></span></span>
                </li>
            </div>
        </div>
    </article>

    <article id="qualification" class="white">
        <h2>Qualification</h2>
        <div class="flex-wrap" data-aos="fade-up">
            <section class="box1">
                <section class="box01">
                    <h2>High School Diploma</h2>
                    <p class="year">2014</p>
                    <p class="description">I got a high school certificate with a score of 100/100 with honors.</p>
                </section>
                <section class="box02">
                    <h2>College Diploma</h2>
                    <p class="year">2020</p>
                    <p class="description">I earned a college certificate with a score of 99/100 at the Leonardo da Vinci scientific high school in Florence.</p>
                </section>
            </section>
            <section class="box2">
                <section class="box01">
                    <h2>IELTS Certificate</h2>
                    <p class="year">2020</p>
                    <p class="description">I got an IELTS certificate with an average score of 6.5 / 9.</p>
                </section>
                <section class="box02">
                    <h2>University</h2>
                    <p class="year">2021</p>
                    <p class="description">I enrolled at Queen Mary University in London and am in the first year of IT Management.</p>
                </section>
            </section>
        </div>
    </article>

    <article id="project" class="dark">
        <h2>Projects</h2>
        <div class="flex-wrap" data-aos="fade-up">
            <section class="box1">
                <div>
                    <h3>The PointLess Game</h3>
                    <p> In November 2020 I developed a small game that is very inspired by the PointLessGame television quiz.
                        The game (still without real graphics) simulates a match between 4 people where everyone has to answer a series of questions right. 
                        The point is that the right answer can be more than one and therefore the player takes as many points as the originality and rarity of the same.<br> The game is developed in Java and was my first university project.</p>
                </div>
                <img src="images/PointLess.webp" alt="PointLess Demonstration">
            </section>
            <hr>
            <section class="box2">
                <img src="images/EstoX.webp" alt="EstoX Demonstration">
                <div>
                    <h3>EstoX</h3>
                    <p> In March 2021 I developed a Java project called EstoX. The program simulates a trading-type investment where a user, through a constant market simulation, can perform DEMO investments on many listed companies. 
                        The program saves the data and retrieves the value of the shares from the major equity sites. 
                        The program does not provide for any real win or loss but serves to simulate and train inexperienced people in this sector quickly and safely.</p>
                </div>
            </section>
        </div>
    </article>

    <article id="experience" class="white">
        <h2>Working Experience</h2>
        <div data-aos="fade-up">
            <div id="line"></div>
            <div class="container r1">
                <div class="item">
                    <h3>Ellegi</h3>
                    <p>In 2018 I worked for a year (alternating school and work) within the IT department of a company that produces hydraulic systems for large factories. I managed the server side and programmed small macros to help simplify daily actions. </p>
                    <i class="fas fa-square"></i>
                </div>
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="container r2">
                <div class="item">
                    <h3>Via di Scampo</h3>
                    <p>In the summer of 2019 and 2020 I worked as a full-time waiter in a fish restaurant in Forte de Marmi. </p>
                    <i class="fas fa-square"></i>
                </div>
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="container r1">
                <div class="item">
                    <h3>Xenia srl</h3>
                    <p>During 2020 I worked part-time in the IT side of an engineering company. I was in charge of managing the company's domains and mail servers + I created automation scripts for daily operations.</p>
                    <i class="fas fa-square"></i>
                </div>
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="container r2">
                <div class="item">
                    <h3>Queen Mary University</h3>
                    <p>I'm currently working as a demonstrator for IT first year modules in Queen Mary University of London</p>
                    <i class="fas fa-square"></i>
                </div>
                <i class="fas fa-file-contract"></i>
            </div>
            <i class="fas fa-angle-down"></i>
        </div>
    </article>


    <section id="contact" class="dark">
        <h2>Contact</h2>
        <div class="flex-wrap" data-aos="fade-up">
            <aside class="box1">
                <p>
                    If you have any questions or just want more information about me please do not hesitate
                    to contact me via the various systems below. Alternatively you can use the contact form on the side.
                </p>
                
                <div class="link-container">
                    <i class="far fa-envelope"></i>
                    <p><a href="mailto:Info@bartolisimone.com">Info@bartolisimone.com</a></p>
                    <i class="fas fa-phone"></i>
                    <p><a href="tel:07723093701">07723093701</a></p>
                    <i class="fab fa-facebook"></i>
                    <p><a href="https://www.facebook.com/simone.bartoli.353" target="_blank">Facebook</a></p>
                    <i class="fab fa-linkedin"></i>
                    <p><a href="https://www.linkedin.com/in/simone-bartoli-5025911b8/" target="_blank">Linkedin</a></p>
                </div>
                
            </aside>
            <div class="box2">
                <form method="POST" action="">
                    <fieldset>
                        <div class="container-wrap">
                            <div class="row">
                                <label for="name">Name</label>
                                <input type="text" maxlength="20" name="name" id="name" placeholder="Insert your Name" required>
                            </div>
                            <div class="row">
                                <label for="surname">Surname</label>
                                <input type="text" maxlength="20" name="surname" id="surname" placeholder="Insert your Surname" required>
                            </div>
                        </div>
                        <label for="login_email">Email Address</label>
                        <input type="email" maxlength="100" name="email" id="login_email" placeholder="Insert your Email" required>

                        <textarea name="message" id="message"  maxlength="500" rows="10" placeholder="Enter your message" required></textarea>
                        
                        <div class="g-recaptcha"
                            data-sitekey="6LfcaL8aAAAAAOLgTuQOzCNZYjm_FFQFg_wRR_rC"
                            data-callback="onSubmit"
                            data-size="invisible">
                        </div>
                        <button type="submit" value="Submit" name="conct_submit" id="conct_submit">Submit</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>

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