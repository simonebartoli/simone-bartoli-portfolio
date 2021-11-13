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
            <h2>New Contact Request</h2>
            <?php date_default_timezone_set('Europe/London');?>
            <p><br>
               You received a new contact request on <b><?php echo date('d-m-Y H:i'); ?></b>.<br>
            </p>
            <p>You can find the details of the request below.</p>
            <hr>
            <p>
                <?php
                    $request = str_pad($result, 5, '0', STR_PAD_LEFT);
                    echo "Request N.".$request . "<br>";
                    echo "Sent: ". date("d-m-Y"). ' at '. date('H:i'). "<br><br>";


                    echo "Name: ". $name."<br>"; 
                    echo "Surname: ". $surname."<br>"; 
                    echo "Email: ". $email."<br><br>"; 
                    echo "Message: <br>". $message."<br>"; 
                ?>
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

