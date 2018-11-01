<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Crypt {
	public static function encrypt($input, $algorithm = CRYPT_BLOWFISH, $key = CRYPT_KEY) {
		if ($algorithm === CRYPT_BLOWFISH)
			return @openssl_encrypt($input, 'BF-CBC', $key);
		else
			return @openssl_encrypt($input, 'AES-256-CBC', $key);
	}

	public static function decrypt($input, $algorithm = CRYPT_BLOWFISH, $key = CRYPT_KEY) {
		if ($algorithm === CRYPT_BLOWFISH)
			return @openssl_decrypt($input, 'BF-CBC', $key);
		else
			return @openssl_decrypt($input, 'AES-256-CBC', $key);
	}
}