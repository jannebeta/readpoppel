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
  <h2>Kutsukoodit</h2>
  <p>Luo, hallitse ja seuraa kutsukoodeja. <a href="">Luo kutsukoodi</a></p>
  <div class="alert alert-danger" id="notification" style="display: none; !important">
  
</div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th># ID-numero</th>
        <th>Kutsukoodi</th>
        <th>Luotu</th>
		 <th>Käytetty</th> 
		  <th>Toiminnot</th>
      </tr>
    </thead>
    <tbody>
	<?php

$dataquery = "SELECT * FROM invites ORDER BY id";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0) 
{
	while($invite = $dataresult->fetch_assoc()) 
	{
			echo '<tr class="inviteRow" data-id="' . $invite["id"] . '">
        <td>' . $invite["id"] . '</td>
        <td>' . utf8_encode($invite["invite_code"]) . '</td>
        <td> ' . date('j.n.Y', $invite["timestamp_created"]) . '</td>
		<td>' . ($invite["is_used"] == 1 ? "Kyllä" : "Ei") . '</td>
		<td><a href="#" class="deleteInvite" data-id="' . $invite["id"] . '">[Poista]</a></td>
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