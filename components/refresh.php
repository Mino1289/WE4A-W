<?php
session_start();

include "db.php";
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['ID_user'])) {
    $id_user = $_SESSION['ID_user'];
    $sql = "SELECT * FROM `notification` WHERE ID_user = ? AND isRead = 0 AND isDisplayed = 0 ORDER BY `date` DESC LIMIT 1";
    $qry = $db->prepare($sql);
    $qry->execute([$id_user]);
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT isBan FROM user WHERE ID = ?";
    $qry = $db->prepare($sql);
    $qry->execute([$id_user]);
    $isBanned = $qry->fetch(PDO::FETCH_ASSOC);

    $n = count($result);
    $response["n"] = $n;
    $response["notifications"] = $result;
    $response["success"] = true;
    $response["isBanned"] = $isBanned["isBan"];
    $response["ID_user"] = $id_user;
}  
echo json_encode($response);
?>