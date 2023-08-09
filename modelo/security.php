<?php
	define('METHOD','AES-256-CBC');
	define('SECRET_KEY', '$aC1ONTRA1234zsv');
	define('SECRET_IV','101712');
	class ENCR
	{	
		public static function encript($string)
		{	$output=false;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV),0,16);
			$output=openssl_encrypt($string, METHOD, $key,0,$iv);
			$output=base64_encode($output);
			return $output;
		}	
		public static function descript($string)
		{	$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV),0,16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key,0,$iv);
			return $output;
		}	
	}
?>