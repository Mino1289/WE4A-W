<?php
session_start();
include 'post.php';
include 'db.php';
global $db;

$response = array("success" => false);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["start"])) {
        $start = $_POST["start"];
    } else {
        $start = 0;
    }
    $types = ["index", "fil", "trending", "user"];
    if (isset($_POST["type"]) && !empty($_POST["type"]) && in_array($_POST['type'], $types)){
        $type = $_POST["type"];
    } else {
        $response["error"] = "Type is required";
    }

    
    if (isset($response["error"])){
        echo json_encode($response);
        return;
    }
    
    if ($type == "index") {
        $sql = "SELECT ID FROM post 
            WHERE ID_post IS NULL AND 
                isDeleted = 0 
            ORDER BY date DESC 
            LIMIT 5 OFFSET $start";
    } elseif ($type == "fil") {
        $sql = "SELECT ID FROM post 
        WHERE ID_post IS NULL AND 
            isDeleted = 0 AND 
            ID_user = ANY (SELECT ID_followed FROM follow WHERE ID_user = ?)
        ORDER BY date DESC LIMIT 5 OFFSET $start";
    } elseif ($type == "trending") {
        $sql = "SELECT `like`.ID_post AS ID FROM `like` 
            INNER JOIN post ON post.ID = `like`.`ID_post` 
            WHERE post.ID_post IS NULL
                AND post.isDeleted = 0 
            GROUP BY `like`.ID_post 
            ORDER BY COUNT(*) DESC
            LIMIT 5 OFFSET $start";
    } elseif ($type == "user") {
        $sql = "SELECT ID FROM post 
            WHERE ID_post IS NULL AND 
                isDeleted = 0 AND 
                ID_user = ?
            ORDER BY date DESC 
            LIMIT 5 OFFSET $start";
    }
    $query = $db->prepare($sql);
    if ($type == "fil" || $type == "user") {
        $query->execute([$_SESSION['ID_user']]);
    } else {
        $query->execute();
    }
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (empty($posts)) {
        echo json_encode($response);
        return;
    }
    $response["success"] = true;
    foreach ($posts as $post) {
        $response["posts"][] = postFromID($post["ID"])->display_post();
    }    
}

echo json_encode($response);
?>