<?php
class Session
{
//セッションクラス
//パーフェクトPHPのSessionクラスを参考にしました。

	protected $secret_words;

    /**
     * 指定したセッション変数の値をセット
     *
     * @param string $key 
     * @param string $value
     */
	function set($key,$value)
	{
		$_SESSION[$key] = $value;
	}
    /**
     * 指定したセッション変数の値を取得
     *
     * @param string $key
     * @return string
     */
	function get($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}
    /**
     * 指定したキーのセッション変数を削除
     *
     * @param string $key
     *  
     */
	function remove($key)
	{
		unset($_SESSION[$key]);
	}
	/**
	* 全セッション変数をクリア
	*
	*
	*
	*/
	function clear()
	{
		$_SESSION = array();
	}
	/**
	* セッションIDを再生成する
	*
	*@param boolean
	*
	*/
	function regenerate($flg = TRUE)
	{
		session_regenerate_id($flg);
		$this->chk_session;
	}
    /**
     * セッションIDの妥当性を確認する
     *
     * @return boolian
     *  
     */	
	private function chk_sessionid()
	{
		//http://www.asahi-net.or.jp/~wv7y-kmr/memo/php_security.html#PHP_Sessionより
		//セッションIDの妥当性をチェックする。
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
    /**
     * フィンガープリント用暗証セット
     *
     * @param string $words
     *  
     */
	function set_secret_words($words)
	{
		$this->secret_words = $words;
	}
    /**
     * フィンガープリントを取得する
     *
     * 
     *  @return string
     */
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
    /**
     * セッションの妥当性を確認する
     *
     * 
     *  @return boolian
     */
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
