<?php
session_start();
include "functions.php";
include "db.php";
global $db;


$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["ID_follow"])) {
        $sql = "DELETE FROM follow WHERE ID = ?";
        $query = $db->prepare($sql);
        $query->execute([$_POST["ID_follow"]]);

        $response["success"] = true;
        $response['following'] = false;
        echo json_encode($response);
        return;
    }

    if (!isset($_POST["ID_user"])) {
        $response["error"] = "ID_user is required";
        echo json_encode($response);
        return;
    } else {
        $id_userpage = $_POST["ID_user"];
    }

    if (!isset($_SESSION['ID_user'])) {
        $response["error"] = "You must be logged in to follow someone";
        echo json_encode($response);
        return;
    } else {
        $id_user = $_SESSION['ID_user'];
    }

    if ($id_user == $id_userpage) {
        $response["error"] = "You can't follow yourself";
        echo json_encode($response);
        return;
    }

    $sql = "SELECT * FROM follow WHERE ID_user = ? AND ID_followed = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_userpage]);
    $follow = $query->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT username FROM user WHERE ID = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user]);
    $username = $query->fetch(PDO::FETCH_ASSOC)["username"];

    if ($follow) {
        $sql = "DELETE FROM follow WHERE ID_user = ? AND ID_followed = ?";
        $query = $db->prepare($sql);
        $query->execute([$id_user, $id_userpage]);

        $responde['followID'] = $follow['ID'];
        $response['following'] = false;

        $sql = "INSERT INTO notification (ID_user, title, content) VALUES (?, 'Follow', '<a href=\'user.php?id=".$id_user."\'>".$username."</a> à commencé à vous suivre.')";
        $query = $db->prepare($sql);
        $query->execute([$id_userpage]);

    } else {
        $sql = "INSERT INTO follow (ID_user, ID_followed) VALUES (?, ?)";
        $query = $db->prepare($sql);
        $query->execute([$id_user, $id_userpage]);

        $response['following'] = true;
        $response['followID'] = $db->lastInsertId();
    }
    $response["success"] = true;
}

echo json_encode($response);
?>