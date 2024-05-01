<?php
session_start();
include "functions.php";
include "db.php";
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["id"])) {
        $response["error"] = "ID is required";
    } else {
        $id_userpage = $_POST["id"];
    }
    if (!isset($_SESSION["ID_user"])) {
        $response["error"] = "You must be logged in to follow someone";
    } else {
        $id_user = $_SESSION["ID_user"];
    }

    if ($id_user == $id_userpage) {
        $response["error"] = "You can't follow yourself";
    }

    if (isset($response["error"])) {
        echo json_encode($response);
        return;
    }

    $sql = "SELECT * FROM follow WHERE ID_user = ? AND ID_followed = ?";
    $query = $db->prepare($sql);
    $query->execute([$id_user, $id_userpage]);
    $follow = $query->fetch(PDO::FETCH_ASSOC);
    if ($follow) {
        $sql = "DELETE FROM follow WHERE ID_user = ? AND ID_followed = ?";
        $query = $db->prepare($sql);
        $query->execute([$id_user, $id_userpage]);
        $response['following'] = false;
    } else {
        $sql = "INSERT INTO follow (ID_user, ID_followed) VALUES (?, ?)";
        $query = $db->prepare($sql);
        $query->execute([$id_user, $id_userpage]);
        $response['following'] = true;
    }
    $response["success"] = true;
}

echo json_encode($response);
?>