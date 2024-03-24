<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>W</title>
</head>
<body>
<?php
    include "header.php";

    if (isset($_SESSION['ID_user'])) {
        // create a search bar
        include "components/search.php";
        // create a post form

        
    }
    else {
        echo "<div id='acceuil'>";
        echo "<h1>Welcome to W!</h1>";
        echo "<p>W is a social network where you can share your thoughts with the world.</p>";
        echo "<p>You need to be connected to see the posts.</p>";
        echo "</div>";
    }
?>

</body>
</html>