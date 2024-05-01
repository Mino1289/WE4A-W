<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W</title>
</head>
<body>
<?php
    include "header.php";

    if (isset($_SESSION['ID_user'])) {
        
        $sql = "SELECT follow.ID, follow.ID_user, follow.ID_followed, username FROM follow 
        INNER JOIN user ON follow.ID_followed = user.ID
        WHERE follow.ID_user = ?";
        $query = $db->prepare($sql);
        $query->execute([$_SESSION['ID_user']]);
        $followed = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($followed) == 0) {
            echo "<div class='container'>";
            echo "<div class='suivis m-3'>";
            echo "<h2>Vous ne suivez personne</h2>";
            echo "</div></div>";
        } else {

            echo "<div class='container'>";
            echo "<div class='suivis m-3'>";
            echo "<table class='table table-hover table-bordered'>";
            echo "<thead><tr>";
            echo "
            <th scope='col'>Pseudo</th>
            <th scope='col'>Unfollow</th>
            ";
            echo "</tr></thead>";
            echo "<tbody id='table-body-notif' class='table-group-divider'>";
            

            
            
            foreach ($followed as $follow) {
                echo "<tr id='follow-".$follow["ID"]."'>";
                echo "<td><a href='user.php?id=".$follow["ID_user"]."'>".$follow['username']."</a></td>";
                echo "<td><button class='btn btn-danger btn-unfollow' onclick=unfollow(".$follow['ID'].")>Unfollow</button></td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div></div>";
        }
    }
?>

</body>
</html>