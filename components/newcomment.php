<?php
session_start();
include "functions.php";
include "db.php";
global $db;

if (!isset($_GET["id"])) {
    header("Location: ". $_SERVER['HTTP_REFERER']);
} else {
    $id_post = $_GET["id"];
}
if (!isset($_SESSION["ID_user"])) {
    header("Location: ". $_SERVER['HTTP_REFERER']);
} else {
    $id_user = $_SESSION["ID_user"];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["newComment"])) {
        $content = $displayedcontent = "";
        if (!empty($_POST["content"])) {
            $content = test_input($_POST["content"]);
            $displayedcontent = preg_replace("/#([a-zA-Z]+[0-9]*)/", "<a class='hashtag' href='index.php?q=$1'>#$1</a>", $content);
        }

        if (empty($content) || empty($_SESSION['ID_user'])) {
            return;
        }
        
        $sql = "INSERT INTO post (ID_user, ID_post, content, displayedcontent) VALUES (?, ?, ?, ?)";
        $query = $db->prepare($sql);
        $query->execute([$id_user, $id_post, $content, $displayedcontent]);

        $sql = "SELECT username FROM user WHERE ID = ?";
        $query = $db->prepare($sql);
        $query->execute([$id_user]);
        $username = $query->fetch(PDO::FETCH_ASSOC)['username'];

        $sql = "INSERT INTO notification (ID_post, ID_user, title, content) VALUES (?, ?, ?, ?)";
        $query = $db->prepare($sql);
        $query->execute([$id_post, $id_user, "Nouveau commentaire", "Votre <a href='post.php?id=".$id_post."'>post</a> a reçu un nouveau commentaire par <a href='user.php?id=".$id_user."'>'".$username."</a>."]);

        
    }
}

    header("Location: ". $_SERVER['HTTP_REFERER']);
?>