<?php
include 'db.php';
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["id"]) && !empty($_POST["id"])){
        $id = $_POST["id"];
    } else {
        $response["error"] = "ID is required";
    }

    $types = ["post", "user"];
    if (isset($_POST["type"]) && !empty($_POST["type"]) && in_array($_POST['type'], $types)){
        $type = $_POST["type"];
    } else {
        $response["error"] = "Type is required";
    }

    if (isset($_POST["title"]) && !empty($_POST["title"])){
        $title = htmlspecialchars($_POST["title"]);
    } else {
        $response["error"] = "Title is required";
    }

    if (isset($_POST["content"]) && !empty($_POST["content"])){
        $content = htmlspecialchars($_POST["content"]);
    } else {
        $response["error"] = "Content is required";
    }

    if (isset($response["error"])){
        echo json_encode($response);
        return;
    }
    
    if ($type == "post"){
        $sql = "SELECT isSensible FROM post WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $result = $qry->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE post SET isSensible=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([($result['isSensible'] == '1') ? 0 : 1, $id]);

        // notify user
        $sql = "INSERT INTO `notification`(ID_post, ID_user, title, content) VALUES(?, (SELECT ID_user FROM post WHERE ID=?), ?, ?)";
        $qry = $db->prepare($sql);
        $qry->execute([$id, $id, $title, $content]);

        $response["success"] = true;
    } elseif ($type == "user") {
        $sql = "SELECT isWarn FROM user WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $result = $qry->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE user SET isWarn=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([($result['isWarn'] == '1') ? 0 : 1, $id]);

        // notify user
        
        $sql = "INSERT INTO `notification`(ID_user, title, content) VALUES(?, ?, ?)";
        $qry = $db->prepare($sql);
        $qry->execute([$id, $title, $content]);

        $response["success"] = true;
    }
}

echo json_encode($response);
?>