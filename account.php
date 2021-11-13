<?php
    session_start();
    $path = "/login.php";
    $redir = true; //only redirect if it is in private access only page

    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    require $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php'; 
    require $_SERVER['DOCUMENT_ROOT']. '/php/delete_account.php';
    require $_SERVER['DOCUMENT_ROOT']. '/php/find_action.php';
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script defer type="text/javascript" src="js/navToggle.js" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                <?php if(!$_SESSION['verified']){ ?>
                $("hr:first").after("<div class='verification'><p>CLICK TO VERIFY YOUR ACCOUNT</p></div>")
                $(".verification").click(function(){
                    window.location.href = "/verification.php"
                })
                <?php }else{ ?>
                $("hr:first").after("<div class='verification yes'><p>YOUR ACCOUNT HAS BEEN VERIFIED</p></div>")
                $(".verification").hover(function(){
                    $(this).css("cursor", "initial");
                })
                <?php } ?>
                $("#change").submit(function(){
                    $(".success").remove()
                    $(".errors").remove()

                    var info = {
                        pass_submit: true,
                        password: $("#password").val(),
                        confirm_password: $("#confirm").val()
                    }

                    $.ajax({
                        type: "POST",
                        url: "/php/password_change",
                        data: info,
                        dataType: "json",
                        encode: true
                    })

                    .done(function(data){
                        if(data == true){
                            $("#change").append("<p class='success'>Your Password has changed</p>")
                        }else{
                            $("#change").append("<ul class='errors'>");
                            for(let i=0; i<data.length; i++){
                                $("#change ul").append("<li>" + data[i] + "</li>");
                            }
                            $("#change").append("</ul>");
                        }
                        $("#password").val("")
                        $("#confirm").val("")
                    })
                    .fail(function(jqXHR, textStatus, errorThrown){
                        $("#change").append("<p class='success'>We can't connect to the server</p>")
                    })
                    event.preventDefault()
                })
            })
        </script>

        <script type="text/javascript" charset="utf-8">
            window.onload = function(){
                var button = document.getElementsByClassName("number")[0].querySelectorAll("button");
                var box = document.querySelectorAll(".published");
                box.forEach(item => {
                    item.addEventListener("click", function(){
                        var postid = item.getElementsByClassName("postID")[0].innerHTML;
                        var replyid = item.getElementsByClassName("replyID")[0];

                        if(typeof(replyid) != 'undefined' && replyid != null){
                            var link = "/view-post.php?" + "postid=" + postid;
                            link = link + "&reply=" + replyid.innerHTML + "#selected";
                        }
                        else{
                            var link = "/view-post.php?" + "postid=" + postid;
                        }
                        window.open(link);
                    })
                })
                button.forEach(item =>{
                    item.addEventListener("click", function(){
                        var number = item.innerHTML - 1;
                        var link = "/account.php?page=" + number;
                        window.open(link, "_SELF");
                    })
                })
            }
        </script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/effect.css">
        <link rel="stylesheet" href="css/account.css">
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

        <div id="account">
            <?php 
                echo "<h1>Welcome Back <span>".$_SESSION['name']. " ". $_SESSION['surname']. "</span></h1>";
            ?>
            <hr>
            <?php if($_SESSION['role']=="admin"){ ?>
                <a class="admin" href="/admin_centre.php" target="_BLANK">
                    <h2>ADMIN CENTRE</h2>
                </a>
            <?php } ?>
            <div class="flex-wrap">
                <div class="box1">
                    <div class="content">
                        <h2>Personal Information</h2>
                        <ul>
                        <?php
                            echo "<li>Name:</li> <span>".$_SESSION['name']. "</span>"; 
                            echo "<li>Surname:</li> <span>".$_SESSION['surname']. "</span>"; 
                            echo "<li>Date of Birth:</li> <span>".$_SESSION['dob']. "</span>"; 
                            echo "<li>Email:</li> <span>".$_SESSION['email']. "</span>"; 
                        ?>
                        </ul>
                    </div>
                    <div class="content">
                        <h2>Change Password</h2>
                        <form action="" method="POST" id="change">
                            <fieldset>
                                <input type="password" name="password" id="password" placeholder="Insert your new password" required>
                                <input type="password" name="confirm" id="confirm" placeholder="Confirm your new password" required>
                            </fieldset>
                            <fieldset>
                                <input type="submit" value="Submit" name="pass_submit">
                            </fieldset>
                            <?php
                                if(isset($_SESSION['pass_changed'])){
                                    if($_SESSION['pass_changed'] == true){
                                        echo "<p class='success'>Your Password has changed</p>";
                                        unset($_SESSION['pass_changed']);
                                    }
                                    else{
                                        echo "<ul class='errors'>";
                                            for($i=0; $i<count($errors); $i++){
                                                echo "<li>". $errors[$i]. "</li>";
                                            }
                                        echo "</ul>";
                                        unset($_SESSION['pass_changed']);
                                        unset($errors);
                                    }
                                }
                            ?>
                        </form>
                    </div>
                
                </div>
                <div class="spacer"></div>
                <div class="box2 content">
                    <h2>Post Published</h2>
                    <section class="show-action <?php if(!isset($post_entry)){echo 'changeheigth';}?>">
                        <?php if(isset($post_entry)){ for($i=4*$page_number; $i<4*$page_number+4 && $i<count($post_entry); $i++){ ?>
                        <article class="published">
                            <label class="title-post"><?php if($post_entry[$i]['type']=="post"){echo $post_entry[$i]['title'];}else{echo $post_entry[$i]['message'];}?></label><br>
                            <label class="type"><?php echo strtoupper($post_entry[$i]['type']) ?> - </label>
                            <label style="display: none;" class="postID"><?php echo $post_entry[$i]['postID'] ?></label>
                            <?php if(isset($post_entry[$i]['replyID'])){?>
                            <label style="display: none;" class="replyID"><?php echo $post_entry[$i]['replyID'] ?></label>
                            <?php } ?>
                            <label class="date"><?php echo $post_entry[$i]['date'] ?></label>
                        </article>
                        <?php } }else{ ?>
                        <section class="empty">
                            <label>You have no post or reply for now</label>
                        </section>
                        <?php } ?>
                    </section>
                    <section class="number">
                        <?php for($i=0; $i<$page; $i++){ //GET REQUEST?>
                            <button><?php echo $i+1 ?></button>
                        <?php } ?>
                    </section>
                </div>
            </div>
            <div id="canc_acc" class="content">
                <h2>Delete Account</h2>
                <p> Are you sure you want to delete your account?<br>
                    <span style="color: red;">All your data will be removed and would not be possible recover it.</span> <br>
                    If you are sure please insert your password and press submit.
                </p>
                <form action="" method="POST">
                    <input type="password" name="password" placeholder="Insert your password" required>
                    <input type="submit" name="delete_submit" value="Delete my Account">
                    <?php
                        if(isset($_POST['delete_submit'])){
                            if(count($errors_delete)!=0){
                                echo "<ul class='errors'>";
                                    for($i=0; $i<count($errors_delete); $i++){
                                        echo "<li>". $errors_delete[$i]. "</li>";
                                    }
                                echo "</ul>"; 
                                unset($errors_delete);                               
                            }
                        }
                    ?>
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