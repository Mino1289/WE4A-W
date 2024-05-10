<style>
        #profile_picture{
            width: 5em;
            height: 5em;
            margin: 0 auto;
        } 
</style>
<?php
    class User {
        public $ID_user; 
        public $username; 
        public $email; 
        public $first_name; 
        public $last_name; 
        public $adress;
        public $birth_date;
        public $profile_picture;
        public $isWarn;
        public $isBan;
        public $isAdmin;

        function __construct($ID, $username, $email, $first_name, $last_name, $adress, $birth_date, $profile_picture, $isWarn, $isBan, $isAdmin) {
            $this->ID_user = $ID;
            $this->username = $username;
            $this->email = $email;
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->adress = $adress;
            $this->birth_date = $birth_date;
            $this->profile_picture = $profile_picture;
            $this->isWarn = $isWarn;
            $this->isBan = $isBan;
            $this->isAdmin = $isAdmin;
            $_SESSION['ID_user_page'] = $ID;
        }

        function display_page() {
            include 'components/post.php';
            global $db;

            echo "<div class='container text-center mt-2'>";
            echo "<div class='' id='profile_picture'><img src='data:image/png;base64,". base64_encode($this->profile_picture) ."' class='img-fluid' alt='profile picture'></div>";
            echo "<div class=''><h1>". $this->username ."</h1></div>";
            echo "<div class=''><p>". $this->first_name ." ". $this->last_name ."</p></div>";
            echo "<div class='row'>";

            $sql = "SELECT COUNT(*) AS n FROM follow WHERE ID_followed = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $followers = $query->fetch(PDO::FETCH_ASSOC);

            echo "<div class='col'><p class='m-0'>Followers : ". $followers['n'] ."</p></div>"; //TODO: click on the number to display the list of followers/followings
            
            $sql = "SELECT COUNT(*) AS n FROM follow WHERE ID_user = ?";
            $query = $db->prepare($sql);
            $query->execute([$this->ID_user]);
            $followings = $query->fetch(PDO::FETCH_ASSOC);

            echo "<div class='col'><p class='m-0'>Followings : ". $followings['n'] ."</p></div>"; //TODO: click on the number to display the list of followers/followings
            echo "</div>";
            echo "<div class='row my-2'>";
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1 && $_SESSION['ID_user'] != $this->ID_user && $this->isAdmin == 0) {
                echo "<div class='col-6'>
                <form action='components/setadmin.php' method='post'>
                <button id='warn-btn' class='btn btn-warning px-3'>Set Admin</button>
                <input type='hidden' name='ID_user' value='".$this->ID_user."'>
                </form>
                </div>";
            }
            if (isset($_SESSION['ID_user']) && $_SESSION['ID_user'] != $this->ID_user) {
                $sql = "SELECT * FROM follow WHERE ID_user = ? AND ID_followed = ?";
                $query = $db->prepare($sql);
                $query->execute([$_SESSION['ID_user'], $this->ID_user]);
                $follow = $query->fetch(PDO::FETCH_ASSOC);
                if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1 && $_SESSION['ID_user'] != $this->ID_user && $this->isAdmin == 0)) {
                    echo "<div class='col-6'></div>";
                }
                echo "<div class='col-6'>";
                echo "<button id='follow-btn' class='btn btn-";
                if ($follow) {
                    echo "danger";
                } else {
                    echo "success";
                }
                echo " follow-btn px-3' onclick='follow(".$this->ID_user.")'>";
                if ($follow) {
                    echo "Unfollow";
                } else {
                    echo "Follow";
                }
                echo "</button></div>";
            } elseif (isset($_SESSION['ID_user']) && $_SESSION['ID_user'] == $this->ID_user) {
                echo "<div class='col'><a href='followers.php'>
                <button value='Followers' class='btn btn-primary'>Follower</button></a>
                </div>";

                echo "<div class='col'><a href='followings.php'>
                <button value='Followings' class='btn btn-primary'>Suivi</button></a>
                </div>";
                echo "</div>"; 
                echo "<div class='row my-2'>";
                echo "<div class='col'><a href='statistic.php'>
                <button value='Statistics' class='btn btn-primary'>Statistiques</button></a>
                </div>";
                echo "<div class='col'><a href='settings.php'>
                <button value='Settings' class='btn btn-primary'>Paramètres</button></a>
                </div>";
                echo "</div>";
            }

            echo "</div>";
            if (isset($_SESSION['ID_user']) && $_SESSION['ID_user'] == $this->ID_user && $this->isBan == 0) {
                include 'components/newpost.php';
            }
            if(isset($_SESSION['ID_user'])){
                if ($this->isBan == 1) {
                    echo "<div class='alert alert-danger text-center m-3 mt-4'>";
                    if ($this->ID_user == $_SESSION['ID_user']) {
                        echo "<h2>Vous êtes banni !</h2>";
                        echo "<p>Ceci est la seule page à laquelle vous pouvez accéder.</p></div>";
                    } else {
                        echo "<h2>Cet utilisateur est banni !</h2></div>";
                    }
                } 
                if ($this->isBan == 0 || $this->ID_user == $_SESSION['ID_user'] || $_SESSION['isAdmin'] == 1) {
                    echo "<div id='posts' class='mt-4'>";
                    echo "</div>";
                }
            } else {
                if ($this->isBan == 1) {
                    echo "<div class='alert alert-danger text-center m-3 mt-4'>";
                    echo "<h2>Cet utilisateur est banni !</h2></div>";
            }
        }
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
        

        return new User($user['ID'], $user['username'], $user['email'], $user['first_name'], $user['last_name'], $user['adress'], $user['birth_date'], $user['profile_picture'], $user['isWarn'], $user['isBan'], $user['isAdmin']);
    }
?>