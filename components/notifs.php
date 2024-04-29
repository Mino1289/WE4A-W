<?php
include 'db.php';
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["id"]) && !empty($_POST["id"])){
        $id = $_POST["id"];
    }

    $types = ["displayed", "delete", "read", "readAll", "deleteAll"];
    if (isset($_POST["type"]) && !empty($_POST["type"]) && in_array($_POST['type'], $types)){
        $type = $_POST["type"];
    }

    if ($type == "displayed") {
        $sql = "UPDATE `notification` SET isDisplayed=1 WHERE ID = ?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $response["success"] = true;
    } elseif ($type == "delete") {
        $sql = "DELETE FROM `notification` WHERE ID = ?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $response["success"] = true;
    } elseif ($type == "read") {
        $sql = "UPDATE `notification` SET isRead=1 AND isDisplayed=1 WHERE ID = ?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $response["success"] = true;
    } elseif ($type == "readAll") {
        $sql = "UPDATE `notification` SET isRead=1, isDisplayed=1 WHERE ID_user = ?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $response["success"] = true;
    } elseif ($type == "deleteAll") {
        $sql = "DELETE FROM `notification` WHERE ID_user = ?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $response["success"] = true;
    }
}

echo json_encode($response);
?>