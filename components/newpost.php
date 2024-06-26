<div class="container mb-5">
    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'])?>" method="POST" enctype="multipart/form-data">

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
                        <p>Le fichier n\'est pas une image.</p>
                    </div>';
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    echo '<div class="col">
                            <p>Désolé, votre image existe déjà.</p>
                        </div>';
                    $uploadOk = 0;
                }

                if($_FILES['postImage']['size'] >= 2000000) {
                    echo '<div class="col">
                            <p>Désolé, votre image est trop lourde (maximum 2Mo).</p>
                        </div>';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo '<div class="col">
                            <p>Désolé, votre image n\'a pas été mise en ligne.</p>
                        </div>';
                    $new_target_name = NULL;
                    // if everything is ok, try to upload file
                } else {
                    move_uploaded_file($_FILES['postImage']['tmp_name'], $target_file);
                    
                    // Get the id of the post that we will publish
                    $sql = "SELECT MAX(ID) AS max FROM Post";
                    $qry = $db->prepare($sql);
                    $qry->execute();
                    $post = $qry->fetch(PDO::FETCH_ASSOC);
                    $idNb = $post['max'] + 1;

                    // Rename file with the id of our post
                    $newName = $idNb . '.' . $imageFileType;
                    rename($target_file, $target_dir . $newName);
                    $new_target_name = $target_dir . $newName;
                }
            } else {
                $new_target_name = NULL;
            }

            $sql = "SELECT username FROM user WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$_SESSION['ID_user']]);
            $username = $query->fetch(PDO::FETCH_ASSOC)['username'];

            // Upload it to the database
            $sql = "INSERT INTO post (ID_user, content, displayedcontent, imageURL) VALUES (?, ?, ?, ?)";
            $query = $db->prepare($sql);
            $query->execute([$_SESSION['ID_user'], $content, $displayedcontent, $new_target_name]);

            $id = $db->lastInsertId();

            $sql = "SELECT * FROM follow WHERE ID_followed = ?";
            $query = $db->prepare($sql);
            $query->execute([$_SESSION['ID_user']]);
            $followers = $query->fetchAll(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO notification (ID_user, title, content) VALUES (?, ?, ?)"; // no need of ID_post it's not it's own post
            foreach ($followers as $follower) {
                $query = $db->prepare($sql);
                $query->execute([$follower['ID_user'], "Nouveau post", "Un nouvel <a href='post.php?id=".$id."'>article</a> a été publié par " . $username]);
            }
            
        }
        ?>
    </div>
</div>