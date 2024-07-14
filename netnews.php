<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}

include("includes/header.php");

?>
<div class="container">
<h2>Tämä osio on työn alla</h2>
Valitsemasi sivu on valitettavasti tällä hetkellä kesken eikä sitä ole vielä saatu valmiiksi. Ole hyvä ja tule myöhemmin uudelleen.
<a href="/"><h4>Palaa etusivulle</h4></a>
</div>

<?php
include("includes/footer.php");
?>