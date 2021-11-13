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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/effect.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/terms&condition.css">
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


    <div class="intro">
        <h1>Terms & Condition</h1>
        <p>The following information represents the Terms and Conditions of use of private areas
            of the site "bartolisimone.com". The end user must accept the "User Agreement"
            and "Administrator Agreement" sections if he wants to be able to continue in the private areas.</p>
    </div>

    <div class="info">
        <h2>Information and Syntax</h2>
        <p>Below you will find the meaning of words that will be used in "User Agreement"
            and "Administrator Agreement" sections.</p>
        <ul>
        <li><b>Site:</b> bartolisimone.com or http://bartolisimone.com or https://bartolisimone.com or http://www.bartolisimone.com or https://www.bartolisimone.com</li>
        <li><b>Private Areas:</b> Blog and Account pages</li>
        <li><b>Rules:</b> all the elements inside the "User Agreement" and "Administrator Agreement" sections</li>
        </ul>
    </div>
    <div class="user-agreement">
        <h2>User Agreement</h2>
        <p>In this section you will find the RULES of use of the site that must be respected by the end user.
            These rules are categorical, without free interpretation and valid 24/7.
            Violation of these rules will lead to specific consequences described better below.</p>

        <h3>Rules Class A</h3>
        <p>All Class A rules are rules whose violation will lead to an <b>INSTANT PERMANENT BAN</b> of the offender's account.
            All public account information such as POSTS and REPLIES will be forcibly deleted while private information such as NAME, SURNAME,
            DATE OF BIRTH and EMAIL will be kept in a separate list that may (if necessary) be used for <b>future investigation or legal action</b>.</p>
        <ul>
            <li>A01 - The user <b>MUST</b> register using his real personal data and an email owned by him</li>
            <li>A02 - The user <b>MUST NOT</b> create more than 1 account</li>
            <li>A03 - The user <b>MUST NOT</b> try in any way to violate the private sections of other users and/or the administrator</li>
            <li>A04 - The user <b>MUST NOT</b> try to attack (through any technique) the security and stability of the site</li>
            <li>A05 - The user <b>MUST NOT</b> share his login details with anyone</li>
            <li>A06 - The user <b>MUST NOT</b> use vulgar and/or bad language in any POST or REPLY</li>
            <li>A07 - The user <b>MUST NOT</b> share any type of Copyright content</li>
            <li>A08 - The user <b>MUST NOT</b> use in any POST or REPLY words and/or phrases and/or idioms that may violate religious freedom</li>
            <li>A09 - The user <b>MUST NOT</b> use in any POST or REPLY words and/or phrases and/or idioms that may violate the freedom of political idea</li>
            <li>A10 - The user <b>MUST NOT</b> use words and/or phrases and/or idioms in any POST or REPLY that may violate the freedom of sex and sexual orientation</li>
            <li>A11 - The user <b>MUST NOT</b> use words and/or phrases and/or idioms in any POST or REPLY that may racially discriminate other individuals and/or groups</li>
            <li>A12 - The user <b>MUST NOT</b> use in any POST or REPLY words and/or phrases and/or idioms that may discriminate the physical appearance of other people and/or groups</li>
            <li>A13 - The user <b>MUST NOT</b> use words and/or phrases and/or idioms in any POST or REPLY that can discriminate anyone with physical and/or mental illnesses</li>
            <li>A14 - The user <b>MUST NOT</b> use in any POST or REPLY words and/or phrases and/or idioms that may incite hatred and/or contempt of any person and/or group</li>
            <li>A15 - The user <b>MUST NOT</b> violate any Class B rule more than 2 times</li>
        </ul>

        <h3>Rules Class B</h3>
        <p>All class B rules are rules that if violated lead to an <b>IMMEDIATE SUSPENSION</b> of the account and related features for <b>2 days at the first infringement and one week at the second</b>. 
        The suspension leads to the inability to access the private areas for the established time. All POSTS and/or REPLIES that triggered the report will be removed while the other 
        information will remain unchanged. On the third infringement, the account suffers the consequences described in the "Rules Class A" section.</p>

        <ul>
            <li>B01 - The user <b>MUST</b> post POST and/or REPLY in English only
                (you can use any translator on the internet if you don't know the language).
                PS: you don't need perfect English, just make yourself understood by others :)
          </li>
            <li>B02 - The user <b>MUST NOT</b> post any POST and/or REPLY for the purpose of spamming or advertising to third parties</li>
            <li>B03 - The user <b>MUST NOT</b> post any meaningless POST and/or REPLY</li>
            <li>B04 - The user <b>MUST NOT</b> post any REPLY that is not related to the POST question</li>
            <li>B05 - The user <b>MUST NOT</b> post POSTs that are outside the allowed topics (see below)</li>
                <ul class="sublist">
                <li>Problems or Programming Tips</li>
                <li>Management or Software Issues</li>
                <li>Information and News on Technology</li>
                </ul>
        </ul>

        <h3>General Security Information</h3>
        <p>This site to avoid security problems (such as the creation of multiple accounts and the posting of prohibited content) allows the post functionality only after a verification of the account. 
        This can be done by verifying a phone number (through an OTP code) or by verifying a PayPal account. 
        For more information contact info@bartolisimone.com</p>

        <h3>Update on Terms & Condition</h3>
        <p>This site can update its terms and conditions at any time by adding new rules or modifying existing ones. 
            The user will be notified by email and web notification of new changes at least 48 hours before the start date of the new terms and conditions. 
            The user can opt out of the new terms at any time by canceling its account; if, however, by the start date of the new terms the user has not taken any action in this regard, the new conditions will be automatically accepted.</p>
        
    </div>
    <div class="administrator-agreement">
        <h2>Administrator Agreement</h2>
        <p>This section describes the privileges and obligations of the blog and site administrator, <b>Simone Bartoli</b>.</p>
        <h3>Obligations</h3>
        <ul>
            <li>The admin <b>MUST</b> comply with all Class A and Class B rules (with the exception of rule B02 at most)</li>
            <li>The admin <b>MUST</b> guarantee the security of the site through constantly updated security protocols</li>
            <li>The admin <b>MUST</b> protect user informations from possible external attacks by making inaccessible to everyone 
            at least the login PASSWORD and the PHONE NUMBER connected to the account</li>
            <li>The admin <b>MUST</b> take action against anyone who violates the rules in the "User Agreement" section as soon as possible</li>
            <li>The admin <b>MUST</b> have the Terms & Conditions signed whenever there are any changes to the document itself</li>
        </ul>
        <h3>Privileges</h3>
        <ul>
            <li>The admin <b>HAS</b> the power to delete any POST and/or REPLY not published by him</li>
            <li>The admin <b>HAS</b> the power to ban or suspend the account of anyone who violates the rules of the "User Agreement" section</li>
            <li>The admin <b>HAS</b> the power to access user information such as <b>NAME, SURNAME, DATE OF BIRTH, EMAIL, POST & REPLY published</b></li>
        </ul>
    </div>
    <hr>
    <div class="hash">
        <p>All contents above the white line is part of the Terms and Conditions.</p><br>
        <p style="word-break: break-all;">Verification Hash (SHA256): <b>42B35972D11EECB404E43B545C9EC1DC23EF45D263AAE9DFD6D0A375EB454537</b></p>
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