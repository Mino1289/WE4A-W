<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="./css/post.css">
</head>
<body>
    <?php
    include 'header.php';
    
    if(isset($_GET['id'])){
        include 'components/user.php';
        $sql = "SELECT MAX(ID) as max FROM user";
        $rs = $db->prepare($sql);
        $rs->execute();
        $maxID = $rs->fetch();

        if($_GET['id'] > $maxID['max']){
            echo "Utilisateur inexistant";
        } else {
            $user = userFromID($_GET['id']);
            $user->display_page();
        }
    } 
    ?>
    
    <script src="scripts/post.js"></script>
</body>
</html>