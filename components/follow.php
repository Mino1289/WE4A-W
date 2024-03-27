<?php
session_start();
include "functions.php";
include "db.php";
global $db;

if (!isset($_GET["id"])) {
    header("Location: ../index.php");
} else {
    $id_userpage = $_GET["id"];
}
if (!isset($_SESSION["ID_user"])) {
    header("Location: ../index.php");
} else {
    $id_user = $_SESSION["ID_user"];
}

if ($id_user == $id_userpage) {
    header("Location: ../user.php?id=$id_user");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT * FROM follow WHERE ID_user = ? AND ID_followed = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_userpage]);
    $follow = $query->fetch(PDO::FETCH_ASSOC);
    if ($_POST["follow"]) {
        if ($follow) {
            $sql = "DELETE FROM follow WHERE ID_user = ? AND ID_followed = ?";
            $query = $db->prepare($sql);
            $query->execute([$id_user, $id_userpage]);
        } else {
            $sql = "INSERT INTO follow (ID_user, ID_followed) VALUES (?, ?)";
            $query = $db->prepare($sql);
            echo $query->queryString;
            echo "id_user:". $id_user;
            echo "id_page:". $id_userpage;
            $query->execute([$id_user, $id_userpage]);
        }
    }
}
header("Location: ../user.php?id=$id_userpage");
?>