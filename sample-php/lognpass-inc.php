<?php
	// Log'n Pass
	//
	//	23/02/2015, pprem : création du fichier
	
	// lognpass_set_temp_dir : set temporary directory to store old password and protect against key logger
	//		$tempdir => temporary directory where Log'n Pass can store old passwords
	global $lognpass_temp_dir;
	$lognpass_temp_dir = "";
	function lognpass_set_temp_dir($tempdir)
	{
		global $lognpass_temp_dir;
		if (is_dir($tempdir))
		{
			$lognpass_temp_dir = $tempdir;
		}
	}

	// lognpass_get_password : generate a password
	//		$phrase_md5 => the md5 crypted user's lognpass phrase
	//		$api_key, $api_num => parameters from api.lognpass.com/get
	//		return the password
	function lognpass_get_password($phrase_md5,$api_key,$api_num)
	{
		$pass = "";
		$md5 = md5($phrase_md5.$api_key);
		for ($i = 0; $i < strlen($md5); $i++)
		{
			$c = substr($md5,$i,1);
			if (("0" <= $c) && ($c <= "9"))
			{
				$pass .= $c;
			}
		}
		$i = 0;
		while (strlen($pass) < 5)
		{
			$pass .= $i;
			$i++;
		}
		if (strlen($pass) > 9)
		{
			$pass = substr($pass,0,9);
		}
		return strlen($pass).$pass.$api_num;
	}

	// lognpass_check_password : check a password
	//		$phrase_md5 => the md5 crypted user's lognpass phrase
	//		$password => the password to check
	//		return TRUE if the password is good, FALSE if it isn't
	function lognpass_check_password($phrase_md5,$password)
	{
		global $lognpass_temp_dir;
		$password_ok = false;
		$nb = intval(substr($password,0,1));
		$api_num = substr($password,$nb+1);
		$data = @file_get_contents("http://".$api_num.".lognpass.net/get/");
		if ("" != $data)
		{
			if (is_object($api = json_decode($data)))
			{
				if (isset($api->key) && (isset($api->num)) && ($api_num == $api->num))
				{
					$password_ok = ($password == lognpass_get_password($phrase_md5, $api->key, $api->num));
				}
			}
		}
		if (($password_ok) && ("" != $lognpass_temp_dir) && (is_dir($lognpass_temp_dir)))
		{
			$fn = $lognpass_temp_dir."/".$phrase_md5.".lnp";
			if (false !== ($old = @file_get_contents($fn)))
			{
				$password_ok = ($password != $old);
			}
			@file_put_contents($fn,$password);
		}
		return $password_ok;
	}
?>