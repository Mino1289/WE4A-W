<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    include 'header.php';
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        include 'components/user.php';
        $sql = "SELECT ID FROM user WHERE ID = ?";
        $rs = $db->prepare($sql);
        $rs->execute([$id]);
        $exist = $rs->fetch();

        if(!$exist){
            echo "Utilisateur inexistant";
        } else {
            $user = userFromID($id);
            $user->display_page();
        }
    } 
    ?>
<script>
    loadPosts("user");
    window.addEventListener('scroll', function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
            loadPosts("user");
        }
    });
</script>
</body>
</html>