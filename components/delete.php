<?php
include 'db.php';
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["id"]) && !empty($_POST["id"])){
        $id = $_POST["id"];
    }

    $types = ["post", "user"];
    if (isset($_POST["type"]) && !empty($_POST["type"]) && in_array($_POST['type'], $types)){
        $type = $_POST["type"];
    }

    if (isset($_POST["title"]) && !empty($_POST["title"])) {
        $title = $_POST["title"];
    }

    if (isset($_POST["content"]) && !empty($_POST["content"])) {
        $content = $_POST["content"];
    }

    if ($type == "post"){
        $sql = "UPDATE post SET isDeleted=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([1, $id]);
        // notify user
        $sql = "INSERT INTO `notification`(ID_post, ID_user, title, content) VALUES(?, (SELECT ID_user FROM post WHERE ID=?), ?, ?)";
        $qry = $db->prepare($sql);
        $qry->execute([$id, $id, $title, $content]);
         
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