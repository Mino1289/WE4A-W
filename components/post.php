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
        public $isDeleted;
        public $imageURL;

        function __construct($ID, $ID_user, $ID_post, $content, $date, $isSensible, $isDeleted, $imageURL) {
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
            
            $post_html = "<div class='position-relative'>";
            $post_html .= "<div id='post-".$this->ID."' class='card mb-5";
            if ($this->ID_post != null) {
                $post_html .= " mx-5 comment";
            }
            else {
                $post_html .= " mx-3 post";
            }
            if ($user['isWarn'] != 0) {
                $post_html .= " warned";
            }
            if ($this->isSensible == '1') {
                $post_html .= " sensible";
            }
            $post_html .= "'>";
            
            $post_html .= "<div class='card-header'>";
            $post_html .= "<div class='row align-items-start align-items-center'>";
            $img = base64_encode($user['profile_picture']);
            $post_html .= '<div class="col-md-2">';
            $post_html .= '<img class="pdp img-thumbnail" alt="pp" src="data:image/png;base64,'.$img.'">';
            $post_html .= "</div>";

            $post_html .= '<div class="col">';
            $post_html .= "<a href='user.php?id=".$this->ID_user."' class=''>".$user["username"]."</a>";
            
            $sql = "SELECT isAdmin FROM user WHERE ID = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $isAdmin = $query->fetch(PDO::FETCH_ASSOC);
            if ($isAdmin['isAdmin'] == 1) {
                $post_html .= "<span class='ms-3 badge bg-warning text-dark'>Admin</span>";
            }
            $post_html .= "</div>";

            if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) || (isset($_SESSION["ID_user"]) && $this->ID_user == $_SESSION["ID_user"]) && $this->isDeleted == 0) {
                $post_html .= '<div class="dropdown col">
                <button class="btn btn-sm btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                </button>
                <ul class="dropdown-menu">';
            }

            if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {
                $post_html .= '<li>';
                    $post_html .= "<div class='dropdown-item'>";
                    $post_html .= "<button id='btn-warn-post-".$this->ID."' class='btn btn-sm btn-warning' type='submit' name='sensible' onclick='warn(".$this->ID.",\"post\")'>";
                    if($this->isSensible == 0){
                        $post_html .= "Marquer le";
                    } else {
                        $post_html .= "Enlever le marquage du";
                    }
                    $post_html .= " post sensible</button></div>";
                $post_html .= '</li><li>';
                
                $post_html .= "<div class='dropdown-item'>";
                $post_html .= "<button class='btn btn-sm btn-warning warn-user-".$this->ID_user."' type='submit' name='sensible' onclick='warn(".$this->ID_user.",\"user\")'>";
                if($user['isWarn'] == 0){
                    $post_html .= "Avertir";
                } else {
                    $post_html .= "Enlever l'avertissement de";
                }
                $post_html .= " l'utilisateur</button></div>";
                $post_html .= '</li>';
            }
            if (isset($_SESSION['isBanned']) && $_SESSION['isBanned'] == 0) {
                if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) || (isset($_SESSION["ID_user"]) && $this->ID_user == $_SESSION["ID_user"]) && $this->isDeleted == 0) {
                    $post_html .= "<li><div class='dropdown-item'>";
                    $post_html .= "<button class='btn btn-sm btn-danger' type='submit' name='sensible' onclick='delet(".$this->ID.",\"post\")'>";
                    $post_html .= "Supprimer le ";
                    if ($this->ID_post != null) {
                        $post_html .= "commentaire";
                    }
                    else {
                        $post_html .= "post";
                    }
                    $post_html .= "</button></div></li>";
                }
            } else {
                $post_html .= "<li><p>Vous n'avez droit à aucune action.</p></li>";
            }
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1 && $_SESSION['ID_user'] != $this->ID_user) {
                $post_html .= "<li><div class='dropdown-item'><button class='btn btn-sm btn-danger' type='submit' name='ban' onclick='ban(".$this->ID_user.")'>Bannir l'utilisateur</button></div></li>";
            }
            if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) || (isset($_SESSION["ID_user"]) && $this->ID_user == $_SESSION["ID_user"]) && $this->isDeleted == 0) {
                $post_html .= '</ul></div>';
            }

            
            $post_html .= "</div>";
            $post_html .= "</div>";
            $post_html .= "<div class='card-body text-body-primary'>
                <div class='row'>
                <div class='col'>";
            $post_html .= "<p>".$this->content."</p>";
            
            $post_html .= "</div>";
            if($this->imageURL != null){
                $post_html .= '<div class="col">
                    <img class="img-fluid" alt="Image of the post" src="'.$this->imageURL.'">
                    </div>';
                }
            $post_html .= '</div></div>';
            $post_html .= "<div class='card-footer'>";
            
            $post_html .= "<div class='row align-items-center'>";
            if (isset($_SESSION['isBanned']) && $_SESSION['isBanned'] == 0) {
                $post_html .= "<div class='col'><a href='post.php?id=".$this->ID."'><button class='btn btn-sm btn-primary'>Voir le post</button></a></div>";
            }
            $post_html .= "<div class='col'><small class='text-body-secondary' >Le ".$this->date."</small></div>";
            if (isset($_SESSION['isBanned']) && $_SESSION['isBanned'] == 0) {
                $outline = "";
                if (isset($_SESSION['ID_user'])) {
                    $sql = "SELECT * FROM `like` WHERE `like`.ID_post = ? AND `like`.ID_user = ?";
                    $query = $db->prepare($sql);
                    $query->execute([$this->ID, $_SESSION['ID_user']]);
                    $like = $query->fetch(PDO::FETCH_ASSOC);
                    if ($like) {
                        $outline = "outline-";
                    }
                }
                $post_html .= "<div class='col'>
                <button id='like-".$this->ID."' class='btn btn-sm btn-".$outline."success' onclick=like(".$this->ID.")>". $this->likes." W</button>
                </div>"; 
                
                $outline = "";
                if (isset($_SESSION['ID_user'])) {
                    $sql = "SELECT * FROM dislike WHERE dislike.ID_post = ? AND dislike.ID_user = ?";
                    $query = $db->prepare($sql);
                    $query->execute([$this->ID, $_SESSION['ID_user']]);
                    $dislike = $query->fetch(PDO::FETCH_ASSOC);
                    if ($dislike) {
                        $outline = "outline-";
                    }
                }

                $post_html .= "<div class='col'>
                <button id='dislike-".$this->ID."' class='btn btn-sm btn-".$outline."danger' onclick=dislike(".$this->ID.")>". $this->dislikes ." L</button>
                </div>";
                // add a btn btn-sm to display the comments and add a comment
                //TODO: using https://getbootstrap.com/docs/5.3/components/collapse/
                
                $post_html .= '<div class="col">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#comment-add-'.$this->ID.'" aria-expanded="false" aria-controls="collapseExample">
                    Ajouter un commentaire
                    </button>
                    </div>';

                $sql = "SELECT COUNT(*) AS n FROM post WHERE ID_post = ? AND isDeleted = 0";
                $query = $db->prepare($sql);
                $query->execute([$this->ID]);
                $n = $query->fetch(PDO::FETCH_ASSOC);
                $n = $n['n'];
                if ($n > 0) {
                    $post_html .= '<div class="col">
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#comment-'.$this->ID.'" aria-expanded="false" aria-controls="collapseExample">
                        Voir les commentaires ('.$n.')
                        </button>
                        </div>';
                }

                $post_html .= "</div></div>"; // footer + row div
                $post_html .= '<div class="collapse" id="comment-add-'.$this->ID.'">
                <div class="card card-body m-4">
                <p>Ajouter un commentaire !</p>
                    <form action="components/newcomment.php?id='.$this->ID.'" method="POST">
                    <input type="hidden" name="id" value="'.$this->ID.'">
                    <div class="mb-3">
                            <label for="newCommentTextArea" class="form-label"></label>
                            <textarea class="form-control" placeholder="Que voulez-vous commenter ?" id="newCommentTextArea" maxlength="300" name="content" rows="3" autocomplete="off"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-auto">
                            <button class="form-control btn btn-sm btn-primary" type="submit" name="newComment" id="newCommentSubmit">Ajouter le commentaire</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>';
        }
            $post_html .= "</div>";
            
            
            $sql = "SELECT * FROM post WHERE ID_post = ? AND isDeleted = 0 ORDER BY `date` DESC";
            $query = $db->prepare($sql);
            $query->execute([$this->ID]);
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($comments) {
                $post_html .= '<div class="collapse" id="comment-'.$this->ID.'"><div class="m-2">';
            }
            foreach ($comments as $comment) {
                $comment = new Post($comment['ID'], $comment['ID_user'], $comment['ID_post'], $comment['displayedcontent'], $comment['date'], $comment['isSensible'], $comment['isDeleted'], $comment['imageURL']);
                $post_html .= $comment->display_post();
            }
            if ($comments){ 
                $post_html .= "</div></div>";
            }
            $post_html .= "</div>";

            return $post_html;
        }
        
        function display_page() {
            // Add a form to comment the post/comment
            echo "<div class='mt-3'></div>";
            echo $this->display_post();          

            // echo "<div id='posts'>";
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

        return new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['displayedcontent'], $post['date'], $post['isSensible'], $post['isDeleted'], $post['imageURL']);
    }
?>