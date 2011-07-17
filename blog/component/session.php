<?php
class Session
{
	protected $secret_words;

//パーフェクトPHPのSessionクラスを参考にしました。
	function set($key,$value)
	{
		$_SESSION[$key] = $value;
	}
	function get($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}
	function remove($key)
	{
		unset($_SESSION[$key]);
	}
	function clear()
	{
		$_SESSION = array();
	}
	function regenerate($flg = TRUE)
	{
		session_regenerate_id($flg);
		$this->chk_session;
	}
	//http://www.asahi-net.or.jp/~wv7y-kmr/memo/php_security.html#PHP_Sessionより
	//セッションIDの妥当性をチェックする。
	private function chk_sessionid()
	{
		$session_id = session_id();
		If (preg_match( '/^[-,0-9a-fA-Z]+$/D', $session_id )) {
			return TRUE;
		} 
		elseif ( $session_id === ""){
			return TRUE;
		}
		else {
			trigger_error( 'Session ID is invalid.', E_USER_ERROR );
			exit;
		}
	}
	function set_secret_words($words)
	{
		$this->secret_words = $words;
	}
	private function get_fingerprint()
	{
	    $fingerprint = $this->secret_words;

	    if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
	        $fingerprint .= $_SERVER['HTTP_USER_AGENT'];
	    }
	    if ( ! empty( $_SERVER['HTTP_ACCEPT_CHARSET'] ) ) {
	        $fingerprint .= $_SERVER['HTTP_ACCEPT_CHARSET'];
	    }
	    $fingerprint .= session_id();
	    return sha1( $fingerprint );
	}
	function chk_session()
	{
		if ($this->chk_sessionid){
			$fingerprint = $this->get_fingerprint();
			if (($this->get('_fingerprint')))
			{
				if($this->get('_fingerprint') === $fingerprint){
					return TRUE;
				}
				else{
					trigger_error( 'Session is invalid.', E_USER_ERROR );
					exit;
				}
			}
			else{
				$this->set('_fingerprint',$fingerprint);
				return TRUE;
			}
		}
	}

}
