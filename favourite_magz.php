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
			
?>
<div class="container">
<form class="form-horizontal">
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
  <input id="editor_username" name="editor_username" value="<?php echo $userData["username"]; ?>" type="text" placeholder="" class="form-control input-md" required="">
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
</form>

<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Vaihda salasana</legend>

</fieldset>
</form>

</div>

<?php
include("includes/footer.php");
?>