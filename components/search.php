<div class="container mt-2">
    <form role="search" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <div class="row">
            <div class="col-8">
                <input class="form-control me-2" type="search" placeholder="Chercher sur W" aria-label="Search" name="value" maxlength="32" autocomplete="off" required>
            </div>
            <div class="col mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="searchPost" value="post" checked>
                    <label class="form-check-label" for="searchPost">Post</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="searchUsers" value="user">
                    <label class="form-check-label" for="searchUsers">User</label>
                </div>
            </div>
            <div class="col">
                <button class="btn btn-outline-secondary" type="submit" value="Research" name="search">Chercher</button>
            </div>
        </div>
    </form>
</div>

<?php
include "post.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $value = "";

    if (isset($_POST["value"]) && !empty($_POST["value"])) {
        $value = test_input($_POST["value"]);
    }

    $types = ["post", "user"];
    if (isset($_POST["type"]) && !empty($_POST["type"]) && in_array($_POST['type'], $types)) {
        $type = test_input($_POST["type"]);
    }
} else {
    $value = "";
    if (isset($_GET["q"]) && !empty($_GET["q"])) {
        $value = test_input($_GET["q"]);
    }
}


if (empty($value)) {
    return;
} else {
    if ($type == "user") {
        $sql = "SELECT * FROM user 
                WHERE username LIKE ?";

        $query = $db->prepare($sql);
        $query->execute(["%" . $value . "%"]);
        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        $n = count($users);
        echo "<div id='search_info' class='mt-3'><p class='text-center'> " . $n . " résultats ont été trouvé pour '" . $value . "'</p></div>";

        echo "<div class='container text-center'>";
        echo "<div class='usearch m-3'>";
        echo "<table class='table table-hover table-bordered'>";
        echo "<thead><tr>";
        echo "
        <th scope='col'>Pseudo</th>
        ";
        echo "</tr></thead>";
        echo "<tbody id='table-body-notif' class='table-group-divider'>";


        foreach ($users as $user) {
            echo "<tr id='user-" . $user["ID"] . "'>";
            echo "<td><a href='user.php?id=" . $user["ID"] . "'>" . $user['username'] . "</a></td>";
            $sql = "SELECT * FROM follow WHERE ID_user = ? AND ID_followed = ?";
            $query = $db->prepare($sql);
            $query->execute([$_SESSION['ID_user'], $user['ID']]);
            $follow = $query->fetch(PDO::FETCH_ASSOC);
            if ($user['ID'] != $_SESSION['ID_user']) {
                if ($follow) {
                    echo "<td><button class='btn btn-danger' id='follow-btn-" . $user["ID"] . "' onclick='follow(" . $user["ID"] . ")'>Ne plus suivre</button></td>";
                } else {
                    echo "<td><button class='btn btn-success' id='follow-btn-" . $user["ID"] . "' onclick='follow(" . $user["ID"] . ")'>Suivre</button></td>";
                }
            } else {
                echo "<td></td>";
            }
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div></div>";
    } elseif ($type == "post") {
        $sql = "SELECT * FROM post 
                WHERE content LIKE ? AND ID_post IS NULL AND isDeleted = 0
                ORDER BY `date` DESC";

        $query = $db->prepare($sql);
        $query->execute(["%" . $value . "%"]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $n = count($result);
        echo "<div id='search_info' class='mt-3'><p class='text-center'> " . $n . " résultats ont été trouvé pour '" . $value . "'.</p></div>";
        foreach ($result as $post) {
            $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible'], $post['isDeleted'], $post['imageURL']);
            echo $post->display_post();
        }
    }
}
?>