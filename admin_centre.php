<?php
    session_start();
    $path = "/login.php";
    $redir = true; //only redirect if it is in private access only page

    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    require $_SERVER['DOCUMENT_ROOT'] . '/php/restore_session.php'; 

    if($_SESSION['role']!="admin"){
        redir("/account.php");
    }

    require $_SERVER['DOCUMENT_ROOT'] . '/php/administration.php'; 

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
                $(".select table tbody tr").each(function(){
                    $(this).click(function(){
                        $("#limit").prop("disabled", false)
                        $(".submit button").prop("disabled", false)

                        $(".row").css("display", "none")
                        $(".select button").prop("disabled", false)
                        $(".select button").html("NEXT")
                        id = $(".id", this).html();
                        $("table tr").css({"background-color":"unset", "font-weight":"normal", "text-decoration":"unset"})
                        $(this).css({"background-color":"#394245", "font-weight":"bolder", "text-decoration":"underline"})
                    })
                })
                $(".select button").click(function(){
                    var info = {
                        search: true,
                        idsearch: id
                    }
                    $.ajax({
                        type: "POST",
                        url: "/php/administration",
                        data: info,
                        dataType: "json",
                        encode: true
                    })
                    .done(function(data){
                        $(".rules .row").css("display", "flex")

                        $("#limit").val("") //TO RESET EACH TIME YOU CHANGE ROW SELECTED
                        $("#limit option:selected").val("").change()

                        userInfo = data;
                        report = 0
                        for(let i=0; i<userInfo.length; i++){
                            if(userInfo[i].status == "suspended" || userInfo[i].status == "banned"){
                                 report = report + 1
                            }
                        }
                        rule()
                    })
                })
            })
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/effect.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/all.css">
        <link rel="stylesheet" href="css/admin_centre.css">
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


        <header>
            <h1>This space is reserved to the ADMIN ONLY</h1>
            <hr>
        </header>

        <div class="select">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Terms Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<count($data); $i++){ ?>
                    <tr>
                        <td class="id" style="display: none;"><?php echo $data[$i]['accountID']; ?></td>
                        <td><?php echo $data[$i]['name']; ?></td>
                        <td><?php echo $data[$i]['surname']; ?></td>
                        <td><?php echo $data[$i]['email']; ?></td>
                        <td><?php echo $data[$i]['date']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button disabled>NEXT <i class="far fa-lock" style="margin-left: 1em;"></i></button>
        </div>

        <div class="rules">
            <script>
                function rule(){
                    $(".list li").remove();
                    if(report == 0){
                        $(".desc").html(userInfo[0].name + " " + userInfo[0].surname + " has never been reported")
                    }else{
                        $(".desc").html(userInfo[0].name + " " + userInfo[0].surname + " has been reported " + report + " times")
                        for(let i=0; i<userInfo.length; i++){
                            if(userInfo[i].status == "suspended"){
                                $(".list").append("<li>SUSPENDED - Rule Violated " + userInfo[i].rule + " - " + userInfo[i].date +"</li>")
                            }else if(userInfo[i].status == "banned"){
                                $(".list").append("<li>BANNED - Rule Violated " + userInfo[i].rule + " - " + userInfo[i].date +"</li>")
                                $("#limit").prop("disabled", true)
                            }
                        }
                    }
                    $("#limit").change(function(){
                        $("#rule").empty()

                        $(".rules button").prop("disabled", true)
                        if($(".rules button").prop("disabled")){
                            $(".post button").prop("disabled", true)
                            $(".post .row, .submit .row").css("display", "none")
                        }

                        $("#rule").append("<option value='' selected>Rule Violated</option>")
                        if($("#limit option:selected").val() == ""){
                            $("#rule").prop("disabled", true)
                            return
                        }

                        $("#rule").prop("disabled", false)
                        selected = $("#limit option:selected").val()
                        if(selected != "suspend"){
                            $("#rule").append("<optgroup label='Class A Rules'>")
                            <?php for($i=0; $i<15; $i++){ ?>
                                $("#rule").append("<option value='<?php echo 'A'.str_pad(strval($i+1), 2, '0', STR_PAD_LEFT); ?>'><?php echo 'A'.str_pad(strval($i+1), 2, "0", STR_PAD_LEFT); ?></option>")
                            <?php } ?>
                            $("#rule").append("</optgroup>")
                        }else{
                            $("#rule").append("<optgroup label='Class B Rules'>")
                            <?php for($i=0; $i<5; $i++){ ?>
                                $("#rule").append("<option value='<?php echo 'B'.str_pad(strval($i+1), 2, '0', STR_PAD_LEFT); ?>'><?php echo 'B'.str_pad(strval($i+1), 2, "0", STR_PAD_LEFT); ?></option>")
                            <?php } ?>
                            $("#rule").append("</optgroup>")
                        }
                    })
                    $("#rule").change(function(){
                        $(".rules button").prop("disabled", true)
                        if($(".rules button").prop("disabled")){
                            $(".post button").prop("disabled", true)
                            $(".post .row, .submit .row").css("display", "none")
                        }

                        ruleViolated = $("#rule option:selected").val()
                        if($("#rule option:selected").val() != ""){
                            $(".rules button").prop("disabled", false)
                        }
                        else{
                            $(".rules button").prop("disabled", true)
                        }
                    })
                    $(".rules button").click(function(){
                        if(ruleViolated=="A01" || ruleViolated=="A02" || ruleViolated=="A03" || ruleViolated=="A04" || ruleViolated=="A05" || ruleViolated=="A07"){
                            $(".submit .row").css("display", "flex")
                            postid = null
                            replyid = null
                            submit()
                        }
                        else{
                            var info = {
                                find_post: true,
                                idsearch: id
                            }
                            $.ajax({
                                type: "POST",
                                url: "/php/administration",
                                data: info,
                                dataType: "json",
                                encode: true
                            })
                            .done(function(data){
                                postInfo = data
                                $(".post .row").css("display", "flex")
                                post()
                            })
                        }

                    })


                }
            </script>
            <h2>SELECT RULES <i class="far fa-chevron-down" style="margin-left: 0.5em;"></i></h2>
            <div class="row" style="display: none;">
                <div class="column list">
                    <p class="desc">L'utente è stato segnalato tot volte</p>
                </div>
                <div class="column">
                    <select name="limit" id="limit" required>
                        <option value="" selected>Choose the LIMIT</option>
                        <option value="ban">BAN THE USER</option>
                        <option value="suspend">SUSPEND THE USER</option>
                    </select>
                    <select name="rule" id="rule" required disabled>
                        <option value="" selected>Rule Violated</option>
                    </select>
                    <button disabled>NEXT</button>
                </div>
            </div>
        </div>

        <div class="post">
            <h2>SELECT POST (OPTIONAL) <i class="far fa-chevron-down" style="margin-left: 0.5em;"></i></h2>
            <div class="row" style="display: none;">
                <script>
                    function post(){
                        $(".post p").remove()
                        $(".post table tbody").remove()

                        if(postInfo == null){
                            $(".post table").css("display", "none")
                            $(".post .row").prepend("<p class='nothing'>The User has no POST</p>")
                            $(".post button").css("display", "none")
                            return
                        }else{
                            $(".post table").css("display", "table")
                            $(".post table").append("<tbody>")
                            for(let i=0; i<postInfo.length; i++){
                                if(postInfo[i].banned == true){
                                    $(".post table").append("<tr disabled>")
                                }else{
                                    $(".post table").append("<tr>")
                                }
                                $(".post table tr:last").append("<td class='postid' style='display: none;'>" + postInfo[i].postID + "</td>")
                                if(postInfo[i].type == "REPLY"){
                                    $(".post table tr:last").append("<td class='replyid' style='display: none;'>" + postInfo[i].replyID + "</td>")
                                    $(".post table tr:last").append("<td>" + postInfo[i].type + "</td>")
                                    $(".post table tr:last").append("<td>" + postInfo[i].message.substring(0, 15) + "...</td>")
                                }
                                else{
                                    $(".post table tr:last").append("<td>" + postInfo[i].type + "</td>")
                                    $(".post table tr:last").append("<td>" + postInfo[i].title + "</td>")
                                }
                                $(".post table tr:last").append("<td>" + postInfo[i].date + "</td>")

                                if(postInfo[i].type == "REPLY"){
                                    $(".post table tr:last").append("<td style='text-align: center;'><a target='_BLANK' href='/view-post.php?postid=" + postInfo[i].postID + "&reply=" + postInfo[i].replyID + "#selected'>LINK TO REPLY</a></td>")
                                }else{
                                    $(".post table tr:last").append("<td style='text-align: center;'><a target='_BLANK' href='/view-post.php?postid=" + postInfo[i].postID + "'>LINK TO POST</a></td>")
                                }
                                $(".post table").append("</tr>")
                            }
                            $(".post table").append("</tbody>")
                        }
                        $(".post table tbody tr").each(function(){
                            $(this).click(function(){
                                $(".post button").prop("disabled", false)
                                $(".post button").html("NEXT")
                                postid = $(".postid", this).html()
                                replyid = $(".replyid", this).html()
                                $(".post table tr").css({"background-color":"unset", "font-weight":"normal", "text-decoration":"unset"})
                                $(this).css({"background-color":"#394245", "font-weight":"bolder", "text-decoration":"underline"})
                            })
                        })
                        $(".post button").click(function(){
                            $(".submit .row").css("display", "flex")
                            submit()
                        })

                    }
                </script>
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Title/Message</th>
                            <th>Date Published</Th>
                            <th>Link</th>
                        </tr>
                    </thead>
                </table>
                <button disabled>NEXT <i class="far fa-lock" style="margin-left: 1em;"></i></button>
            </div>
        </div>

        <div class="submit">
            <h2>REPORT <i class="far fa-chevron-down" style="margin-left: 0.5em;"></i></h2>
            <div class="row" style="display: none;">
            <script>
                function submit(){
                    //avoid report number problem START
                    report = 0
                    for(let i=0; i<userInfo.length; i++){
                        if(userInfo[i].status == "suspended"){
                                report = report + 1
                        }
                    }
                    //avoid report number problem END

                    report = report + 1
                    $(".submit .row").empty()
                    if(selected == "ban"){
                        $(".submit .row").append("<p class='final'>The user " + userInfo[0].name + " " + userInfo[0].surname + 
                        " WILL BE BANNED after the violation of the RULE - " + ruleViolated + ". </p>")
                        $(".submit .row").append("<p class='final'>All his/her posts will be flagged and no longer publicly available</p>")

                    }else{
                        if(report==1){
                            var option = "his/her account will be suspended for 48 hours"
                        }else if(report==2){
                            var option = "his/her account will be suspended for 7 days"
                        }else{
                            var option = "his/her account WILL BE ALSO BANNED for the violation of the rule A15"
                        }
                        $(".submit .row").append("<p class='final'>The user " + userInfo[0].name + " " + userInfo[0].surname + 
                        " WILL BE SUSPENDED after the violation of the RULE " + ruleViolated + "<br>" +
                        "This is the " + report + " Report so " + option + ".</p>")
                        if(report != 3){
                            if(replyid == null){
                                $(".submit .row").append("<p class='final'>This <a target='_BLANK' href='/view-post.php?postid=" + postid + "'>POST</a> will be flagged</p>")
                            }else{
                                $(".submit .row").append("<p class='final'>This <a target='_BLANK' href='/view-post.php?postid=" + postid + "&reply=" + replyid + "#selected'>REPLY</a>" + " will be flagged</p>")
                            }
                        }else{
                            $(".submit .row").append("<p class='final'>All his/her posts will be flagged and no longer publicly available</p>")
                        }
                    }
                    $(".submit .row").append("<button style='width: 35%;' >SUBMIT</button>")
                    $(".submit button").click(function(){
                        if(confirm("Are you sure you want to continue?")){                            
                            var info = {
                                submit: true,
                                idsearch: id,
                                postsearch: postid,
                                replysearch: replyid,
                                limit: selected,
                                rule: ruleViolated,
                                reportN: report
                            }
                            
                            $.ajax({
                                type: "POST",
                                url: "/php/administration",
                                data: info,
                                dataType: "json",
                                encode: true
                            })
                            .done(function(data){
                                $(".submit .row").append("<p class='final' style='margin-top: 2em;'>Your request was successful</p>")
                                $(".submit button").prop("disabled", true)
                            })
                            
                        }
                    })
                }     
            </script>
                
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