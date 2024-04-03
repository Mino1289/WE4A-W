<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./css/register.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <h1>W Social Network : for the winners</h1>
    <?php
    // database connection code
    session_start();
    include 'components/db.php';
    global $db;


    //Données reçues via formulaire?
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["birthDate"])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $birthDate = $_POST['birthDate'];

            $sql = "INSERT INTO `user` (`username`, `email`, `password`, `first_name`, `last_name`, `birth_date`) VALUES (?, ?, ?, ?, ?, ?)";

            // insert in database 
            $rs = $db->prepare($sql);
            $rs->execute([$username, $email, $password, $firstName, $lastName, $birthDate]);
            $ID = $db->lastInsertId();

            if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['size'] != 0) {
                $profile_picture = file_get_contents($_FILES["photo_profil"]["tmp_name"]);
                $sql = "UPDATE user SET profile_picture = ? WHERE ID = ?";
                $qry = $db->prepare($sql);
                $qry->execute([$profile_picture, $ID]); // ID de l'user
            }

            if ($rs) {
                $_SESSION["ID_user"] = $ID;
                $_SESSION["profile_picture"] = $profile_picture;
                $_SESSION['isAdmin'] = 0;
                header("Location: user.php?id=" . $ID);    
            }
        }
    } ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">

        <div class="register_form">

            <div class="formbutton">Register</div>
            <label for="username">Username :</label>
            <div class="icon-input">
                <input autofocus type="text" name="username" maxlength="20" placeholder="Username" autocomplete="off">
                <i class="fa fa-fw fa-user"></i>
            </div>

            <label for="firstName">First Name :</label>
            <div class="icon-input">
                <input type="text" name="firstName" maxlength="40" placeholder="First Name" autocomplete="off">
                <i class="fa fa-fw fa-user"></i>
            </div>
            <label for="lastName">Last Name :</label>
            <div class="icon-input">
                <input type="text" name="lastName" maxlength="40" placeholder="Last Name" autocomplete="off">
                <i class="fa fa-fw fa-user"></i>
            </div>
            <label for="email">Email :</label>
            <div class="icon-input">
                <input type="text" name="email" maxlength="100" placeholder="Email" autocomplete="off">
                <i class="fa fa-fw fa-envelope"></i>
            </div>
            <label for="birthDate">Birthdate : </label>
            <div class="icon-input">
                <input type="date" name="birthDate" maxlength="100" autocomplete="off">
                <i class="fa fa-fw fa-calendar"></i>
            </div>
            <label for="photo_profil">Profile Picture :</label>
            <div class="icon-input">
                <input type="file" name="photo_profil" accept="image/*">
                <i class="fa fa-fw fa-image"></i>
            </div>
            <label for="password">Password :</label>
            <div class="icon-input">
                <input type="password" name="password" maxlength="20" placeholder="Password" autocomplete="off">
                <i class="fa fa-fw fa-lock"></i>
            </div>
            <div class="formbutton">
                <button type="submit">Envoyer le formulaire</button>
            </div>
    </form>
    </div>
    <hr>


</body>

</html>