
<link rel="stylesheet" href="./css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="scripts/script.js"></script>
<header>


<?php
    session_start();
    include 'components/db.php';
    include 'components/functions.php';
    global $db;
    $email = $password = $username= " ";
    $emailErr = $passwordErr=" ";
    $verifPassword=" ";

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["login"])){ // Variables are already define because we use required balise 
        $password = test_input($_POST["password"]);
        $email = test_input($_POST["email"]);
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // filter_var filters a variable with a specific filter. In this case it's for email
            $mailErr = "<script>validate('mail1');</script>
            <p class='error_message'>Incorrect format mail</p>";
        }

        if(!__isuserhere($email,'email',$db))
        {
            $emailErr ="<script>validate('mail1');</script>
            <p class='error_message'>Incorrect mail</p>";
            
        }
        
        // $password= md5($password);
        
        if($emailErr == " "){
            $sql="SELECT password FROM user WHERE email=?";
            $qry = $db->prepare($sql);
            $qry->execute([$email]);
            $verifPassword =$qry->fetch();

            if($verifPassword[0] != $password){
                $passwordErr="<script>validate('password1');</script>
                <p class='error_message'>Wrong password</p>";
            }else{
                $sql="SELECT username, ID, isAdmin FROM user WHERE password=? AND email= ?";
                $qry = $db->prepare($sql);
                $qry->execute([$password,$email]);
                $infos = $qry->fetch();

                $_SESSION['ID_user'] = $infos["ID"];
                $_SESSION['isAdmin'] = $infos["isAdmin"];
                $_SESSION['profile_picture'] = __findPP($email,$password,$db);
                // $page = $_SERVER['HTTP_REFERER'];
                // header("Location: $page");
            
            }
        }
    } 
?>

<nav class="navbar navbar-expand-lg bg-body-secondary p-2">
  <div class="container-fluid">
    <a class="navbar-brand" href="./index.php">W</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
            if(!(isset($_SESSION['ID_user']))){
                echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>';
                echo '<li class="nav-item"><a class="nav-link" href="./register.php">Sign up</a></li>';
            } else {
                // my page
                echo '<li class="nav-item"><a class="nav-link" href="user.php?id='.$_SESSION['ID_user'].'">Ma page</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="./fil.php">Mon fil</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="./components/disconnect.php">Déconnexion</a></li>';
                
                if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                    echo '<li class="nav-item"><a class="nav-link" href="./admin.php">Admin</a></li>';
                }
                
            }
            echo '</ul>';
            if (isset($_SESSION['ID_user'])) {
                if (isset($_SESSION['profile_picture'])) {
                    $img = base64_encode($_SESSION['profile_picture']);
                    echo '<a class="navbar-link active" href="user.php?id='.$_SESSION['ID_user'].'">';
                    echo '<img class="pdp rounded img" alt="profile_picture" src="data:image/png;base64,'.$img.'" />';
                    echo '</a>';
                } 
            }
        ?>
        </ul>
    </div>
  </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="loginModalLabel">Sign In</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="container text-center">
            <div class="row mb-3">
                <label for="loginEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input required type="email" name="email" type="text" maxlength=60 class="form-control" id="loginEmail" placeholder="email@example.com" autocomplete="off">
                </div>
                <?php echo $emailErr;?>
            </div>
            <div class="row mb-3">
                <label for="loginPassword" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                <input required name="password" type="password" class="form-control" id="loginPassword"  maxlength=40 placeholder="Password" autocomplete="off">
                <?php echo $passwordErr;?>
                </div>
            </div> 
            
            <button name="login" value="Submit" type="submit" class="btn btn-primary">Sign in</button>
            <div class="row mb-3">
                <a href="register.php">Don't have an account ?</a>
            </div>

        </div></div>
        </form>
      <!-- </div> -->
    </div>
  </div>
</div>



<script src="scripts/script.js"></script>


</header>