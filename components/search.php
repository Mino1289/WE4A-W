
<div class="container mt-2">
<form class="d-flex" role="search" method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <input class="form-control me-2" type="search" placeholder="Search on W" aria-label="Search" name="value" maxlength="32" autocomplete="off" required>
    <button class="btn btn-outline" type="submit" value="Research" name="search">Search</button>
</form>
</div>

<?php
    include "post.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $value = "";

    if (isset($_POST["value"]) && !empty($_POST["value"])) {
        $value = test_input($_POST["value"]);
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
    $sql = "SELECT * FROM post 
            WHERE content LIKE ? AND ID_post IS NULL AND isDeleted = 0
            ORDER BY `date` DESC";

}
    $query = $db->prepare($sql);
    $query->execute(["%".$value."%"]);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $n = count($result);
    echo "<div id='search_info'><p> Found ".$n." results for '".$value."'</p></div>";
    foreach ($result as $post) {
        $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible'], $post['imageURL']);
        echo $post->display_post();
    }
// }
?>