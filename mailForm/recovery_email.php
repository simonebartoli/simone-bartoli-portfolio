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
                <h2>Recovery Password Request</h2>
                <p>Dear  
                <?php 
                $name;
                $surname;
            
                function setVar($newName, $newSurname){
                    global $name;
                    global $surname;
            
                    $name = $newName;
                    $surname = $newSurname;
                }
                echo $name. " " .$surname
                ?>
                </p>
                <p>This email contain the link to access your account without the password. You request the password recovery on  
                <?php
                    date_default_timezone_set('Europe/London');
                    echo date('d-m-Y'). ' at '. date('H:i'). '.';
                ?>
                The link that you find below can be used once and expire after 1 hour.</p>
                <hr>
                <h3>Click on the button to access your account</h3>
                <?php
                    $link;
                    echo '<a href='. $link. '><button style="margin-top: 0.5em; width: 200px; height: 75px; color: whitesmoke; font-size: 1.5em; border: 2pt solid black; background-color: #394245;">LogIn</button></a>';
                ?>
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

