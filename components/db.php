<?php
    define("HOST", "localhost");
    define("DB_NAME", "w");
    define("USER", "root");
    define("PASSWORD", "");

    try {
        $db = new PDO("mysql:host=".HOST.";dbname=".DB_NAME.";charset=utf8", USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : ".$e->getMessage();
    }

?>