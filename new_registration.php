<?php
require_once("configuration.php");

$inviteCode = "";
$username = "";
$email = "";

$hasError = false;
$errorTexts = null;


if (isset($_POST["registration_username"]))
{
	$inviteCode = mysqli_real_escape_string($dataconnection, $_POST["inviteCode"]);
	$username = mysqli_real_escape_string($dataconnection, $_POST["registration_username"]);
$password = mysqli_real_escape_string($dataconnection, $_POST["registration_password"]);
$passwordAgain = mysqli_real_escape_string($dataconnection, $_POST["registration_password_double"]);
$email = mysqli_real_escape_string($dataconnection, $_POST["registration_email"]);

	if (!filter_var($_POST["registration_email"], FILTER_VALIDATE_EMAIL)) {
		$hasError = true;
		$errorTexts .= "Virheellinen sähköpostiosoite ";
	}
	if ($password != $passwordAgain)
	{
		$hasError = true;
		$errorTexts .= "Salasanat eivät täsmää ";
	}
	if (empty($_POST["registration_username"])) {
		$hasError = true;
		$errorTexts .= "Käyttäjänimi puuttuu ";
	}
		if (strlen($_POST["registration_username"]) > 20) {
		$hasError = true;
		$errorTexts .= "Käyttäjänimi on liian pitkä. ";
	}
	if (strlen($_POST["registration_username"]) < 2) {
		$hasError = true;
		$errorTexts .= "Käyttäjänimi on liian lyhyt. ";
	}
	if (strlen($_POST["registration_password"]) < 6) {
		$hasError = true;
		$errorTexts .= "Salasana on liian lyhyt, minimipituus 6 merkkiä. ";
	}
		if (empty($_POST["registration_password"])) {
		$hasError = true;
		$errorTexts .= "Salasana puuttuu ";
	}
	if (empty($_POST["registration_password_double"])) {
		$hasError = true;
		$errorTexts .= "Salasanan varmistus puuttuu ";
	}
	if (mysqli_num_rows($dataconnection->query("SELECT invite_code FROM invites WHERE invite_code = '$inviteCode' AND is_used = '0' LIMIT 1")) == 0)
{
	$hasError = true;
		$errorTexts .= "Kutsukoodi on virheellinen ";
}

	if (mysqli_num_rows($dataconnection->query("SELECT username FROM user_accounts WHERE username = '$username' LIMIT 1")) == 1)
{
		$hasError = true;
		$errorTexts .= "Käyttäjätunnus on jo käytössä ";
}

if (!$hasError)
{
	$dataconnection->query("INSERT INTO `user_accounts` (`username`, `password`, `registered_ip`, `email`, `timestamp_registered`, `welcomeHelp`) VALUES ('$username', '" . password_hash($password, PASSWORD_BCRYPT) . "', '" . $_SERVER['REMOTE_ADDR'] . "', '$email', '" . time() . "', '1')");

	//$dataconnection->query("UPDATE invites SET nu_mail = '$email' AND is_used = '1' WHERE invite_code = '$inviteCode' LIMIT 1");
	
$dataconnection->query("DELETE FROM `invites` WHERE (`invite_code`='" . $inviteCode . "')");

$_SESSION["LOGIN_USER"] = $username;

	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
	exit;
	
}

}

$errorMessageStyling = '<div class="alert alert-danger">
  <b>Korjaa seuraavat puutteet:</b> ' . $errorTexts . '
</div><script>
$(document).ready(function() {
$( "#nextStep" ).show();
});
</script>';

include("includes/header.php");

