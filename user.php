<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    include 'header.php';

    if(isset($_GET['id'])){
        include 'components/user.php';

        $user = userFromID($_GET['id']);
        $user->display_page();
    } 
    ?>
    
</body>
</html>