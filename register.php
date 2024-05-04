<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <link rel="stylesheet" href="./css/register.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
</head>

<body>
    <?php
    include 'header.php';
    
    //Données reçues via formulaire?
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["passwordCheck"]) && isset($_POST["email"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["adress"]) && isset($_POST["birthDate"])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordCheck = $_POST['passwordCheck'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $adress = $_POST['adress'];
            $birthDate = $_POST['birthDate'];
            
            // Check if the email is unique
            $sql = $db->prepare('SELECT COUNT(email) AS EmailCount FROM user WHERE email = ?');
            $sql->execute([$email]);
            $qry = $sql->fetch(PDO::FETCH_ASSOC);
            if($qry['EmailCount'] > 0){
                echo "<script>alert('Cet email est déjà associé à un compte.');
                window.location = 'register.php';</script>";
                return;
            }

            // Check if the username is unique
            $sql = $db->prepare('SELECT COUNT(username) AS UsernameCount FROM user WHERE username = ?');
            $sql->execute([$username]);
            $qry = $sql->fetch(PDO::FETCH_ASSOC);
            if($qry['UsernameCount'] > 0){
                echo "<script>alert('Ce pseudo est déjà associé à un compte.');
                window.location = 'register.php';</script>";
                return;
            }
            
            $sql = "INSERT INTO `user` (`username`, `email`, `password`, `first_name`, `last_name`, `adress`, `birth_date`) VALUES (?, ?, ?, ?, ?, ?, ?)";

            // insert in database
            $rs = $db->prepare($sql);
            $rs->execute([$username, $email, $password, $firstName, $lastName, $adress, $birthDate]);
            $ID = $db->lastInsertId();

            if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['size'] != 0) {
                $profile_picture = file_get_contents($_FILES["photo_profil"]["tmp_name"]);
                $sql = "UPDATE user SET profile_picture = ? WHERE ID = ?";
                $qry = $db->prepare($sql);
                $qry->execute([$profile_picture, $ID]); // ID de l'user
                if ($qry)
                    $_SESSION["profile_picture"] = $profile_picture;
            } else {
                $profile_picture = file_get_contents('https://www.gravatar.com/avatar/'.md5(strtolower(trim($username))).'?d=identicon');
                $sql = "UPDATE user SET profile_picture = ? WHERE ID = ?";
                $qry = $db->prepare($sql);
                $qry->execute([$profile_picture, $ID]); // ID de l'user
                if ($qry)
                    $_SESSION["profile_picture"] = $profile_picture;
            }

            if ($rs) {
                $_SESSION["ID_user"] = $ID;
                $_SESSION['isAdmin'] = 0;
                header("Location: user.php?id=" . $ID);    
            }
        }
    }
             ?>

    <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">

        <div class="register_form">

            <div class="formbutton">Inscription</div>
            <label for="username">Pseudo :</label>
            <div class="icon-input">
                <input autofocus type="text" name="username" maxlength="20" placeholder="Pseudo" autocomplete="off" required>
                <i class="fa fa-fw fa-user"></i>
            </div>

            <label for="firstName">Prénom :</label>
            <div class="icon-input">
                <input type="text" name="firstName" maxlength="40" placeholder="Prénom" autocomplete="off" required>
                <i class="fa fa-fw fa-user"></i>
            </div>
            <label for="lastName">Nom de famille :</label>
            <div class="icon-input">
                <input type="text" name="lastName" maxlength="40" placeholder="Nom de famille" autocomplete="off" required>
                <i class="fa fa-fw fa-user"></i>
            </div>
            <label for="email">Email :</label>
            <div class="icon-input">
                <input type="text" name="email" maxlength="100" placeholder="Email" autocomplete="off" required>
                <i class="fa fa-fw fa-envelope"></i>
            </div>
            <label for="email">Adresse postale :</label>
            <div class="icon-input">
                <input type="text" name="adress" maxlength="100" placeholder="Adresse postale" autocomplete="off" required>
                <i class="fa fa-fw fa-envelope"></i>
            </div>
            <label for="birthDate">Date de naissance : </label>
            <input type="date" name="birthDate" maxlength="100" autocomplete="off" required>
            <label for="photo_profil">Photo de profil :</label>
            <div class="icon-input">
                <input type="file" name="photo_profil" accept="image/*">
                <i class="fa fa-fw fa-image"></i>
            </div>
            <label for="password">Mot de passe :</label>
            <div class="icon-input">
                <input type="password" name="password" id="password" maxlength="20" placeholder="Mot de passe" autocomplete="off" required>
                <i class="fa fa-fw fa-lock"></i>
            </div>
            <label for="password">Verification du mot de passe :</label>
            <div class="icon-input">
                <input type="password" name="passwordCheck" id="passwordCheck" maxlength="20" placeholder="Verification du mot de passe" autocomplete="off"  required>
                <i class="fa fa-fw fa-lock"></i>
                <p id="isIdentical"></p>
            </div>
            <div class="formbutton">
                <button type="submit" id="formButton" disabled>S'inscrire</button>
            </div>
    </form>
    </div>
    <script>
        // Check if the two passwords are identical
        $("#passwordCheck").on("keyup", function() {
            if ($('#password').val() !== $('#passwordCheck').val()) {
                $('#isIdentical').text("Passwords don\'t match");
                $("#isIdentical").css('color', 'red');
                $("#formButton").prop('disabled', true);
                $("#formButton").css('backgroundColor', 'red');
            }
            else{
                $('#isIdentical').text("Passwords match");
                $("#isIdentical").css('color', 'green');
                $("#formButton").prop('disabled', false);
                $("#formButton").css('backgroundColor', 'green');
            }
        });
    </script>


</body>

</html>