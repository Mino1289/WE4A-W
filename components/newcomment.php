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
    }
}

    header("Location: ". $_SERVER['HTTP_REFERER']);
?>