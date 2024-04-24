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
        $sql = "SELECT isSensible FROM post WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $result = $qry->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE post SET isSensible=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([($result['isSensible'] == '1') ? 0 : 1, $id]);
        
        $response["success"] = true;
    } elseif ($type == "user") {
        $sql = "SELECT isWarn FROM user WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $result = $qry->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE user SET isWarn=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([($result['isWarn'] == '1') ? 0 : 1, $id]);
        $response["success"] = true;
    }
}

echo json_encode($response);
?>