<?php
include 'header.php';

if (isset($_SESSION['ID_user'])) {
    include 'components/user.php';


    $id = $_SESSION['ID_user'];
    $user = userFromID($id);
?>
    <form action="components/settings.php" method="POST" enctype="multipart/form-data">
        <label for="username">Username :</label>
        <div class="icon-input">
            <input autofocus type="text" name="username" maxlength="20" placeholder="<?php echo $user->username; ?>" autocomplete="off">
            <i class="fa fa-fw fa-user"></i>
        </div>

        <label for="firstName">First Name :</label>
        <div class="icon-input">
            <input type="text" name="firstName" maxlength="40" placeholder="<?php echo $user->first_name; ?>" autocomplete="off">
            <i class="fa fa-fw fa-user"></i>
        </div>

        <label for="lastName">Last Name :</label>
        <div class="icon-input">
            <input type="text" name="lastName" maxlength="40" placeholder="<?php echo $user->last_name; ?>" autocomplete="off">
            <i class="fa fa-fw fa-user"></i>
        </div>

        <label for="email">Email :</label>
        <div class="icon-input">
            <input type="text" name="email" maxlength="100" placeholder="<?php echo $user->email; ?>" autocomplete="off">
            <i class="fa fa-fw fa-envelope"></i>
        </div>

        <label for="birthDate">Birthdate : </label>
        <div class="icon-input">
            <input type="date" name="birthDate" maxlength="100" value="<?php echo $user->birth_date; ?>" autocomplete="off">
            <i class="fa fa-fw fa-calendar"></i>
        </div>

        <label for="photo_profil">Profile Picture :</label>
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

        <label for="password">Password :</label>
        <div class="icon-input">
            <input type="password" name="password" maxlength="20" placeholder="Password" autocomplete="off">
            <i class="fa fa-fw fa-lock"></i>
        </div>

        <button class="btn btn-primary" type="submit">Envoyer le formulaire</button>
    </form>
<?php
}
?>