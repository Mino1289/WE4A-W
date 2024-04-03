
<link rel="stylesheet" href="./css/header.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<header>


<?php
    session_start();
    include 'components/db.php';
    include 'components/functions.php';
    global $db;
    $email = $password = $username= " ";
    $emailErr = $passwordErr=" ";
    $verifPassword=" ";

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["login"])){ // Variables are already define because we use required balise 
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
                $sql="SELECT username, ID, isAdmin FROM user WHERE password=? AND email= ?";
                $qry = $db->prepare($sql);
                $qry->execute([$password,$email]);
                $infos = $qry->fetch();

                // $validation = "<p id='welcome_back'>Welcome back $infos[0] </p><style>#welcome_back{color:green;}</style>";
                $_SESSION['ID_user'] = $infos["ID"];
                $_SESSION['isAdmin'] = $infos["isAdmin"];
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

<div class="modal-content">
    <div id="member_login">
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

    <input name="login" type="submit" value="Submit" id="submit"/>


</div></div>
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
                // my page
                echo '<a href="user.php?id='.$_SESSION['ID_user'].'">My page</a>';
                echo '<a href="./components/disconnect.php">Disconnect</a>';

                if($_SESSION['isAdmin']) {
                    echo '<a href="./admin.php">Admin</a>';
                }

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