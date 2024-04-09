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
    <div class="row mx-2">
    <div class="col"><canvas id="daystat"></canvas></div>
    <div class="col"><canvas id=""></canvas></div>
    </div>
    <script type="module">
        const daystatdata = <?php
                $sql = "SELECT DATE(`date`) AS `date`, COUNT(*) AS count, ISNULL(ID_post) as isComment FROM post WHERE ID_user = ? AND isDeleted = 0 GROUP BY DATE(`date`), ISNULL(ID_post)";
                $query = $db->prepare($sql);
                $query->execute([$_SESSION['ID_user']]);
                $acquisitions = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($acquisitions);
            ?>;

        new Chart(
            document.getElementById('daystat'), {
                data: {
                    labels: daystatdata.map(row => row.date),
                    datasets: [
                        {
                            label: 'Posts per Day',
                            data: daystatdata.filter(row => !row.isComment).map(row => row.count),
                            type: 'bar',
                        },
                        {
                            label: 'Comments per Day',
                            data: daystatdata.filter(row => row.isComment).map(row => row.count),
                            type: 'bar',
                            color: 'red',
                        }
                    ]
                }
            }
        );
    </script>
    <?php }
?>

</body>
</html>