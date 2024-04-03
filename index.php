<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/post.css">
    <title>W</title>
</head>
<body>
<?php
    include "header.php";

    if (isset($_SESSION['ID_user'])) {
        // create a search bar
        include "components/search.php";
        
        // create a post form
        
        if (!isset($_POST["search"]) && !isset($_GET["q"])) {
            include "components/newpost.php";
            // display the posts
            $sql = "SELECT * FROM post WHERE ID_post IS NULL AND isDeleted = 0 ORDER BY date DESC";
            $query = $db->prepare($sql);
            $query->execute();
            $posts = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as $post) {
                $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible']);
                $post->display_post();
            }
        }
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