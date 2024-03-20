<?php
    class Post {
        public $ID;
        public $ID_user;
        public $ID_post;
        public $content;
        public $date; 
        public $isSensible;
        public $likes;
        public $dislikes;

        function __construct($ID, $ID_user, $ID_post, $content, $date, $isSensible) {
            $this->ID = $ID;
            $this->ID_user = $ID_user;
            $this->ID_post = $ID_post;
            $this->content = $content;
            $this->date = $date;
            $this->isSensible = $isSensible;
            
            global $db;

            $sql = "SELECT COUNT(*) FROM like WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $this->likes = $query->fetch(PDO::FETCH_ASSOC);
            $this->likes = $this->likes['COUNT(*)'];

            $sql = "SELECT COUNT(*) FROM dislike WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $this->dislikes = $query->fetch(PDO::FETCH_ASSOC);
            $this->dislikes = $this->dislikes['COUNT(*)'];
            
        }

        function display_post() {
            global $db;

            $sql = "SELECT username, profile_picture, isWarn FROM user WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            
            echo "<div class='post".($user["isWarn"]) ? " warning": ""."'>";
            echo "<p class='username'>".$user["username"]."</h1>";
            echo "<div class='information_post'>";
            echo "<ul>";
            echo "<p>Content : ".$this->content."</p>";
            echo "<p>Date : ".$this->date."</p>";
            echo "<p>isSensible : ".$this->isSensible."</p>";
            echo "<p>W : ". $this->likes ."</p>"; //TODO: add a small form that likes/dislike the post/comment (get the unique id)
            echo "<p>L : ". $this->dislikes ."</p>"; //TODO: add a small form that likes/dislike the post/comment (get the unique id)
            echo "</ul></div>";
            // Add a form to comment the post/comment
        }

        function display_page() {
            $this->display_post();
            global $db;

            $sql = "SELECT * FROM post WHERE ID_post = ? ORDER BY date DESC";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='comment'>";
            foreach ($comments as $comment) {
                $comment = new Post($comment['ID'], $comment['ID_user'], $comment['ID_comment'], $comment['content'], $comment['date'], $comment['isSensible']);
                $comment->display_post();
            }
            echo "</div>";
        }
    }

    function postFromID($ID) {
        global $db;

        $sql = "SELECT * FROM post WHERE ID = ?";
        $query = $db->prepare($sql);
        $query->execute([$ID]);
        $post = $query->fetch(PDO::FETCH_ASSOC);
        if (!$post) {
            return null;
        }

        return new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['content'], $post['date'], $post['isSensible']);
    }
?>