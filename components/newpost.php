<div class="container mb-5">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="newPostTextArea" class="form-label"></label>
            <textarea class="form-control" placeholder="De quoi voulez-vous parler ?" id="newPostTextArea" maxlength="300" name="content" rows="3" autocomplete="off" required></textarea>
        </div>
        <div class="row g-3">

            <div class="col-auto">
                <input class="form-control" type="file" id="postImage" name="postImage" accept="image/*">
            </div>
            <div class="col-auto">
                <button class="form-control btn btn-primary" type="submit" name="newPost" id="newPostSubmit">Envoyer le post</button>
            </div>
        </div>
    </form>
    <div class="row text-body-secondary">
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

            $uploadOk = 1; // Check for the image uploaded
            if (isset($_FILES['postImage']) && $_FILES['postImage']['size'] != 0) {
                $target_dir = "imgPost/";
                $target_file = $target_dir . basename($_FILES['postImage']['name']);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES['postImage']['tmp_name']);

                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo '<div class="col">
                        <p>File is not an image.</p>
                    </div>';
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    echo '<div class="col">
                            <p>Sorry, file already exists. </p>
                        </div>';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo '<div class="col">
                            <p>Sorry, your file was not uploaded.</p>
                        </div>';
                    // if everything is ok, try to upload file
                } else {
                    move_uploaded_file($_FILES['postImage']['tmp_name'], $target_file);
                    
                    // Get the id of the post that we will publish
                    $sql = "SELECT MAX(ID) AS max FROM Post";
                    $qry = $db->prepare($sql);
                    $qry->execute();
                    $post = $qry->fetch(PDO::FETCH_ASSOC);
                    $idNb = $post['max'] + 1;
                    echo $idNb;

                    // Rename file with the id of our post
                    $newName = $idNb . '.' . $imageFileType;
                    rename($target_file, $target_dir . $newName);
                    $new_target_name = $target_dir . $newName;
                }
            } else {
                $new_target_name = NULL;
            }


            // Upload it to the database
            $sql = "INSERT INTO post (ID_user, content, displayedcontent, imageURL) VALUES (?, ?, ?, ?)";
            $query = $db->prepare($sql);
            $query->execute([$_SESSION['ID_user'], $content, $displayedcontent, $new_target_name]);
        }
        ?>
    </div>
</div>