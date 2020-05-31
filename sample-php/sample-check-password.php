<?php
	// http://api.lognpass.com/lognpass-php/sample-check-password.php?password=97738233911
	require_once(dirname(__FILE__)."/lognpass-inc.php");
	lognpass_set_temp_dir(dirname(__FiLE__)."/temp");
	$phrase = "les chaussettes de l'archiduchesse sechent a l'abri des vents et marees dans l'arriere salle du chateau en flammes.";
	print ("<p>Log'N Pass phrase : ".$phrase."</p>");
	$phrase_md5 = md5($phrase);
	$password = (isset($_GET["password"]))?trim($_GET["password"]):"";
	print ("<p>Password : ".$password."</p>");
	print ("<p>Acces : ".((lognpass_check_password($phrase_md5,$password))?"autorise":"refuse")."</p>");
?>