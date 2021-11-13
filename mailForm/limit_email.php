<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div id="title" style="background-color: #1B2326; padding: 1em; color: whitesmoke;">
            <h1 style="font-size: 2em; text-align:center;">Simone Bartoli</h1>
        </div>
        <div id="content" style="font-size: 1.5em; margin: 1em; text-align: left;">
            <div>
                <h2>Your Account has been SUSPENDED/BANNED</h2>
                <p>Dear <?php 
                echo $name. " " .$surname
                ?>, <br>
                </p>
                <p>This email has been sent to inform you that your account has been suspended/banned after the violation of 
                the rule A01. For this reason you
                <?php
                    date_default_timezone_set('Europe/London');
                    echo date('d-m-Y'). ' at '. date('H:i'). '.';
                ?>
                </p>
                <hr>
                <h3>What can you do now?</h3>
                <p> You can <span style="font-weight: bold;">view your profile</span> clicking in the top left of the navbar and <br>
                    <span style="font-weight: bold;">access to the blog</span> where you'll be able to post question and reply to other people's questions.
                </p>
            </div>
        </div>
        <div id="footer" style="background-color: #1B2326; padding: 1em; color: whitesmoke;">
            <p> <span style="color: white; font-weight: bold;">THIS IS A NO-REPLY EMAIL.</span> Please do not send anything to this because it would be automatically deleted without being read. <br>
                <span style="display: block; margin: 0.5em;"></span>If you haven't send the request please contact <a href="mailto:Info@bartolisimone.com">Info@bartolisimone.com</a>
            </p>
            <p style="margin-top: 2em;">Structured & Designed by <span style="font-weight: bold;">Simone Bartoli</span> | Every right reserved&reg;</p>
        </div>
    </body>
</html>

