<div class="container mb-5">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

    <div class="mb-3">
        <label for="newPostTextArea" class="form-label"></label>
        <textarea class="form-control" placeholder="De quoi voulez-vous parler ?" id="newPostTextArea" maxlength="300" name="content" rows="3" autocomplete="off"></textarea>
    </div>
    <div class="row g-3">

        <div class="col-auto">
            <input class="form-control" type="file" id="newPostImage" accept="image/*">
        </div>
        <div class="col-auto">
            <button class="form-control btn btn-primary" type="submit" name="newPost" id="newPostSubmit">Envoyer le post</button>
        </div>
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
