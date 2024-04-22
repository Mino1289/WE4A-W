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
    include "components/post.php";

    if (isset($_SESSION['ID_user'])) {
        $sql = "SELECT * FROM post 
            WHERE ID_post IS NULL AND 
                isDeleted = 0 AND 
                ID_user = ANY (SELECT ID_followed FROM follow WHERE ID_user = ?)
            ORDER BY date DESC";
        $query = $db->prepare($sql);
        $query->execute([$_SESSION['ID_user']]);
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);

        echo '<div class="text-center">
        <div class="row">
          <div class="col align-self-center">
            <p class="m-3">Les posts des personnes que vous suivez.<p>
          </div>
        </div></div>';
        echo "<div class='container'>";
            foreach ($posts as $post) {
                $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible']);
                $post->display_post();
            }
        echo "</div>";
    }
?>

</body>
</html>