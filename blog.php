<?php
    session_start();
    $path = "/login.php";
    $redir = true;

    include $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    include $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php'; 
    require $_SERVER['DOCUMENT_ROOT'] . '/php/blog_post.php'; 
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

        <script defer type="text/javascript" src="/js/validation_post.js" charset="utf-8"></script>
        <script defer type="text/javascript" src="/js/see_post.js" charset="utf-8"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script defer type="text/javascript" src="js/navToggle.js" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                <?php if(!$_SESSION['verified']){ ?>
                $(".add-post").remove()
                $("#welcome").after("<div class='verification'><p>YOU NEED TO VERIFY YOUR ACCOUNT TO POST</p></div>")
                $(".verification").click(function(){
                    window.location.replace("/verification.php");
                })
                <?php if($empty){ ?>
                $(".verification").css("margin-bottom", "50vh")

                <?php }} ?>
            })
        </script>


        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/effect.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/blog.css">
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




        <?php echo "<div id='welcome'><h1><span style='font-weight:bolder'>".$_SESSION['name']. " ". $_SESSION['surname']. "</span> - Welcome to My Blog</h1><hr></div>"; ?>
        
        <?php if($empty){ ?>
        <section class="add-post first">
            <div class="add-toggle first visible">
        
        <?php }else{ ?>
        <section class="add-post more">
            <div class="add-toggle more visible">
        <?php } ?>
            
                <p class="visible">There <?php if($empty){ ?> are no posts<?php }else{if(mysqli_num_rows($data)==1){echo "is 1 post";}else{echo "are ". mysqli_num_rows($data). " posts";}} ?>... for now<br>Do you want to Add One?</p>
                <button id="addPost">Add Post</button>
                <?php if(!$empty){ ?>
                <form action="" method="POST" id="filter-form">
                    <label style="display:inline-block">Filter: </label>
                    <select name="month" id="filter">
                        <option value="" <?php if(!isset($_POST['month'])){ echo "selected>Choose a Month";}else{echo ">Reset";} ?> </option>
                        <?php 
                        for($i=0; $i<count($date_post); $i++){
                            echo "<option "; if(isset($_POST['month'])){ if($_POST['month']==$date_post[$i]){ echo "selected";}} echo ">". $date_post[$i]. "</option>";
                        }
                        ?>

                    </select>
                </form>
                <?php } ?>
            </div>

            <?php if($empty){ ?>
            <div class="post-form first visible">
            <?php }else{ ?>
            <div class="post-form more visible">
            <?php } ?> 
            
                <form action="" method="POST" id="postblog">
                    <fieldset class="content">
                        <input type="text" name="title" id="title" placeholder="Choose a Title" maxlength="35" required>
                        <textarea name="message" id="message"  rows="10" maxlength="400" placeholder="...And now your message" required></textarea>
                    </fieldset>
                    <fieldset class="submit">
                        <div class="row">
                            <input id="submit" type="submit" value="Submit" name="post_submit">
                            <input id="reset" type="reset" value="Clear All">
                        </div>
                        <div class="row">
                            <button type="button" id="cancel">Cancel Message</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </section>
        <?php if(!$empty){ ?>
        <section class="show-post">
            <?php for($i=0; $i<count($info); $i++){ ?>
            <article class="post <?php if($info[$i]['name']=="Simone" && $info[$i]['surname']=="Bartoli"){echo 'royal';}?>">
                <label class="postID" style="display: none;"><?php echo $info[$i]['postID'] ?></label>
                <div class="first">
                    <label style="display:inline-block" class="perinf"><?php echo $info[$i]['name']. " ". $info[$i]['surname']. "</label>";
                    if($info[$i]['name']=="Simone" && $info[$i]['surname']=="Bartoli"){echo "<i class='fas fa-crown' style='color: #FFD700; font-size: 1em; padding-left: 0.5em;'></i>";}  ?>
                    <label class="date"><?php echo $info[$i]['date']?></label>
                </div>
                <hr class="mobile-only">
                <i class="fas fa-long-arrow-alt-right no-mobile"></i>
                <label class="title-post"><?php echo $info[$i]['title']?></label>
                <p style="font-weight: bold;"><?php echo getReplyNumber($i). " Replies"?></p>
            </article>
            <?php } ?>
        </section>
        <?php }?>



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