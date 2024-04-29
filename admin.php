<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
include "header.php";


if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $sql= "UPDATE USER SET isWarn=1 WHERE id_user=?";
    $qry = $db->prepare($sql);
    $qry->execute([$id]);
    $verifPassword =$qry->fetch();
}
?>

