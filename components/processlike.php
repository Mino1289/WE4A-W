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
    $sql = "SELECT * FROM `like` WHERE ID_user = ? AND ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_post]);
    $like = $query->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM `dislike` WHERE ID_user = ? AND ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_post]);
    $dislike = $query->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST["like"])) {
        
        if ($like) {
            __deletefromlikedislike("like", $id_user, $id_post, $db);
        } else {
            if ($dislike) {
                __deletefromlikedislike("dislike", $id_user, $id_post, $db);
            }
            $sql = "INSERT INTO `like` (ID_user, ID_post) VALUES (?, ?)";
            $query = $db->prepare($sql);
            $query->execute([$id_user, $id_post]);
        }
    } 
    if (isset($_POST["dislike"])) {
        if ($dislike) {
            __deletefromlikedislike("dislike", $id_user, $id_post, $db);
        } else {
            if ($like) {
                __deletefromlikedislike("like", $id_user, $id_post, $db);
            }
            $sql = "INSERT INTO `dislike` (ID_user, ID_post) VALUES (?, ?)";
            $query = $db->prepare($sql);
            $query->execute([$id_user, $id_post]);
        }
    }
}

// header("Location: ../post.php?id=$id_post")
header("Location: ". $_SERVER['HTTP_REFERER']);
?>