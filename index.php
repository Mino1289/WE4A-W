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

            echo '<div id="posts"></div>';
        }
        ?>
<script defer>
    loadPosts("index");
    window.addEventListener('scroll', function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
            loadPosts("index"); // <- fonction à faire qui call du ajax
        }
        displayBlurBtn();
    });
</script>
<?php
    } else {
        echo '<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Bienvenue sur W, le reseau social de la Win, mais on tolère aussi les Loosers...</h5>
            <div class="row">
                <div class="col">
                    <p class="card-text">
                        <ul>
                            <li>Connectez-vous pour voir les posts de vos amis</li>
                            <li>Partagez vos moments de gloire</li>
                            <li>Likez et commentez les posts de vos amis</li>
                        </ul>
                    </p>
                </div>
                <div class="col">
                    <p>
                    <ul>
                        <li><button type="button" class="btn btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#registerModal">Pas de compte ?</button></li>
                        <li><button type="button" class="btn btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#loginModal">Déjà un compte ?</button></li>
                    </p>
                </div>
            </div>
          <p class="card-text"><small class="text-body-secondary"></small></p>
        </div>
      </div>';
    }
?>

</body>
</html>