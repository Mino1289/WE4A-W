<?php
session_start();
include "functions.php";
include "db.php";
global $db;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ID_user']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
        $ID_user = $_POST['ID_user'];

        $sql = "UPDATE user SET isAdmin = 1 WHERE ID = ?";
        $query = $db->prepare($sql);
        $query->execute([$ID_user]);

        $sql = "INSERT INTO notification (ID_user, title, content) VALUES (?, 'admin', 'Vous avez été promu administrateur')";
        $query = $db->prepare($sql);
        $query->execute([$ID_user]);
    }
}

header("Location: ". $_SERVER['HTTP_REFERER']);
?>