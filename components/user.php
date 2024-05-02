<?php
    class User {
        public $ID_user; 
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
            $_SESSION['ID_user_page'] = $ID;
        }

        function display_page() {
            include 'components/post.php';
            global $db;

            echo "<title> W | ".$this->username ."</title>";
            echo "<h3 id='username'>".$this->username."</h3>";
            echo "<div id='information_user' class='m-1'>";
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

            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1 && $_SESSION['ID_user'] != $this->ID_user) {
                echo "add a btn to make you admin !";
            }
            if (isset($_SESSION['ID_user']) && $_SESSION['ID_user'] != $this->ID_user) {
                $sql = "SELECT * FROM follow WHERE ID_user = ? AND ID_followed = ?";
                $query = $db->prepare($sql);
                $query->execute([$_SESSION['ID_user'], $this->ID_user]);
                $follow = $query->fetch(PDO::FETCH_ASSOC);

                echo "<div class='col-1'>
                <button id='follow-btn' class='form-control btn btn-";
                if ($follow) {
                    echo "danger";
                } else {
                    echo "success";
                }
                echo " follow-btn' onclick='follow(".$this->ID_user.")'>";
                if ($follow) {
                    echo "Unfollow";
                } else {
                    echo "Follow";
                }
                echo "</button></div>";
            } elseif (isset($_SESSION['ID_user']) && $_SESSION['ID_user'] == $this->ID_user) {
                echo "<div class='col-1 m-1'><a href='followed.php'>
                <button value='Followed' class='btn btn-primary'>Suivi</button></a>
                </div>";
                echo "<div class='col-1 m-1'><a href='statistic.php'>
                <button value='Statistics' class='btn btn-primary'>Statistiques</button></a>
                </div>";
                echo "<div class='col-1 m-1'><a href='settings.php'>
                <button value='Settings' class='btn btn-primary'>Paramètres</button></a>
                </div>";
            }

            echo "</div>";
            echo "<div id='posts'>";
            echo "</div>";
        }

        function isAdmin(): bool{
            if($this -> isAdmin == 1){
                return true;
            } else {
                return false;
            }
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