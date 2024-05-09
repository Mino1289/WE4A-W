<?php
    session_start();
    include 'db.php';
    global $db;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
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
            //$sql = $db->prepare('SELECT COUNT(email) AS EmailCount FROM user WHERE email = ?');
            $sql = "SELECT email AS EmailCount FROM user WHERE email = ?";
            $qry = $db->prepare($sql);
            $result = $qry->execute([$email]);
            $isAlreadyExist = $qry->fetch(PDO::FETCH_ASSOC);
            if($isAlreadyExist){
                echo "<script>alert('Cet email est déjà associé à un compte.');
                window.location = '../index.php';</script>";
                return;
            }

            // Check if the username is unique
            $sql = "SELECT username AS UsernameCount FROM user WHERE username = ?";
            $qry = $db->prepare($sql);
            $result = $qry->execute([$username]);
            $isAlreadyExist = $qry->fetch(PDO::FETCH_ASSOC);
            if($isAlreadyExist){
                echo "<script>alert('Ce pseudo est déjà associé à un compte.');
                window.location = '../index.php';</script>";
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
                $_SESSION['isBanned'] = 0;
                header("Location: ../user.php?id=" . $ID);
            }
        }
    }
?>