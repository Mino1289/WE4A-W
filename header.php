
<link rel="stylesheet" href="./css/header.css">

<header>


<?php
    session_start();
    include 'components/db.php';
    include 'components/functions.php';
    global $db;
    $email = $password = $username= " ";
    $emailErr = $passwordErr=" ";
    $verifPassword=" ";

    if($_SERVER["REQUEST_METHOD"]=="POST"){ // Variables are already define because we use required balise 
        $password = test_input($_POST["password"]);
        $email = test_input($_POST["email"]);
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // filter_var filters a variable with a specific filter. In this case it's for email
            $mailErr = "<script>validate('mail1');</script>
            <p class='error_message'>Incorrect format mail</p>";
        }

        if(!__isuserhere($email,'email',$db))
        {
            $emailErr ="<script>validate('mail1');</script>
            <p class='error_message'>Incorrect mail</p>";
            
        }
        
        // $password= md5($password);
        
        if($emailErr == " "){
            $sql="SELECT password FROM user WHERE email=?";
            $qry = $db->prepare($sql);
            $qry->execute([$email]);
            $verifPassword =$qry->fetch();

            if($verifPassword[0] != $password){
                $passwordErr="<script>validate('password1');</script>
                <p class='error_message'>Wrong password</p>";
            }else{
                $sql="SELECT username, ID FROM user WHERE password=? AND email= ?";
                $qry = $db->prepare($sql);
                $qry->execute([$password,$email]);
                $infos = $qry->fetch();

                // $validation = "<p id='welcome_back'>Welcome back $infos[0] </p><style>#welcome_back{color:green;}</style>";
                $_SESSION['ID_user'] = $infos["ID"];
                $_SESSION['profile_picture'] = __findPP($email,$password,$db);
                // header('Refresh:0');
                $page = $_SERVER['HTTP_REFERER'];
                header("Location: $page");
            
            }
        }
    } 
?>


<div id="modal" class="modal">

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

<div id="member_login" class="modal-content">
    <span class="close">&times;</span>


    <div class="name" name="mail1">
        <div><i class="fa fa-fw fa-envelope" id="logosearch"></i></div>
        <input required class='input' name="email" type="text" maxlength=60 placeholder="Email" autocomplete="off"/>
    </div>

    <?php echo $emailErr;?>

    <div class="name" name="password1">
        <div><i class="fa fa-fw fa-lock" id="logosearch"></i></div>
        <input required class='input' name="password" type="password" maxlength=40 placeholder="Password" autocomplete="off"/>
    </div>
    
    <?php echo $passwordErr;?>

    <input name="submit" type="submit" value="Submit" id="submit"/>


</div>
</form>
</div>


<div class="menu-container" onclick="changeState(this)">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    <div id="topnav" class="topnav hide">
        <a href="./index.php">W</a>
        <?php
            if(!(isset($_SESSION['ID_user']))){
                echo '<a id="modalBtn" href="#">Login</a>';
                echo '<a href="./register.php">Sign up</a>';
            } else {
                echo '<a href="./components/disconnect.php">Disconnect</a>';

            }
        ?>

    </div>

    <?php

        if (isset($_SESSION['ID_user'])) {
            if (isset($_SESSION['profile_picture'])) {
                $img = base64_encode($_SESSION['profile_picture']);
                echo '<a href="user.php?id='.$_SESSION['ID_user'].'">';
                echo '<img id="pdp" alt="profile_picture" src="data:image/png;base64,'.$img.'" />';
                echo '</a>';
            } else {
                echo '<a  href="user.php?id='.$_SESSION['ID_user'].'"><img id="pdp" alt="profile_picture" src="img/No_account.png"/></a>';
            }
        } else {
            echo '<a href="register.php">'.
                // '<img id="pdp" alt="profile_picture" src="img/2754.png"/>'.
                '</a>';
        }
    ?>

<script src="scripts/header.js"></script>


</header>