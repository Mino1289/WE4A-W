<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <!-- <link rel="stylesheet" href="css/post.css"> -->
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

            echo "<div class='container'>";
            foreach ($posts as $post) {
                $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible']);
                $post->display_post();
            }
            echo "</div>";
        }
    }
    else {
        echo '<div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
          <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
        </div>
      </div>';
    }
?>

</body>
</html>