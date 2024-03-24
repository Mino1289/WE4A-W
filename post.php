<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/post.css">
</head>
<body>
    <?php
    include 'header.php';

    if(isset($_GET['id']) || isset($_POST['id'])){
        $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
        include 'components/post.php';

        $post = postFromID($_GET['id']);
        if ($post == NULL) {
            echo "<div id='acceuil'>";
            echo "<h1>Post not found</h1>";
            echo "<p>The post you are looking for does not exist.</p>";
            echo "</div>";
        } else {
            $post->display_page();
        }
    } else {
        // 404
    }
    ?>
    
</body>
</html>