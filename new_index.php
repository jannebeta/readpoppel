<?php
require_once("configuration.php");

if (isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
	exit;
}

$errorMessage = "";

// Checking login details!

if (isset($_POST["username"]))
{
require_once("functions/passwordLib.php");

$username = mysqli_real_escape_string($dataconnection, $_POST["username"]);
$password = mysqli_real_escape_string($dataconnection, $_POST["password"]);

$passwordHash = @$dataconnection->query("SELECT password FROM user_accounts WHERE username = '$username' LIMIT 1")->fetch_object()->password;
$adminStatus = false;

if (password_verify($password, $passwordHash))
{
	/*	if ($dataconnection->query("SELECT accountDisabled FROM user_accounts WHERE username = '$username' LIMIT 1")->fetch_object()->accountDisabled == "1")
	{
		$errorMessage = '<div class="alert alert-danger">
 Käyttäjätili on poistettu käytöstä. Ota yhteyttä ylläpitoon.
</div>';
return;
	}
	*/

	$_SESSION["LOGIN_USER"] = $username;
	$_SESSION["LOGIN_UID"] = $dataconnection->query("SELECT id FROM user_accounts WHERE username = '$username' LIMIT 1")->fetch_object()->id;

	if ($dataconnection->query("SELECT permission FROM user_accounts WHERE username = '$username' LIMIT 1")->fetch_object()->permission == "ADMINISTRATOR")
	{
		$adminStatus = true;
	}
	$_SESSION["IS_ADMIN"] = $adminStatus;
 	$dataconnection->query("UPDATE user_accounts SET accessCount = accessCount + 1 WHERE username = '$username'");
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/");
die();

}
else
{
$errorMessage = '<div class="alert alert-danger">
 Käyttäjänimi tai salasana on virheellinen. Jos olet unohtanut salasanan, klikkaa <a href="">tästä</a>.
</div>';
}
}

include("includes/header.php");
?>
<script type="text/javascript" src="/js/cookie.js"></script>
<div class="container">
    <div class="row">
	<?php
      echo $errorMessage;
	  ?>

        <div class="col-sm-7"><img src="/assets/images/illustrations/salainen.png" style="width: 30%; float: right;"><h2>Tervetuloa palvelun Lukupoppeli etusivulle!</h2><p>Lukupoppeli on salainen palvelu, joka on tarkoitettu vain tietyille henkilöille. ;)</p> <p>Joten pääsyä tänne ei ole noin vain vaan tarvitset kutsukoodin tai käyttäjätunnuksen. </p>Mikäli omaat kutsukoodin saat luotua itsellesi uuden käyttäjätunnukset ja pääset sisään..<br><br>
		<a href="/registration"><h3>Minulla on kutsukoodi >></h3></a>
		</div>
        <div class="col-sm-5"><h2>Kirjaudu sisään</h2><div class="form-group">
<form method="post">
    <div class="col-md-5"> <label class="control-label" for="username">Käyttäjänimi</label>  <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required=""><label class="control-label" for="password">Salasana</label>  <input id="password" name="password" type="password" placeholder="" class="form-control input-md" required=""><br><a href="/forgot_password">Unohdin salasanani</a><br><br><input type="submit" class="btn btn-info" value="Kirjaudu"></div>
</form>
</div></div>
    </div>
</div>

<div id="cookie_directive_container" class="container" style="display: none">
					<nav class="navbar navbar-default navbar-fixed-bottom">

							<div class="container">
							<div class="navbar-inner navbar-content-center" id="cookie_accept">

									<a href="#" class="btn btn-default pull-right">Sulje</a>
									<p class="text-muted credit">
								Lukupoppeli käyttää evästeitä käyttäjän istunnon ylläpitämiseen. Lue meidän <a href="/cookies">keksipolitiikasta</a>.
									</p>
									<br>

							</div>
						</div>

					</nav>
			</div>

<?php
include("includes/footer.php");
?>
