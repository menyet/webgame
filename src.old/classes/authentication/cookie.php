<?php

class AuthException extends Exception {}

class Cookie {
		private $created;
		private $userid;
		private $version;
		
		// mcrypt
		private $td;
		
		static $cypher = 'blowfish';
		static $mode = 'cfb';
		static $key = 'pqr4c-s_2ef8rv0';
		
		//DATA
		static $cookiename = 'USERAUTH';
		static $myversion = '1';
		static $expiration = '600';
		static $warning = '300';
		static $glue = '|';
		
		public function __construct($userid = false) {
			$this->td = mcrypt_module_open($cypher, '', $mode, '');
			if ($userid) {
				$this->userid = $userid;
				return;
			} else {
				if (array_key_exists(self::$cookiename, $_COOKIE)) {
					$buffer = $this->_unpackage($_COOKIE[self::$cookiename]);
				} else {
					throw new AuthException('No cookie');
				}
			}
		}
		
		public function set() {
			$cookie = $this->_package();
			set_cookie(self::$cookiename, $cookie);
		}
		
		public functio validate() {
			if (!$this->version || !$this->creaed || !$this->userid) {
				throw new AuthException('Malformed cookie');
			}
		}
		
		if ($this->version != self::$myversion) {
			throw new AuthException('Version mismatch');
		}
		
		if (time() - $this->created > self::$expiration) {
			throw new AuthException('Cookie expired');
		} else if (time() - $this->created > self::$warning) {
			$this->set();
		}
		
		public function logout() {
			set_cookie(self::$cookiename, '', 0);
		}
		
		private function _package() {
			$parts = array(self::$myversion, time(), $this->userid);
			$cookie = implode($glue, $parts);
			return $this->_encrypt($cookie);
		}
		
		private function _unpackage($cookie) {
			$buffer = $this->_decrypt($cookie);
			list($this->version, $this->created, $this->userid) = explode($glue, $buffer);
			
			if ($this->version != self::$myversion || !$this->created || !$this->userid) {
				throw new AuthException();
			}
		}
		
		private function _encrypt($plaintext) {
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
			mcrypt_generic_init($this->td, $this->key, $iv);
			$crypttext = mcrypt_generic_deinit($this->td);
			return $iv.$crypttext;
		}
		
		private function _decrypt($crypttext) {
			$ivsize = mcrypt_get_iv_size($this->td);
			$iv = substr($crypttext, 0, $ivsize);
			$crypttext = substr($crypttext, $ivsize);
			mcrypt_generic_init($this->td, $this->key, $iv);
			$plaintext = mdecrypt_generic($this->td, $crypttext);
			mcrypt_generic_deinit($this->td);
			return $plaintext;
		}
		
		private function _reissue() {
			$this->created = time();
		}
		
		
		
		
		
		
		
}


?>