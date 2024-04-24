<?php
session_start();
include "functions.php";
include "db.php";
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_post"])) {
        $id_post = $_POST["id_post"];
    }
    if (isset($_POST["type"])) {
        $type = $_POST["type"];
    }
    if (isset($_SESSION["ID_user"])) {
        $id_user = $_SESSION["ID_user"];
    }

    $sql = "SELECT * FROM `like` WHERE ID_user = ? AND ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_post]);
    $like = $query->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM `dislike` WHERE ID_user = ? AND ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_post]);
    $dislike = $query->fetch(PDO::FETCH_ASSOC);


    if ($type == "like") {
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
    if ($type == "dislike") {
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
    $sql = "SELECT COUNT(*) AS n FROM `like` WHERE ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_post]);
    $response["likes"] = $query->fetch(PDO::FETCH_ASSOC)["n"];

    $sql = "SELECT COUNT(*) AS n FROM `dislike` WHERE ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_post]);
    $response["dislikes"] = $query->fetch(PDO::FETCH_ASSOC)["n"];

    $response['success'] = true;
}
echo json_encode($response);
?>