<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <title>W | Statistics</title>
</head>
<body>

<?php

include "header.php";

    if (isset($_SESSION['ID_user'])) {?>
    <div class="container text-center">
        <h1>Statistiques</h1>
        <div class="row mx-2">
            <div class="col-4"><h3>Total des interactions</h3><canvas id="interactionstats"></canvas></div>
            <div class="col-4"></div>
            <div class="col-4"><h3>Posts par semaines et par mois</h3><canvas id="postweekstats"></canvas><canvas id="postmonthstats"></canvas></div>
        </div>
    </div>

    <script type="module">
        (async function() {
            const monthName = (month) => {
                const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                return months[month - 1];
            }

            const globaldata = <?php
                // get the number of likes received by the user
                $sql = "SELECT COUNT(*) AS likesrecieved FROM `like` WHERE `like`.`ID_post` IN (SELECT ID FROM post WHERE ID_user = ?);";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $likesrecieved = $qry->fetch(PDO::FETCH_ASSOC)['likesrecieved'];

                $sql = "SELECT COUNT(*) AS likesgiven FROM `like` WHERE `like`.`ID_user` = ?;";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $likesgiven = $qry->fetch(PDO::FETCH_ASSOC)['likesgiven'];

                $sql = "SELECT COUNT(*) AS dislikesrecieved FROM `dislike` WHERE `dislike`.`ID_post` IN (SELECT ID FROM post WHERE ID_user = ?);";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $dislikesrecieved = $qry->fetch(PDO::FETCH_ASSOC)['dislikesrecieved'];

                $sql = "SELECT COUNT(*) AS dislikesgiven FROM `dislike` WHERE `dislike`.`ID_user` = ?;";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $dislikesgiven = $qry->fetch(PDO::FETCH_ASSOC)['dislikesgiven'];

                $sql = "SELECT COUNT(*) AS followers FROM follow WHERE ID_followed = ?;";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $followers = $qry->fetch(PDO::FETCH_ASSOC)['followers'];

                $sql = "SELECT COUNT(*) AS following FROM follow WHERE ID_user = ?;";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $following = $qry->fetch(PDO::FETCH_ASSOC)['following'];


                $list = [
                    ['label' => 'Likes reçus', 'value' => $likesrecieved],
                    ['label' => 'Likes donnés', 'value' => $likesgiven],
                    ['label' => 'Dislikes reçus', 'value' => $dislikesrecieved],
                    ['label' => 'Dislikes donnés', 'value' => $dislikesgiven],
                    ['label' => 'Followers', 'value' => $followers],
                    ['label' => 'Following', 'value' => $following]
                ];

                echo json_encode($list);
            ?>
            
            new Chart(
                document.getElementById('interactionstats'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            label: ' ',
                            data: globaldata.map(x => x.value),
                            hoverOffset: 4
                        }],
                        labels: globaldata.map(x => x.label)
                    }
                }
            );

            const weeksdata = <?php
                $sql = "SELECT COUNT(*) AS posts, WEEK(date) AS week FROM post 
                        WHERE ID_user = ? AND isDeleted = 0 
                        GROUP BY week";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $weeksdata = $qry->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($weeksdata);
            ?>;

            new Chart(
                document.getElementById('postweekstats'), {
                    data: {
                        datasets: [
                            {
                                type: 'line',
                                label: 'Posts et commentaires par semaines',
                                data: weeksdata.map(x => x.posts),
                                tension: 0.1
                            }
                        ],
                        labels: weeksdata.map(x => x.week)
                    }
                }
            );

            const monthdata = <?php
                $sql = "SELECT COUNT(*) AS posts, MONTH(date) AS month FROM post 
                        WHERE ID_user = ? AND isDeleted = 0 
                        GROUP BY month";
                $qry = $db->prepare($sql);
                $qry->execute([$_SESSION['ID_user']]);
                $monthdata = $qry->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($monthdata);
            ?>;

            new Chart(
                document.getElementById('postmonthstats'), {
                    data: {
                        datasets: [
                            {
                                type: 'line',
                                label: 'Posts et commentaires par mois',
                                data: monthdata.map(x => x.posts),
                                tension: 0.1
                            }
                        ],
                        labels: monthdata.map(x => monthName(x.month))
                    }
                }
            );
            
            
        })();
    </script>
    <?php }
?>

</body>
</html>