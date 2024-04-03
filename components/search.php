<link rel="stylesheet" href="./css/search.css">

<form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" class="spacing">

    <div id="search">
        <div><i class="fa fa-fw fa-search" id="logosearch"></i></div>
        <input name="value" id="input" type="text" placeholder="Search on W" maxlength="32" autocomplete="off">
        
        <input type="submit" value="Research" id="submit" name="search">
    </div>

</form>

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
            WHERE post.content LIKE ? AND post.ID_post IS NULL AND post.isDeleted = 0
            ORDER BY post.date DESC";
}

$query = $db->prepare($sql);
$query->execute(["%".$value."%"]);
$result = $query->fetchAll(PDO::FETCH_ASSOC);

$n = count($result);
echo "<div id='search_info'><p> Found ".$n." results for '".$value."'</p></div>";
foreach ($result as $post) {
    $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible']);
    $post->display_post();
}

?>