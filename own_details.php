<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}

include("includes/header.php");

$userResult = $dataconnection->query("SELECT * FROM user_accounts WHERE username = '" . $_SESSION["LOGIN_USER"] . "' LIMIT 1");
			$userData = $userResult->fetch_assoc();
	
if (isset($_POST["editor_firstname"]))
{
	$firstNameField = $_POST["editor_firstname"];
	$secondNameField = $_POST["editor_secondname"];
	$emailField = $_POST["editor_email"];
	$currentPassField = $_POST["editor_currentpassword"];
	$newPassField = $_POST["editor_newpassword"];
	$newPass2Field = $_POST["editor_newpassword2"];
	
	if (empty($firstNameField) || empty($secondNameField) || empty($emailField))
	{
		$errorMessage = "Vaadittuja tietoja puuttuu, tarkista.";
	}
	if (!filter_var($emailField, FILTER_VALIDATE_EMAIL)) {
		$errorMessage = "Virheellinen sähköpostiosoite.";
	}
		if (empty($errorMessage))
	{
		
    $dataChange = $dataconnection->prepare("UPDATE user_accounts SET firstName=?, secondName=?, email=? WHERE username=? LIMIT 1");
	@$dataChange->bind_param('ssss', $firstNameField, $secondNameField, $emailField, $_SESSION["LOGIN_USER"]);
	$dataChange->execute();
	
		$successMessage = "Tietojen muuttaminen onnistui.";
		


	}
	
	
	if (!empty($currentPassField))
	{

$password = mysqli_real_escape_string($dataconnection, $currentPassField);

$passwordHash = @$dataconnection->query("SELECT password FROM user_accounts WHERE username = '" . $_SESSION["LOGIN_USER"] . "' LIMIT 1")->fetch_object()->password;

if (empty($newPassField) || empty($newPass2Field))
{
	$errorMessage = "Vaadittuja tietoja puuttuu, tarkista.";
}

if (!password_verify($password, $passwordHash))
{
	$errorMessage = "Nykyinen salasana on virheellinen. Ole hyvä ja tarkista.";
}
if ($newPassField != $newPass2Field)
		{
			$errorMessage = "Salasanat eivät täsmää. Tarkista oikeinkirjoitus.";
		}
		
			if (empty($errorMessage))
	{
		
		try
{
	
	$pQuery = "UPDATE user_accounts SET password=? WHERE username=?";
	$passwordChange = $dataconnection->prepare("UPDATE user_accounts SET password=? WHERE username=? LIMIT 1");
	@$passwordChange->bind_param('ss', password_hash($newPassField, PASSWORD_BCRYPT), $_SESSION["LOGIN_USER"]);
	$passwordChange->execute();

	$successMessage = "Salasanan vaihtaminen onnistui.";
}

catch(Exception $e)
{
$errorMessage = "Jokin meni pieleen";
}
	
	}
	
		
	}
	
	
}
	
?>
<div class="container">
<form class="form-horizontal" method="post">

<?php

if (!empty($errorMessage))
{
	?>
	
<div class="alert alert-danger" role="alert">
 <?php echo $errorMessage; ?>
</div>

<?php

}

?>

<?php

if (!empty($successMessage))
{
	?>
	
<div class="alert alert-success" role="alert">
 <?php echo $successMessage; ?>
</div>

<?php

}

?>
<fieldset>

<!-- Form Name -->
<legend>Muokkaa omia tietoja</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="editor_firstname">Etunimi</label>  
  <div class="col-md-4">
  <input id="editor_email" name="editor_firstname" type="text" value="<?php echo $userData["firstName"]; ?>"  placeholder="" class="form-control input-md" required="">
  <span class="help-block">Käytetään palvelun kustomoinnissa. Ei näytetä julkisesti.</span>  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="editor_secondname">Sukunimi</label>  
  <div class="col-md-4">
  <input id="editor_email" name="editor_secondname" type="text" value="<?php echo $userData["secondName"]; ?>" placeholder="" class="form-control input-md" required="">
  <span class="help-block">Käytetään palvelun kustomoinnissa. Ei näytetä julkisesti.</span>  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="editor_username">Käyttäjänimi</label>  
  <div class="col-md-4">
  <input id="editor_username" name="editor_username" value="<?php echo $userData["username"]; ?>" type="text" placeholder="" class="form-control input-md" disabled="">
  <span class="help-block">Tämä on sinun nimimerkkisi, joka näkyy julkisesti muun muassa kommentoidessasi uutisartikkeleita.</span>  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="editor_email">Sähköpostiosoite</label>  
  <div class="col-md-4">
  <input id="editor_email" name="editor_email" type="text" value="<?php echo $userData["email"]; ?>" placeholder="" class="form-control input-md" required="">
  <span class="help-block">Voit tilata uuden salasanan tähän sähköpostiosoitteeseen, jos satut unohtamaan sen.</span>  
  </div>
</div>

<!-- Prepended checkbox -->
<div class="form-group">
  <label class="col-md-4 control-label" for="prependedcheckbox">Kirjautumiskerrat</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">     
         <?php echo $userData["accessCount"]; ?>
      </span>
  
    </div>
    <p class="help-block">tunnuksen luomisen <?php echo date('j.n.Y G:i', $userData["timestamp_registered"]); ?>  jälkeen</p>
  </div>
</div>

</fieldset>


<fieldset>

<!-- Form Name -->
<legend>Vaihda salasana</legend>

<div class="form-group">
  <label class="col-md-4 control-label" for="editor_currentpassword">Nykyinen salasana</label>  
  <div class="col-md-4">
  <input id="editor_currentpassword" name="editor_currentpassword" type="password" value="" placeholder="" class="form-control input-md">
  <span class="help-block">Mikäli haluat vaihtaa salasanasi, kirjoita tähän nykyinen salasanasi.</span>  
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="editor_newpassword">Uusi salasana</label>  
  <div class="col-md-4">
  <input id="editor_newpassword" name="editor_newpassword" type="password" value="" placeholder="" class="form-control input-md">
  <span class="help-block">Anna uusi salasana. Muista valita turvallinen sana!</span>  
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="editor_newpassword2">Uusi salasana uudelleen</label>  
  <div class="col-md-4">
  <input id="editor_newpassword2" name="editor_newpassword2" type="password" value="" placeholder="" class="form-control input-md">
  <span class="help-block">Kirjoitusvirheiden välttämiseksi</span>  
  </div>
</div>

<button type="submit" class="btn btn-default">Tallenna muutokset</button>

</fieldset>
</form>

</div>

<?php
include("includes/footer.php");
?>