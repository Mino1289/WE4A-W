<?php
    class User {
        public $ID; 
        public $username; 
        public $email; 
        public $first_name; 
        public $last_name; 
        public $birth_date;
        public $profile_picture;
        public $isWarn; 
        public $isAdmin;

        function __construct($ID, $username, $email, $first_name, $last_name, $birth_date, $profile_picture, $isWarn, $isAdmin) {
            $this->ID_user = $ID;
            $this->username = $username;
            $this->email = $email;
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->birth_date = $birth_date;
            $this->profile_picture = $profile_picture;
            $this->isWarn = $isWarn;
            $this->isAdmin = $isAdmin;
        }

        function display_page() {
            include 'components/post.php';
            global $db;

            echo "<title> W | ".$this->username ."</title>";
            echo "<h3 id='username'>".$this->username."</h3>";
            echo "<div id='information_user'>";
            echo "<p>Name : ".$this->username."</p>";
            echo "<p>Firstname : ".$this->first_name."</p>";
            echo "<p>Mail : ".$this->email."</p>";

            $sql = "SELECT COUNT(*) AS n FROM follow WHERE ID_followed = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $followers = $query->fetch(PDO::FETCH_ASSOC);

            echo "<p>Followers : ". $followers['n'] ."</p>"; //TODO: click on the number to display the list of followers/followings

            $sql = "SELECT COUNT(*) AS n FROM follow WHERE ID_user = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $followings = $query->fetch(PDO::FETCH_ASSOC);

            echo "<p>Followings : ". $followings['n'] ."</p>"; //TODO: click on the number to display the list of followers/followings
            //TODO: add a btn to follow/unfollow the user if you are not the user
            echo "</div>";
            echo "<div id='post_container'>";
            
            $sql = "SELECT * FROM post WHERE ID_user = ? AND ID_post IS NULL ORDER BY date DESC";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $posts = $query->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($posts as $post) {
                $post = new Post($post['ID'], $post['ID_user'], $post['ID_post'], $post['content'], $post['date'], $post['isSensible']);
                $post->display_post();
            }


            echo "</div>";
        }
    }

    function userFromID($ID) : User {
        global $db;
        $sql = "SELECT * FROM user WHERE ID = ?";
        $query = $db->prepare($sql);
        $query->execute([$ID]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
        

        return new User($user['ID'], $user['username'], $user['email'], $user['first_name'], $user['last_name'], $user['birth_date'], $user['profile_picture'], $user['isWarn'], $user['isAdmin']);
    }
?>