<?php
include 'db.php';
global $db;

if(isset($_GET["id"]) && !empty($_GET["id"])){
    $id = $_GET["id"];
} else {
    header("Location: ../index.php");
}

$types = ["post", "user"];
if(isset($_GET["type"]) && !empty($_GET["type"]) && in_array($_GET['type'], $types)){
    $type = $_GET["type"];
} else {
    header("Location: ../index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if ($type == "post"){
        $sql = "SELECT isSensible FROM post WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $result = $qry->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE post SET isSensible=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([($result['isSensible'] == '1') ? 0 : 1, $id]);
        
    } elseif ($type == "user") {
        $sql = "SELECT isWarn FROM user WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$id]);
        $result = $qry->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE user SET isWarn=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([($result['isWarn'] == '1') ? 0 : 1, $id]);
    }
}
header("Location: ../index.php");
?>