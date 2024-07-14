<?php
require_once("configuration.php");

if (isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
	exit;
}

$infoMessage = "";

if (isset($_POST["recv_mail"]))
{
	if (!filter_var($_POST["recv_mail"], FILTER_VALIDATE_EMAIL)) {
		$infoMessage = '<div class="alert alert-danger">
  Virheellinen sähköpostiosoite!
</div>';
	}
	else
	{
			$infoMessage = '<div class="alert alert-success">
  Lähetimme salasanan palautuslinkin sähköpostiin <b>' . htmlentities($_POST["recv_mail"]) . '</b>. Jos sitä ei näy niin tarkista roskapostikansio, tarkista myös oikeinkirjoitus. Ongelmatilanteissa ota yhteyttä ylläpitoon.
</div>';
	}

}
include("includes/header.php");
?>
			
<div class="container">
    <div class="row">
<?php
      echo $infoMessage;
	  ?>
        <div class="col-sm-10"><img src="/assets/images/illustrations/questionmarks.png" style="float: right;"><h2>Unohtuneen salasanan palautus</h2><p>Unohditko salasanasi? Tältä sivulta voit tilata tilisi sähköpostiin tai puhelimeesi unohtuneen salasanasi.</p>
		<div class="col-sm-4">
		<form method="post">
		 <div class="form-group">
    <label for="inputdefault">Anna tiliisi liitetty sähköpostiosoite:</label>
    <input class="form-control input-lg" id="inputdefault" name="recv_mail" type="text" placeholder="lassi.lukija@pikaposti.fi">
	  </div>
	<input type="submit" class="btn btn-info input-lg" value="Lähetä salasanan palautuslinkki">
	</form>
	

  </div>
		</div>
      
    </div>
</div>

<?php
include("includes/footer.php");
?>