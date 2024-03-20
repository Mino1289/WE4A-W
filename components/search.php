<?php
    include "post.php";

echo '<div id="box_google_type">

<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" class="spacing">

    <div id="search">
        <div id=""><i class="fa fa-fw fa-search" id="logosearch"></i></div>
        <input name="value" id="input" type="text" placeholder="Search on W" maxlength="32" autocomplete="off">
        
        <input type="submit" value="Research" id="submit" name="search">
    </div>

    <div id="bordure_separation"></div>
</form>
</div>';



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $value = "";
    if (!empty($_POST["value"])) {
        $value = test_input($_POST["value"]);
    }
}

if (empty($value)) {
    return;
} else {
    $sql = "SELECT * FROM post INNER JOIN user ON post.ID_user = user.ID 
            WHERE post.content LIKE ?";
}

$query = $db->prepare($sql);
$query->execute(["%".$value."%"]);
$result = $query->fetchAll(PDO::FETCH_ASSOC);

$n = count($result);
foreach ($result as $post) {
    $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['content'], $post['date'], $post['isSensible']);
    $post->display_post();
}

?>