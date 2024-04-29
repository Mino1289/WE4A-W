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
        $ID_user = $_SESSION['ID_user'];

        echo "<div class='container'>";
        echo "<div class='row m-3 align-items-center'>";
        echo "<div class='col'><p class='ms-2'>Notifications</p></div>";
        echo "<div class='col'><button id='btn-mark-all-notif' class='btn btn-success' onclick=exeNotif(".$ID_user.",'readAll')>Marquer <strong>toutes</strong> les notifications comme lues</button></div>";
        echo "<div class='col'><button id='btn-del-all-notif' class='btn btn-danger' onclick=exeNotif(".$ID_user.",'deleteAll')>Supprimer <strong>toutes</strong> les notifications</button></div>";
        echo "</div>";
        echo "<div class='notifications'>";
        echo "<table class='table table-hover table-bordered'>";
        echo "<thead><tr>";
        echo "<th scope='col'>Titre</th>
            <th scope='col'>Message</th>
            <th scope='col'>Date</th>
            <th scope='col'>Marquer Lu</th>
            <th scope='col'>Supprimer</th>";
        echo "</tr></thead>";
        echo "<tbody id='table-body-notif' class='table-group-divider'>";

        // before selecting notifications, we delete the ones that are older than 14 days
        $sql = "DELETE FROM notification WHERE `date` < DATE_SUB(NOW(), INTERVAL 14 DAY)";
        $qry = $db->prepare($sql);
        $qry->execute();
        
        // then we select the notifications

        $sql = "SELECT * FROM notification WHERE ID_user = ? ORDER BY `date` DESC";
        $qry = $db->prepare($sql);
        $qry->execute([$ID_user]);
        $notification = $qry->fetchAll();

        foreach ($notification as $notif) {
            if ($notif['isRead'] == 0) {
                echo "<tr id='notif-".$notif['ID']."' class='table-primary notif'>";
            } else {
                echo "<tr id='notif-".$notif['ID']."' class='notif'>";
            }
            echo "<td>".$notif['title']."</td>";
            echo "<td>".$notif['content']."</td>";
            echo "<td>".$notif['date']."</td>";
            echo "<td>";
            if ($notif['isRead'] == 0) {
                echo "<button id='btn-mr-notif-".$notif["ID"]."' class='btn btn-success btn-mr-notif' onclick=exeNotif(".$notif['ID'].",'read')>Marquer Lu</button>";
            }
            echo "</td>";
            echo "<td><button id='btn-del-notif-".$notif["ID"]."' class='btn btn-danger btn-del-notif' onclick=exeNotif(".$notif['ID'].",'delete')>Supprimer</button></td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
?>

</body>
</html>