?>
<script>
$(document).ready(function() {
	var checkLock = false;
	

	var options =  {
  onComplete: function(cep) {
	  
	  if (checkLock)
	{
		return;
	}
    var validateInvite = $.post( "/ajax/registration/check_invite", { code: cep } , function() {
	

})
  .done(function(data) {
   	
	if (data.errorCode == -1)
	{
	$( "#inviter" ).removeClass( "has-warning" );
	$( "#inviter" ).removeClass( "has-error" );
	$( "#inviter" ).addClass( "has-success" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-remove" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-warning-sign" );
	$( "#inviterResponseIcon" ).addClass( "glyphicon-ok" );
	$( "#inviterResponse" ).html(data.statusMessage);
	
	if (data.isSuccess)
	{
			$( "#nextStep" ).show();
	}
	
	checkLock = true;
	}

	else
	{
	$( "#inviter" ).removeClass( "has-warning" );
	$( "#inviter" ).removeClass( "has-success" );
	$( "#inviter" ).addClass( "has-error" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-ok" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-warning-sign" );
	$( "#inviterResponseIcon" ).addClass( "glyphicon-remove" );
	$( "#inviterResponse" ).html(data.statusMessage);
	$( "#nextStep" ).hide();
	checkLock = true;
	}
		 
  })
  .fail(function() {
	 $( "#inviter" ).removeClass( "has-warning" );
    $( "#inviter" ).removeClass( "has-success" );
	$( "#inviter" ).addClass( "has-error" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-ok" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-warning-sign" );
	$( "#inviterResponseIcon" ).addClass( "glyphicon-remove" );
	$( "#inviterResponse" ).html("Tapahtui järjestelmävirhe. Ole hyvä ja yritä myöhemmin uudelleen.");
	$( "#nextStep" ).hide();
  })
  .always(function() {
    
});
  },
  onKeyPress: function(cep, event, currentField, options){

  },
  onChange: function(cep){
	  if (cep.length < 27)
	  {
	 $( "#inviter" ).removeClass( "has-success" );
	$( "#inviter" ).removeClass( "has-error" );
	$( "#inviter" ).removeClass( "has-warning" );
		$( "#inviterResponseIcon" ).removeClass( "glyphicon-ok" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-remove" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-warning-sign" );
	$( "#inviterResponse" ).html("Lukupoppeli on salainen palvelu joten tarvitset kutsukoodin luodaksesi käyttäjätilin.");
	checkLock = false;
	$( "#nextStep" ).hide();
	  }

  },
  onInvalid: function(val, e, f, invalid, options){
    var error = invalid[0];
    $( "#inviter" ).removeClass( "has-success" );
	$( "#inviter" ).removeClass( "has-error" );
	$( "#inviter" ).addClass( "has-warning" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-ok" );
	$( "#inviterResponseIcon" ).removeClass( "glyphicon-remove" );
	$( "#inviterResponseIcon" ).addClass( "glyphicon-warning-sign" );
	$( "#inviterResponse" ).html("EI KÄY! Se oli kielletty merkki!");
  }
};

  $('#inviteCode').mask('AAAAAA-AAAAAA-AAAAAA-AAAAAA', options);
  
  $( "#registration_username" ).blur(function() {
	  var checkUsernameAvailability = $.post( "/ajax/registration/check_availability", { username: $("#registration_username").val() }, function() {

})
  .done(function(data) {
	  
	  if (data.errorCode > 0 && !data.isSuccess)
	  {
		     $( "#usernameAvailabilityCheck" ).removeClass( "has-success" );  
			 $( "#usernameAvailabilityCheck" ).addClass( "has-error" );  
	  }

   $( "#usernameACStatus" ).html(data.statusMessage);
 
  })
  .fail(function() {
    alert( "error" );
  })
  .always(function() {
   // alert( "finished" );
});
	  });
});
</script>

<div class="container">
<?php 
if ($hasError)
{
echo $errorMessageStyling;
}
 ?>

<form class="form-horizontal" id="registrationForm" method="post">
<fieldset>

<!-- Form Name -->
<legend>Rekisteröityminen</legend>

<!-- Text input-->
<div class="form-group has-feedback" id="inviter">
  <label class="col-md-4 control-label" for="inviteCode">Kutsukoodi</label>  
  <div class="col-md-4">
  <input id="inviteCode" name="inviteCode" type="text" placeholder="XXXXXX-XXXXXX-XXXXXX-XXXXXX" class="form-control input-md" required="" value="<?php echo $inviteCode; ?>">
   <span class="glyphicon form-control-feedback" id="inviterResponseIcon"></span>
  <span class="help-block" id="inviterResponse">Lukupoppeli on salainen palvelu joten tarvitset kutsukoodin luodaksesi käyttäjätilin.</span>  
  </div>
</div>

<!-- Text input-->
<div style="display: none;" id="nextStep">
<div class="form-group" id="usernameAvailabilityCheck">
  <label class="col-md-4 control-label" for="registration_username">Käyttäjänimi</label>  
  <div class="col-md-4">
  <input id="registration_username" name="registration_username" type="text" placeholder="" class="form-control input-md" required="" value="<?php echo $username; ?>">
  <span class="help-block" id="usernameACStatus">Tämä on sinun nimimerkkisi, joka näkyy julkisesti muun muassa kommentoidessasi uutisartikkeleita.</span>  
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="registration_password">Salasana</label>
  <div class="col-md-4">
    <input id="registration_password" name="registration_password" type="password" placeholder="" class="form-control input-md" required="">
    <span class="help-block">Salasanan täytyy olla vähintään 6 merkkiä pitkä, sisältää isoja ja pieniä kirjaimia, erikoismerkkejä ja ei saisi mielellään perustua mihinkään sanakirjan sanaan.</span>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="registration_password_double">Salasana uudelleen</label>
  <div class="col-md-4">
    <input id="registration_password_double" name="registration_password_double" type="password" placeholder="" class="form-control input-md" required="">
    <span class="help-block">Kirjoitusvirheiden varalta kirjoita salasanasi uudelleen.</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="registration_email">Sähköpostiosoite</label>  
  <div class="col-md-4">
  <input id="registration_email" name="registration_email" type="text" placeholder="" class="form-control input-md" required="" value="<?php echo $email; ?>">
  <span class="help-block">Voit tilata uuden salasanan tähän sähköpostiosoitteeseen, jos satut unohtamaan sen.</span>  
  </div>
</div>



<!--
<div class="form-group">
  <label class="col-md-4 control-label" for="prependedcheckbox">Prepended Checkbox</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">     
          <input type="checkbox">     
      </span>
      <input id="prependedcheckbox" name="prependedcheckbox" class="form-control" type="text" placeholder="placeholder">
    </div>
    <p class="help-block">help</p>
  </div>
</div>
-->
<div class="col-md-4">
<input type="submit" class="btn btn-info" value="Luo käyttäjätunnus">
</div>
</div>


</fieldset>
</form>

</div>

<?php
include("includes/footer.php");
?>