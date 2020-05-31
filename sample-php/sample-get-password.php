<?php
	// http://api.lognpass.com/lognpass-php/sample-get-password.php
	require_once(dirname(__FILE__)."/lognpass-inc.php");
	$phrase = "les chaussettes de l'archiduchesse sechent a l'abri des vents et marees dans l'arriere salle du chateau en flammes.";
	print ("<p>Log'N Pass phrase : ".$phrase."</p>");
	$phrase_md5 = md5($phrase);
	print ("<p>MD5 : ".$phrase_md5."</p>");
	$password = "";
	$data = @file_get_contents("http://api.lognpass.com/get/");
	print ("<p>Log'N Pass data : ".$data."</p>");
	if ("" != $data)
	{
		if (is_object($api = json_decode($data)))
		{
			if (isset($api->key) && (isset($api->num)))
			{
				$password = lognpass_get_password($phrase_md5, $api->key, $api->num);
			}
		}
	}
	print ("<p>Password : ".$password."</p>");
	print ("<p><a href=\"sample-check-password.php?password=".$password."\" target=\"_blank\">tester ce mot de passe</a></p>");
?>