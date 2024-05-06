<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W | Mon Fil</title>
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
    include "components/post.php";

    if (isset($_SESSION['ID_user'])) {

        echo '<div class="text-center">
        <div class="row">
          <div class="col align-self-center">
            <p class="m-3">Les posts des personnes que vous suivez.<p>
          </div>
        </div></div>';
        echo "<div id='posts' class='container'>";
        echo "</div>";
    }
?>

<script defer>
loadPosts("fil");
window.addEventListener('scroll', function () {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        loadPosts("fil");
        displayBlurBtn();
    }
    displayBlurBtn();
});
</script>
</body>
</html>