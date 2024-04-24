<?php
include 'db.php';
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["id"]) && !empty($_POST["id"])){
        $id = $_POST["id"];
    }

    $types = ["post", "user"];
    if(isset($_POST["type"]) && !empty($_POST["type"]) && in_array($_POST['type'], $types)){
        $type = $_POST["type"];
    }

    if ($type == "post"){
        $sql = "UPDATE post SET isDeleted=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([1, $id]);
        $response["success"] = true;
    } elseif ($type == "user") {
        $sql = "DELETE FROM user WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $response["success"] = true;
    }
}

echo json_encode($response);
?>