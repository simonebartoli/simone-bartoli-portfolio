<?php
    session_start();
    $path = "/login.php";
    $redir = true;

    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    require $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php'; 
    require $_SERVER['DOCUMENT_ROOT'] . '/php/retrieve_post.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/php/blog_reply.php';

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
        <script defer type="text/javascript" src="js/validation_reply.js" charset="utf-8"></script>
        <script defer type="text/javascript" src="js/delete_post.js" charset="utf-8"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                <?php if(!$_SESSION['verified']){ ?>
                $("form").remove()
                $("#add-post").remove()
                $(".nav-resp").after("<div class='verification'><p>YOU NEED TO VERIFY YOUR ACCOUNT TO REPLY</p></div>")
                $(".verification").click(function(){
                    window.location.replace("/verification.php");
                })
                <?php } ?>
            })
        </script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/effect.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/view-post.css">
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

        <section class="show-post">
            <article class="post">
                <div class="info">
                    <label class="perinf"><?php echo $data['name']. " ". $data['surname']?></label>
                    <label class="date"><?php echo $data['date']?></label><hr>
                    <label class="title-post"><?php if($data['banned']){echo "This Post HAS BEEN BANNED";}else{echo $data['title'];} ?></label>
                    <label class="message-post"><?php if($data['banned']){echo "This Post HAS BEEN BANNED";}else{echo $data['message'];}?></label>
                </div>
                <div class="action">
                    <p style="font-weight: bold;"><?php echo count($repl). " Replies in Total"?></p>
                    <button id="add-post">Add Reply</button>
                    <?php if($_SESSION['role']=="admin"){ ?>
                        <button type="button" id="delete-post">DELETE POST</button>
                    <?php } ?>
                </div>
            </article>

            <form action="" method="POST" class="invisible">
                <fieldset class="content">
                    <textarea name="message" id="message"  rows="10" maxlength="400" placeholder="...And now your message" required></textarea>
                </fieldset>
                <fieldset class="submit">
                    <div class="row">
                        <input id="submit" type="submit" value="Submit" name="reply_submit">
                        <input id="reset" type="reset" value="Clear All">
                    </div>
                    <div class="row">
                        <button type="button" id="cancel">Cancel Message</button>
                    </div>
                </fieldset>
            </form>
            <?php if(!$empty){ ?>
            <div class="contain">
                <?php for($i=0; $i<count($repl); $i++){ ?>
                <article <?php if(isset($selected)){if($repl[$i]['replyID']==$selected) echo 'id="selected"';}?>class="replies <?php if($repl[$i]['name']=="Simone" && $repl[$i]['surname']=="Bartoli"){echo 'royal';}?>">
                    <div class="info">
                        <label style="display:inline-block" class="perinf"><?php echo $repl[$i]['name']. " ". $repl[$i]['surname']?> - Reply</label>
                        <?php if($repl[$i]['name']=="Simone" && $repl[$i]['surname']=="Bartoli"){echo "<i class='fas fa-crown' style='color: #FFD700; font-size: 1em; padding-left: 0.5em;'></i>";}  ?>
                        <label class="date"><?php echo $repl[$i]['date']?></label><hr>
                        <label class="message-post"><?php echo $repl[$i]['message']?></label>
                    </div>
                    
                        <?php if($_SESSION['role']=="admin"){ ?>
                            <div class="action">
                                <hr>
                                <button type="button" class="delete-reply">DELETE REPLY</button>
                            </div>
                        <?php } ?>
                </article>
                <?php } ?>
            </div>
            <?php } ?>
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