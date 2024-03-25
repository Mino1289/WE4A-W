<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
global $db;

$sql= "UPDATE USER SET isWarn=1 WHERE id_user=?";
$qry = $db->prepare($sql);
$qry->execute([$id]);
$verifPassword =$qry->fetch();
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="name" name="mail1">
        <div><i class="fa fa-fw fa-envelope" id="logosearch"></i></div>
        <input required class='input' name="email" type="text" maxlength=60 placeholder="Email" autocomplete="off"/>
    </div>

    <input name="login" type="submit" value="Submit" id="submit"/>