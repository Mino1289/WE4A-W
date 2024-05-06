<?php
session_start();
include "db.php";


$id = $_SESSION['ID_user'];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST["username"]) && $_POST["username"] != null) {
        $sql = "UPDATE user SET username=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["username"], $id]);
    }
    if (isset($_POST["firstName"]) && $_POST["firstName"] != null) {
        $sql = "UPDATE user SET first_name=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["firstName"], $id]);
    }
    if (isset($_POST["lastName"]) && $_POST["lastName"] != null) {
        $sql = "UPDATE user SET last_name=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["lastName"], $id]);
    }
    if (isset($_POST["email"]) && $_POST["email"] != null) {
        $sql = "UPDATE user SET email=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["email"], $id]);
    }
    if (isset($_POST["birthDate"]) && $_POST["birthDate"] != null) {
        $sql = "UPDATE user SET birth_date=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["birthDate"], $id]);
    }
    if (isset($_POST["adress"]) && $_POST["adress"] != null) {
        $sql = "UPDATE user SET adress=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["adress"], $id]);
    }
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['size'] != 0) {
        $profile_picture = file_get_contents($_FILES["photo_profil"]["tmp_name"]);
        $sql = "UPDATE user SET profile_picture = ? WHERE ID = ?";
        $qry = $db->prepare($sql);
        $qry->execute([$profile_picture, $id]); // ID de l'user
        $_SESSION['profile_picture'] = $profile_picture;
    }
    if (isset($_POST["changePassword"]) && $_POST["changePassword"] != null) {
        $sql = "UPDATE user SET password=? WHERE ID=?";
        $qry = $db->prepare($sql);
        $qry->execute([$_POST["changePassword"], $id]);
    }
    echo "<script> location.href='../settings.php' </script>";
}