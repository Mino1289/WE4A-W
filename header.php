<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<header>


  <?php
  session_start();
  include 'components/db.php';
  include 'components/functions.php';
  global $db;
  $email = $password = $username = " ";
  $emailErr = $passwordErr = " ";
  $verifPassword = " ";

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) { // Variables are already define because we use required balise 
    $password = test_input($_POST["password"]);
    $email = test_input($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // filter_var filters a variable with a specific filter. In this case it's for email
      $mailErr = "<script>validate('mail1');</script>
            <p class='error_message'>Incorrect format mail</p>";
    }

    if (!__isuserhere($email, 'email', $db)) {
      $emailErr = "<script>validate('mail1');</script>
            <p class='error_message'>Incorrect mail</p>";
    }

    // $password= md5($password);

    if ($emailErr == " ") {
      $sql = "SELECT password FROM user WHERE email=?";
      $qry = $db->prepare($sql);
      $qry->execute([$email]);
      $verifPassword = $qry->fetch();

      if ($verifPassword[0] != $password) {
        $passwordErr = "<script>validate('password1');</script>
                <p class='error_message'>Wrong password</p>";
      } else {
        $sql = "SELECT username, ID, isAdmin FROM user WHERE password=? AND email= ?";
        $qry = $db->prepare($sql);
        $qry->execute([$password, $email]);
        $infos = $qry->fetch();

        $_SESSION['ID_user'] = $infos["ID"];
        $_SESSION['isAdmin'] = $infos["isAdmin"];
        $_SESSION['profile_picture'] = __findPP($email, $password, $db);
        // $page = $_SERVER['HTTP_REFERER'];
        // header("Location: $page");

      }
    }
  }
  ?>

  <nav class="navbar navbar-expand-lg bg-body-secondary p-2">
    <div class="container-fluid">
      <a class="navbar-brand" href="./index.php">Home W</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php
          if (!(isset($_SESSION['ID_user']))) {
            echo '<li class="nav-item"><button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#loginModal">Se connecter</button></li> ';
            echo '<li class="nav-item"><button type="button" class="btn btn-info mx-2" data-bs-toggle="modal" data-bs-target="#registerModal">S\'inscrire</button></li>  ';
          } else {
            // my page
            echo '<li class="nav-item"><a class="nav-link" href="user.php?id=' . $_SESSION['ID_user'] . '">Ma page</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="./fil.php">Mon fil</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="./trend.php">Tendances</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="./components/disconnect.php">Déconnexion</a></li>';

            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
              echo '<li class="nav-item"><a class="nav-link" href="./admin.php">Admin</a></li>';
            }
          }
          echo '</ul>';
          if (isset($_SESSION['ID_user'])) {
            $sql = "SELECT COUNT(*) AS n FROM `notification` WHERE ID_user = ? AND isRead = 0";
            $qry = $db->prepare($sql);
            $qry->execute([$_SESSION['ID_user']]);
            $notifs = $qry->fetch()['n'];

            echo '<div class="row align-items-center">';
            echo '<div class="col"><a href="notifications.php">
                <button type="button" class="btn btn-primary position-relative">
                  Notifications';
            echo '<span id="notif-nbr" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  ' . $notifs . '
                  <span class="visually-hidden">unread messages</span>
                </span>';
            echo '</button></a></div>';

            if (isset($_SESSION['profile_picture'])) {
              $img = base64_encode($_SESSION['profile_picture']);
              echo '<div class="col">';
              echo '<p class="visually-hidden" id="ID_user">'.$_SESSION['ID_user'].'</p>';
              echo '<a class="navbar-link active" href="user.php?id=' . $_SESSION['ID_user'] . '">';
              echo '<img class="pdp rounded img" alt="profile_picture" src="data:image/png;base64,' . $img . '" />';
              echo '</a></div>';
            }
            echo '</div>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Modal Login -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="loginModalLabel">Se connecter</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="container text-center">
              <div class="row mb-3">
                <label for="loginEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input required type="email" name="email" type="text" maxlength=60 class="form-control" id="loginEmail" placeholder="email@exemple.com" autocomplete="off">
                </div>
                <?php echo $emailErr; ?>
              </div>
              <div class="row mb-3">
                <label for="loginPassword" class="col-sm-2 col-form-label">Mot de passe</label>
                <div class="col-sm-10">
                  <input required name="password" type="password" class="form-control" id="loginPassword" maxlength=40 placeholder="Mot de passe" autocomplete="off">
                  <?php echo $passwordErr; ?>
                </div>
              </div>

              <button name="login" value="Submit" type="submit" class="btn btn-primary">Se connecter</button>
              <div class="row mb-3">
                <a href="register.php">Vous n'avez pas de compte ?</a>
              </div>

            </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Register -->
  <div class="modal fade bd-example-modal-lg" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="registerModalLabel">S'inscrire</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="components/register.php">
            <div class="container text-center">
              <div class="row mb-3">
                <label for="registerUsername" class="col-sm-3 col-form-label">Pseudo</label>
                <div class="col-sm-9">
                  <input required name="username" type="text" maxlength=25 class="form-control" id="registerUsername" placeholder="Tim" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerFirstName" class="col-sm-3 col-form-label">Prénom</label>
                <div class="col-sm-9">
                  <input required type="text" name="firstName" type="text" maxlength=50 class="form-control" id="registerFirstName" placeholder="Martin" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerLastName" class="col-sm-3 col-form-label">Nom de famille</label>
                <div class="col-sm-9">
                  <input required type="text" name="lastName" type="text" maxlength=50 class="form-control" id="registerLastName" placeholder="Meyer" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerEmail" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                  <input required type="email" name="email" maxlength=100 class="form-control" id="registerEmail" placeholder="email@example.com" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerAdress" class="col-sm-3 col-form-label">Adresse postale</label>
                <div class="col-sm-9">
                  <input required type="text" name="adress" maxlength=100 class="form-control" id="registerAdress" placeholder="12 rue du général Icarya" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerBirthDate" class="col-sm-3 col-form-label">Date de naissance</label>
                <div class="col-sm-9">
                  <input required type="date" name="birthDate" maxlength="100" class="form-control" id="registerBirthDate" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerProfilePicture" class="col-sm-3 col-form-label">Photo de profil</label>
                <div class="col-sm-9">
                  <input type="file" name="photo_profil" accept="image/*" class="form-control" id="registerProfilePicture" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerPassword" class="col-sm-3 col-form-label">Mot de passe</label>
                <div class="col-sm-9">
                  <input required name="password" type="password" class="form-control" id="registerPassword" maxlength=50 placeholder="Mot de passe" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="registerVerifyPassword" class="col-sm-3 col-form-label">Vérifier Mot de passe</label>
                <div class="col-sm-9">
                  <input required name="passwordCheck" type="password" class="form-control" id="registerVerifyPassword" maxlength=50 placeholder="Vérification du mot de passe" autocomplete="off">
                </div>
                <p id="isIdentical"></p>
              </div>

              <button disabled name="register" value="Submit" type="submit" class="btn btn-danger" id="formButton">S'inscrire</button>

            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    // Check if the two passwords are identical
    $("#registerVerifyPassword").on("keyup", function() {
      if ($('#registerPassword').val() !== $('#registerVerifyPassword').val()) {
        $('#isIdentical').text("Les mots de passe ne sont pas identiques.").css('color', 'red');
        $("#formButton").prop('disabled', true).css('backgroundColor', 'red');
      } else {
        $('#isIdentical').text("Les mots de passe sont identiques.").css('color', 'green');
        $("#formButton").prop('disabled', false).css('backgroundColor', 'green');
      }
    });
  </script>




  <script src="scripts/script.js"></script>


</header>