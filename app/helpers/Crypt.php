<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Crypt {
	const ALGO_BLOWFISH = 1;
	const ALGO_AES = 2;

	public static function encrypt($input, $algorithm = Crypt::ALGO_AES, $key = CRYPT_KEY) {
		if ($algorithm === Crypt::ALGO_BLOWFISH)
			$input = @openssl_encrypt($input, 'BF-CBC', $key);
		else
			$input = @openssl_encrypt($input, 'AES-256-CBC', $key);
		return Crypt::limitedCharsEncode($input);
	}

	public static function decrypt($input, $algorithm = Crypt::ALGO_AES, $key = CRYPT_KEY) {
		$input = Crypt::limitedCharsDecode($input);
		if ($algorithm === Crypt::ALGO_BLOWFISH)
			return @openssl_decrypt($input, 'BF-CBC', $key);
		else
			return @openssl_decrypt($input, 'AES-256-CBC', $key);
	}

	private static function limitedCharsEncode($input) {
		return preg_replace('/\//', '&', base64_encode($input));
	}

	private static function limitedCharsDecode($input) {
		return base64_decode(preg_replace('/\&/', '/', $input));
	}
}