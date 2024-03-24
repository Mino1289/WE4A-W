<div class="newPost">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

<div>
    <i class="fa fa-fw fa-envelope"></i>
    <label for="content"></label>
    <input type="text" name="content" maxlength="300" placeholder="De quoi voulez-vous parler ?" autocomplete="off">
</div>
<div>
    <i class="fa fa-fw fa-image"></i>
    <label for="picture"></label>
    <input type="file" name="picture" accept="image/*">
</div>
<div class="formbutton">
    <button type="submit" name="newPost">Envoyer le post</button>
</div>
</form>
</div>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newPost"])) {
    $content = "";
    if (!empty($_POST["content"])) {
        $content = test_input($_POST["content"]);
    }
}

if (empty($content) || empty($_SESSION['ID_user'])) {
    return;
}
$sql = "INSERT INTO post (ID_user, content) VALUES (?, ?)";
$query = $db->prepare($sql);
$query->execute([$_SESSION['ID_user'], $content]);
?>
