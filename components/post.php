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
        public $imageURL;

        function __construct($ID, $ID_user, $ID_post, $content, $date, $isSensible, $imageURL) {
            $this->ID = $ID;
            $this->ID_user = $ID_user;
            $this->ID_post = $ID_post;
            $this->content = $content;
            $this->date = $date;
            $this->isSensible = $isSensible;
            $this->imageURL = $imageURL;
            
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
            
            echo "<div id='post-".$this->ID."' class='card mb-5";
            if ($this->ID_post != null) {
                echo " mx-5 comment";
            }
            else {
                echo " mx-3 post";
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
            echo '<div class="col-md-2">';
            echo '<img class="pdp img-thumbnail" alt="pp" src="data:image/png;base64,'.$img.'">';
            echo "</div>";

            echo '<div class="col">';
            echo "<a href='user.php?id=".$this->ID_user."' class=''>".$user["username"]."</a>";
            
            $sql = "SELECT isAdmin FROM user WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $isAdmin = $query->fetch(PDO::FETCH_ASSOC);
            if ($isAdmin['isAdmin'] == 1) {
                echo "<span class='ms-3 badge bg-warning text-dark'>Admin</span>";
            }
            echo "</div>";

            if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) || (isset($_SESSION["ID_user"]) && $this->ID_user == $_SESSION["ID_user"])) {
                echo '<div class="dropdown col">
                <button class="btn btn-sm btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                </button>
                <ul class="dropdown-menu">';
            }

            if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {
                echo '<li>';
                    echo "<div class='dropdown-item'>";
                    echo "<button id='btn-warn-post-".$this->ID."' class='btn btn-sm btn-warning' type='submit' name='sensible' onclick='warn(".$this->ID.",\"post\")'>";
                    if($this->isSensible == 0){
                        echo "M";
                    } else {
                        echo "Unm";
                    }
                    echo "ark post sensible</button></div>";
                echo '</li><li>';
                
                echo "<div class='dropdown-item'>";
                echo "<button class='btn btn-sm btn-warning warn-user-".$this->ID_user."' type='submit' name='sensible' onclick='warn(".$this->ID_user.",\"user\")'>";
                if($user['isWarn'] == 0){
                    echo "W";
                } else {
                    echo "Unw";
                }
                echo "arn user</button></div>";
                echo '</li>';
            }
            if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) || (isset($_SESSION["ID_user"]) && $this->ID_user == $_SESSION["ID_user"])) {
                echo "<div class='dropdown-item'>";
                echo "<button class='btn btn-sm btn-danger' type='submit' name='sensible' onclick='delet(".$this->ID.",\"post\")'>";
                echo " Delete ";
                if ($this->ID_post != null) {
                    echo "comment";
                }
                else {
                    echo "post";
                }
                echo "</button></div>";
                echo '</ul></div>';
            }

            
            echo "</div>";
            echo "</div>";
            echo "<div class='card-body text-body-primary'>
                <div class='row'>
                <div class='col'>";
            echo "<p>".$this->content."</p>";
            
            echo "</div>";
            if($this->imageURL != null){
                echo '<div class="col">
                    <img class="img-fluid" alt="Image of the post" src="'.$this->imageURL.'">
                    </div>';
                }
            echo '</div></div>';
            echo "<div class='card-footer'>";
            
            echo "<div class='row align-items-center'>";
            echo "<div class='col'><a href='post.php?id=".$this->ID."'><button class='btn btn-sm btn-primary'>Voir le post</button></a></div>";
            echo "<div class='col'><small class='text-body-secondary' >Le ".$this->date."</small></div>";


            $sql = "SELECT * FROM `like` WHERE `like`.ID_post = ? AND `like`.ID_user = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID, $_SESSION['ID_user']]);
            $like = $query->fetch(PDO::FETCH_ASSOC);
            if ($like) {
                $outline = "outline-";
            } else {
                $outline = "";
            }

            echo "<div class='col'>
            <button id='like-".$this->ID."' class='btn btn-sm btn-".$outline."success' onclick=like(".$this->ID.")>". $this->likes." W</button>
            </div>"; 
            
            $sql = "SELECT * FROM dislike WHERE dislike.ID_post = ? AND dislike.ID_user = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID, $_SESSION['ID_user']]);
            $dislike = $query->fetch(PDO::FETCH_ASSOC);
            if ($dislike) {
                $outline = "outline-";
            } else {
                $outline = "";
            }

            echo "<div class='col'>
            <button id='dislike-".$this->ID."' class='btn btn-sm btn-".$outline."danger' onclick=dislike(".$this->ID.")>". $this->dislikes ." L</button>
            </div>";
            // add a btn btn-sm to display the comments and add a comment
            //TODO: using https://getbootstrap.com/docs/5.3/components/collapse/
            
            echo '<div class="col">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#comment-p-'.$this->ID.'" aria-expanded="false" aria-controls="collapseExample">
            Button with data-bs-target
            </button>
            </div>
            <div class="collapse" id="comment-p-'.$this->ID.'">
            <div class="card card-body">
            Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
            </div>
            </div>';
            
            echo "</div></div>"; // footer + row div
            echo "</div>";

            
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
                        <button class="form-control btn btn-sm btn-primary" type="submit" name="newComment" id="newCommentSubmit">Ajouter le commentaire</button>
                    </div>
                </div>';
            echo "</form></div>";

            


            global $db;

            $sql = "SELECT * FROM post WHERE ID_post = ? AND isDeleted = 0 ORDER BY `date` DESC";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);

            // echo "<div class='comment'>";
            foreach ($comments as $comment) {
                $comment = new Post($comment['ID'], $comment['ID_user'], $comment['ID_post'], $comment['content'], $comment['date'], $comment['isSensible'], $comment['imageURL']);
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

        return new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible'], $post['imageURL']);
    }
?>