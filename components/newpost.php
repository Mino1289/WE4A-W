<div class="newPost">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

<div>
    <i class="fa fa-fw fa-envelope"></i>
    <label for="content"></label>
    <textarea type="text" name="content" maxlength="300" placeholder="De quoi voulez-vous parler ?" autocomplete="off"></textarea>
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
    $content = $displayedcontent = "";
    if (!empty($_POST["content"])) {
        $content = test_input($_POST["content"]);
        $displayedcontent = preg_replace("/#([a-zA-Z]+[0-9]*)/", "<a class='hashtag' href='index.php?q=$1'>#$1</a>", $content);
    }

    if (empty($content) || empty($_SESSION['ID_user'])) {
        return;
    }
    $sql = "INSERT INTO post (ID_user, content, displayedcontent) VALUES (?, ?, ?)";
    $query = $db->prepare($sql);
    $query->execute([$_SESSION['ID_user'], $content, $displayedcontent]);
}
?>
