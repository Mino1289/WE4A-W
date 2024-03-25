<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT * FROM `like` WHERE ID_user = ? AND ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$this->ID_user, $this->ID]);
    $like = $query->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM `dislike` WHERE ID_user = ? AND ID_post = ?";
    $query = $db->prepare($sql);
    $query->execute([$this->ID_user, $this->ID]);
    $dislike = $query->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST["like"])) {
        
        if ($like) {
            __deletefromlikedislike("like", $this->ID_user, $this->ID, $db);
        } else {
            if ($dislike) {
                __deletefromlikedislike("dislike", $this->ID_user, $this->ID, $db);
            }
            $sql = "INSERT INTO `like` (ID_user, ID_post) VALUES (?, ?)";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user, $this->ID]);
        }
    } 
    if (isset($_POST["dislike"])) {
        if ($dislike) {
            __deletefromlikedislike("dislike", $this->ID_user, $this->ID, $db);
        } else {
            if ($like) {
                __deletefromlikedislike("like", $this->ID_user, $this->ID, $db);
            }
            $sql = "INSERT INTO `dislike` (ID_user, ID_post) VALUES (?, ?)";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user, $this->ID]);
        }
    }
}

header("Location: post.php?id=$this->ID")
?>