<?php
include 'header.php';

if (isset($_SESSION['ID_user'])) {
    include 'components/user.php';


    $id = $_SESSION['ID_user'];
    $user = userFromID($id);
?>
    <form action="components/settings.php" method="POST" enctype="multipart/form-data">
    <div class="input-group row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="username" class="col-form-label">Pseudo :</label>
        </div>
        <div class="col-md-2">
            <input type="text" name="username" maxlength="20" class="form-control" placeholder="<?php echo $user->username; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="firstName" class="col-form-label">Prénom :</label>
        </div>
        <div class="col-md-2">
            <input type="text" name="firstName" maxlength="50" class="form-control" placeholder="<?php echo $user->first_name; ?>" autocomplete="off">
        </div>
    </div>

    <div class="input-group row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="lastName" class="col-form-label">Nom de famille :</label>
        </div>
        <div class="col-md-2">
            <input type="text" name="lastName" maxlength="50" class="form-control" placeholder="<?php echo $user->last_name; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="email" class="col-form-label">Email :</label>
        </div>
        <div class="col-md-2">
            <input type="email" name="email" maxlength="100" class="form-control" placeholder="<?php echo $user->email; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="date" class="col-form-label">Date de naissance :</label>
        </div>
        <div class="col-md-2">
            <input type="date" name="birthDate" maxlength="100" class="form-control" value="<?php echo $user->birth_date; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="adress" class="col-form-label">Adresse :</label>
        </div>
        <div class="col-md-2">
            <input type="text" name="adress" maxlength="100" class="form-control" placeholder="<?php echo $user->adress; ?>" autocomplete="off">
        </div>
    </div>

    <div class="row g-3 align-items-center mt-1">
        <div class="col-md-1 d-flex justify-content-center align-items-center"> 
            <label for="photo_profil" class="col-form-label">Photo de profil :</label>
        </div>
        <div class="col-auto">
        <div class="icon-input">
            <?php $img = base64_encode($_SESSION['profile_picture']);
            echo '<img class="settings_pdp rounded img-thumbnail" alt="profile_picture" src="data:image/png;base64,' . $img . '" />'; ?>
            <input type="file" id="photo_profil" name="photo_profil" accept="image/*" maxsize="2000">
            <i class="fa fa-fw fa-image"></i>
            <script>
                const uploadField = document.getElementById("photo_profil");

                uploadField.onchange = function() {
                    if (this.files[0].size > 2097152) {
                        alert("L'image est trop grande, \nIl faut une image de moins de 2Mo.");
                        this.value = "";
                    }
                };
            </script>
        </div>
        </div>
    </div>
        <div class="mt-3"> 
        <button class="btn btn-primary" type="submit">Envoyer le formulaire</button>
        </div>
    </form>
    
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#passwordModal">Changer de mot de passe</button>
    <div class="modal fade bd-example-modal-lg" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="passwordModalLabel">Changer de mot de passe</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="components/settings.php">
            <div class="container text-center">
              <div class="row mb-3">
                <label for="changePassword" class="col-sm-3 col-form-label">Mot de passe</label>
                <div class="col-sm-9">
                  <input required type="password" name="changePassword" maxlength=20 class="form-control" id="changePassword" placeholder="Mot de passe" autocomplete="off">
                </div>
              </div>
              <div class="row mb-3">
                <label for="changeVerifyPassword" class="col-sm-3 col-form-label">Vérifier Mot de passe</label>
                <div class="col-sm-9">
                  <input required type="password" name="changeVerifyPassword" maxlength=20 class="form-control" id="changeVerifyPassword" placeholder="Vérification du Mot de passe" autocomplete="off">
                </div>
                <p id="arePasswordsIdentical"></p>
              </div>
              
              <button disabled class="btn btn-primary" type="submit" id="submitChangePassword">Changer de mot de passe</button>
            </div>
        </div>
    </div>
</div>
</div>
<?php
}
?>

<script>
    // Check if the two passwords are identical
    $("#changeVerifyPassword").on("keyup", function() {
      if ($('#changePassword').val() !== $('#changeVerifyPassword').val()) {
        $('#arePasswordsIdentical').text("Les mots de passe ne sont pas identiques.").css('color', 'red');
        $("#submitChangePassword").prop('disabled', true).removeClass('btn-primary').addClass('btn-danger');
      } else {
        $('#arePasswordsIdentical').text("Les mots de passe sont identiques.").css('color', 'green');
        $("#submitChangePassword").prop('disabled', false).removeClass('btn-danger').addClass('btn-success');
      }
    });
  </script>