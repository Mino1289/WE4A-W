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

            $sql = "SELECT id, username, profile_picture, isWarn FROM user WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            
            echo "<div class='mb-5 mx-3 card ";
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
            
            echo "<div class='card-header'>";
            echo "<div class='row align-items-start align-items-center'>";
            $img = base64_encode($user['profile_picture']);
            echo '<div class="col-md-1">';
            echo '<img class="pdp img-thumbnail" alt="pp" src="data:image/png;base64,'.$img.'">';
            echo "</div>";

            echo '<div class="col-md-1">';
            echo "<a href='user.php?id=".$this->ID_user."' class='text-decoration-none'>".$user["username"]."</a>";
            echo "</div>";


            if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {
                echo "<div class='col-2'>";
                echo "<form action='components/warn.php?id=".$this->ID_user."&type=user' method='POST'>";
                echo "<button class='form-control btn btn-warning' type='submit' name='sensible'>";
                if($user['isWarn'] == 0){
                    echo "warn";
                } else {
                    echo "unwarn";
                }
                echo " user</button></form>";

            }
            if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) || (isset($_SESSION["ID_user"]) && $this->ID_user == $_SESSION["ID_user"])) {
                echo "<div class='col-2 pt-2'>";
                echo "<form action='components/delete.php?id=".$this->ID."&type=post' method='POST'>";
                echo "<button class='form-control btn btn-danger' type='submit' name='sensible'>";
                echo " Delete ";
                if ($this->ID_post != null) {
                    echo "comment";
                }
                else {
                    echo "post";
                }
                echo "</button></form></div>";
            }
            
            echo "</div>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<p>".$this->content."</p>";
            
            if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {

                echo"<form action='components/warn.php?id=".$this->ID."&type=post' method='POST'>";
                echo "<button type='submit' name='sensible'>";
                if($this->isSensible == 0){
                    echo "M";
                } else {
                    echo "Unm";
                }
                echo "ark Sensible</button></form>";
                
            }

            echo "</div>";
            echo "<div class='card-footer'>";
            // echo "<p>Date : ".$this->date."</p>";
            
            echo "<div class='row align-items-center'>";
            echo "<div class='col-2'><a href='post.php?id=".$this->ID."'><button class='mb-2 btn btn-primary'>Voir le post</button></a></div>";
            echo "<div class='col-2 pt-2'>Le ".$this->date."</div>";
            //TODO: add a small form that likes/dislike the post/comment (get the unique id)
            echo "<div class='col-2'>
            <form action='components/processlike.php?id=".$this->ID."' method='POST'>
            <button class='form-control btn btn-success' type='submit' name='like'>". $this->likes." W</button>
            </form></div>"; 
            //TODO: add a small form that likes/dislike the post/comment (get the unique id)
            echo "<div class='col-2'>
            <form action='components/processlike.php?id=".$this->ID."' method='POST'>
            <button class='form-control btn btn-danger' type='submit' name='dislike'>". $this->dislikes ." L</button>
            </form></div>";
            echo "</div>";

            // echo "<a href='newcomment.php?id=".$this->ID."'>Commenter</a>";
            echo "</div></div>";

            
        }
        
        function display_page() {
            // Add a form to comment the post/comment
            $this->display_post();

            echo "<div class='container my-4'>";
            echo "<form action='components/newcomment.php?id=".$this->ID."' method='POST'>";
            echo "<input type='hidden' name='id' value='".$this->ID."'>";
            echo '<div class="mb-3">
                    <label for="newCommentTextArea" class="form-label"></label>
                    <textarea class="form-control" placeholder="Que voulez-vous commenter ?" id="newCommentTextArea" maxlength="300" name="content" rows="3" autocomplete="off"></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-auto">
                        <button class="form-control btn btn-primary" type="submit" name="newComment" id="newCommentSubmit">Ajouter le commentaire</button>
                    </div>
                </div>';
            // echo "<input name='newComment' type='submit' value='comment'>";
            echo "</form></div>";

            


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

        return new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible']);
    }
?>