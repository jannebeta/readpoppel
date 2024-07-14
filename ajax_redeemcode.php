<?php
require_once("configuration.php");
$code = mysqli_real_escape_string($dataconnection, $_POST["code"]);

echo '<p><p class="titles">Luodaan uusi käyttäjä</span></p>';
echo '<p><div id="infoArea"><i>Kutsukoodi aktivoidaan vasta onnistuneen rekisteröinnin jälkeen.</i></div></p>';
echo '<p><b>Käyttäjänimi:</b></p>';
echo '<p><input type="text" id="register_username" class="regfield"></p>';
echo '<p><b>Salasana:</b></p>';
echo '<p><input type="password" id="register_password" class="regfield"></p>';
echo '<p><b>Salasana uudelleen:</b></p>';
echo '<p><input type="password" id="register_password_again" class="regfield"></p>';
echo '<p><b>Sähköpostiosoite:</b></p>';
echo '<a href="#" onclick="FinishRegistration(); return false;" class="invite_button" style="float: right;">Viimeistele rekisteröinti >></a>';
echo '<p><input type="text" id="register_email" class="regfield"></p>';
?>