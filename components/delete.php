<?php
include 'db.php';
global $db;

if(isset($_GET["id"]) && !empty($_GET["id"])){
    $id = $_GET["id"];
} else {
    header("Location: ". $_SERVER['HTTP_REFERER']);
}

$types = ["post", "user"];
if(isset($_GET["type"]) && !empty($_GET["type"]) && in_array($_GET['type'], $types)){
    $type = $_GET["type"];
} else {
    header("Location: ". $_SERVER['HTTP_REFERER']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if ($type == "post"){
        $sql = "UPDATE post SET isDeleted=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([1, $id]);

    } elseif ($type == "user") {
        $sql = "DELETE FROM user WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
    }
}
header("Location: ". $_SERVER['HTTP_REFERER']);
?>