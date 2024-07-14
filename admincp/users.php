<?php
require_once("../configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}
if (!$_SESSION["IS_ADMIN"])
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
	exit;
}

include("../includes/header.php");

?>

<div class="container">
  <h2>Käyttäjät</h2>
  <p>Hallitse LukuPoppeli-palveluun luotuja käyttäjiä. <a href="/administration/invites">Kutsukoodi-toiminto</a></p>
  <div class="alert alert-danger" id="notification" style="display: none; !important">

</div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th># ID-numero</th>
        <th>Käyttäjänimi</th>
        <th>Käyttöoikeus</th>
		 		<th>Luomispäivä</th>
		  <th>Toiminnot</th>
      </tr>
    </thead>
    <tbody>
	<?php

function getPrettyPermission($rawPermission)
{
	switch ($rawPermission)
	{
		case "ADMINISTRATOR":
		{
			return "Ylläpitäjä";
		}
		case "READER":
		{
			return "Lukija";
		}
	}
}
$dataquery = "SELECT * FROM user_accounts ORDER BY id";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0)
{
	while($useracc = $dataresult->fetch_assoc())
	{
			echo '<tr class="inviteRow" data-id="' . $useracc["id"] . '">
        <td>' . $useracc["id"] . '</td>
        <td>' . utf8_encode($useracc["username"]) . '</td>
        <td>' . getPrettyPermission($useracc["permission"]) . '</td>
  <td>' . date('j.n.y G:i:s', $useracc["timestamp_registered"]) . '</td>
	<td><button type="button" class="btn btn-info">Tiedot</button> <button type="button" class="btn btn-danger">Poista</button></td>
		</tr>';



	}
}
	?>
    </tbody>
  </table>
</div>

<?php
include("../includes/footer.php");
?>
