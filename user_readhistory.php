<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}

include("includes/header.php");

	$results = $dataconnection->prepare("SELECT user_readhistory.paperBrand, user_readhistory.paperId, user_readhistory.timestamp_start, user_readhistory.currentPage, papers.paperTitle, papers.pageCount FROM user_readhistory, papers WHERE user_readhistory.user_id='" . $_SESSION["LOGIN_UID"] . "' AND papers.paperID = user_readhistory.paperId AND papers.paperBrand = user_readhistory.paperBrand GROUP BY papers.paperTitle, papers.pageCount LIMIT 100");
$results->execute(); 
$results->bind_result($paperBrand, $paperID, $timestampStart, $currentPage, $ptitle, $pcount); 


?>
<div class="container">
  <h2>Lukuhistoria</h2>
  Tältä sivulta näet lukuhistoriasi.
    <table class="table table-hover">
    <thead>
      <tr>
        <th>Tyyppi</th>
        <th>Lehden nimi</th>
        <th>Numero</th>
		 <th>Sivuja luettu</th>
		  <th>Päiväys</th>
		  <th></th>
      </tr>
    </thead>
    <tbody>
	
	<?php
	while($results->fetch()){ 
		{
		
			
			echo '<tr>
        <td>Lehti</td>
		<td><a href="/paper/' . $paperBrand . '">' . ucfirst($paperBrand) . '</a></td>
		<td>' . $ptitle . '</td>
		<td>' . $currentPage . ' / ' . $pcount . '</td>
		<td>' . date('j.n.Y G:i', $timestampStart) . '</td>
		<td><a href="/paper/' . $paperBrand . '/read/' . $paperID . '?page=' . $currentPage . '">Lue uudestaan >></a></td>
		</tr>';
		}
	}
		
		?>
	
	      </tbody>
  </table>
</div>

<?php
include("includes/footer.php");
?>