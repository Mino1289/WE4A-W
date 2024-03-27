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

            $sql = "SELECT COUNT(*) AS n FROM `like` WHERE `like`.ID_post = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $this->likes = $query->fetch(PDO::FETCH_ASSOC);
            $this->likes = $this->likes['n'];

            $sql = "SELECT COUNT(*) AS n FROM dislike WHERE `dislike`.ID_post = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            // echo query with inserted values
            
            $this->dislikes = $query->fetch(PDO::FETCH_ASSOC);
            $this->dislikes = $this->dislikes['n'];
            
        }

        function display_post() {
            global $db;

            $sql = "SELECT username, profile_picture, isWarn FROM user WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            
            echo "<div class='";
            if ($this->ID_post != null) {
                echo "comment";
            }
            else {
                echo "post";
            }
            if ($user['isWarn'] != 0) {
                echo " warned";
            }
            if ($this->isSensible == '1') {
                echo " sensible";
            }
            echo "'>";
            $img = base64_encode($user['profile_picture']);
            echo '<div class="post_user">';
            echo '<img class="post_user_pp" alt="pp" src="data:image/png;base64,'.$img.'">';
            echo "<a href='user.php?id=".$this->ID_user."' class='username'>".$user["username"]."</a>";
            if ($_SESSION["isAdmin"]) {

                echo"<form action='components/warn.php?id=".$this->ID_user."&type=user' method='POST'>";
                echo "<button type='submit' name='sensible'>";
                if($user['isWarn'] == 0){
                    echo "warn";
                } else {
                    echo "unwarn";
                }
                echo " user</button></form>";

                echo"<form action='components/warn.php?id=".$this->ID."&type=delete' method='POST'>";
                echo "<button type='submit' name='sensible'>";
                echo " Delete post </button></form>";
                
            }
            echo "</div>";
            echo "<div class='information_post'>";
            echo "<div>";
            echo "<p>Content : ".$this->content."</p>";
            echo "<p>Date : ".$this->date."</p>";
            
            if ($_SESSION["isAdmin"]) {

                echo"<form action='components/warn.php?id=".$this->ID."&type=post' method='POST'>";
                echo "<button type='submit' name='sensible'>";
                if($this->isSensible == 0){
                    echo "M";
                } else {
                    echo "Unm";
                }
                echo "ark Sensible</button></form>";
                
            }

            //TODO: add a small form that likes/dislike the post/comment (get the unique id)
            echo "<p>W : ". $this->likes ."</p>
            <form action='components/processlike.php?id=".$this->ID."' method='POST'>
            <button type='submit' name='like'>W</button>
            </form>"; 
            //TODO: add a small form that likes/dislike the post/comment (get the unique id)
            echo "<p>L : ". $this->dislikes ."</p>
            <form action='components/processlike.php?id=".$this->ID."' method='POST'>
            <button type='submit' name='dislike'>L</button>
            </form>";

            echo "<a href='post.php?id=".$this->ID."'>Voir le post</a><br>";
            // echo "<a href='newcomment.php?id=".$this->ID."'>Commenter</a>";
            echo "</div></div></div>";

            
        }
        
        function display_page() {
            // Add a form to comment the post/comment
            $this->display_post();

            echo "<form action='components/newcomment.php?id=".$this->ID."' method='POST'>";
            echo "<input type='hidden' name='id' value='".$this->ID."'>";
            echo "<textarea name='content' placeholder='Comment'></textarea>";
            echo "<input name='newComment' type='submit' value='comment'>";
            echo "</form>";

            global $db;

            $sql = "SELECT * FROM post WHERE ID_post = ? ORDER BY `date` DESC";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);

            // echo "<div class='comment'>";
            foreach ($comments as $comment) {
                $comment = new Post($comment['ID'], $comment['ID_user'], $comment['ID_post'], $comment['content'], $comment['date'], $comment['isSensible']);
                $comment->display_post();
            }
            // echo "</div>";
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