<?php
function GetUserData($userName, $column)
{
return $dataconnection->query("SELECT $column FROM papers WHERE username = '$userName' LIMIT 1")->fetch_object()->$column;
}
?>