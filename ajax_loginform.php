<?php
require_once("configuration.php");

echo '<p><p class="titles">Kirjaudu sisään</span></p>';
echo '<p><div id="infoArea"><i>Kirjautuminen vaatii voimassa olevat tunnukset.</i></div></p>';
echo '<p><b>Käyttäjänimi:</b></p>';
echo '<p><input type="text" id="login_username" class="regfield" style="font-size: 30px;"></p>';
echo '<p><b>Salasana:</b></p>';
echo '<p><input type="password" id="login_password" class="regfield" style="font-size: 30px;"></p>';
echo '<a href="#" onclick="LoginToService(); return false;" class="invite_button" style="float: right;">Kirjaudu sisään >></a>';
echo '<p><a href="#" onclick="ShowPasswordReset(); return false;" style="color: white;">Unohdin salasanani</a></p>';
?>