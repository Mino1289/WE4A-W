<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W</title>
</head>
<body>
    <style>
        div.warned, div.sensible {
            filter: blur(5px);
            transition: .3s;
        }

        div.warned:hover, div.sensible:hover {
            filter: none;
            transition: .3s;
        }
    </style>
<?php
    include "header.php";

    if (isset($_SESSION['ID_user'])) {
        // create a search bar
        include "components/search.php";
        
        // create a post form
        
        if (!isset($_POST["search"]) && !isset($_GET["q"])) {
            include "components/newpost.php";
            // display the posts
            $sql = "SELECT ID FROM post WHERE ID_post IS NULL AND isDeleted = 0 ORDER BY date DESC";
            $query = $db->prepare($sql);
            $query->execute();
            $ids = $query->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='container'>";
            foreach ($ids as $id) {
                $post = postFromID($id["ID"]);
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