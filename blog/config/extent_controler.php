<?php
//ちいたんのコントローラクラスを拡張する。
class CMyController extends CController
{
	protected $encoding = "UTF-8";	//エンコーディング
    /**
     * コンストラクタ
     *
     * 
     *  
     */
    function CMyController()
    {
		//コンストラクタ
		CController::CController();
    }
    /**
     * 外から来る変数の文字コードをチェックする
     *
     * 
     *  @return boolian
     */
	public function chk_encoding()
	{
		//外から来る変数の文字コードをチェックする
		$vars = array($_GET, $_POST, $_COOKIE, $_SERVER, $_REQUEST);
		array_walk_recursive($vars, array($this,"_validate_encoding"));
	}
    /**
     * 文字コードをチェックする
     *
     * @param string $val 
     *  @param string $key
     */
	private function _validate_encoding($val, $key) {
	    if (!mb_check_encoding($key,$this->encoding) || !mb_check_encoding($val,$this->encoding)) {
	        trigger_error('Invalid charactor encoding detected.');
	        exit;
	    }
	}
    /**
     * viewの文字コードをセットする
     *
     * @param string $encode
     * @return boolian
     */
	public function setEncoding($encode = "UTF-8"){//デフォルトはUTF-8にする
		$this->encoding = $encode;
	//htmlentitiesやhtmlの文字コード指定で使える文字コード指定かチェック
		$chars = array(	"ISO-8859-1",
						"ISO-8859-15",
						"UTF-8",
						"CP866",
						"KOI8-R",
						"BIG5",
						"GB2312",
						"BIG5-HKSCS",
						"SHIFT_JIS",
						"EUC-JP",
					);
		in_array(strtoupper($this->encoding),$chars) or die ("Unknown charset");

	}
    /**
     * viewの文字コードを取得する
     *
     * 
     * @return string
     */
	public function getEncoding(){
		return $this->encoding;
	}
    /**
     * 文字列のサニタイズを行う
     *
     * @param string $value
     * @return boolian
     */
	private function _setEscape($value){
	//http://soft.fpso.jp/develop/php/entry_1891.htmlを参考に作成してみた
		if (is_string($value) === true) {
			$value = htmlentities($value, ENT_QUOTES,$this->encoding);
		} elseif (is_array($value) === true) {
			$value = array_map(array($this,"_setEscape"),$value);
		}
		return $value;
	}
    /**
     * ビューに値をセットする
     *
     * @param string $name, string $value, boolian $out_tag_flg
     * 
     */
	public function set( $name, $value, $out_tag_flg = FALSE )
	{
		//出力時にhtmlentitiesを通す。ただし、
		//タグ出力フラグがONの場合はスルーする
		If ($out_tag_flg == FALSE){
			$this->variables[$name] = $this->_setEscape($value);
		}else{
			$this->variables[$name]	= $value;
		}
	}
    /**
     * SQLのLOGを取得する(デバックモード時のみ)
     *
     * 
     * @return string
     */
	public function GetSqlLog()
	{
		return $this->_setEscape($this->db->GetSqlLog());
	}
    /**
     * リダイレクトを行う
     *
     * @param string $url, boolian $is301
     * 
     */
	public function redirect( $url, $is301 = FALSE )
	{
	#パーフェクトPHPより一部拝借

        if (!preg_match('#https?://#', $url)) {
            $protocol = $this->isSsl() ? 'https://' : 'http://';
            $host = $this->getHost();
            $base_url = dirname($this->getBaseUrl()) . '/';

            $url = $protocol . $host . $base_url . $url;
        }

		if( $is301 )
		{
			header( "HTTP/1.1 301 Moved Permanently" );
		}
		###madhatterさんのコードを拝借し、一部修正
		if(!$_COOKIE[session_name()]){
			$url .= ( strpos($url, "?") != false ? "&" : "?" ) . urlencode(session_name()) . "=" . $this->_setEscape(session_id());
		}
		###

		header( "Location: " . $url );
		exit();
	}
    /**
     * リクエストハンドラ
     *
     * 
     * 
     */
	public function RequestHandle()
	{
		$get	 = $this->_validate($_GET);
		$post	 = $this->_validate($_POST);
		$request = $this->_validate($_REQUEST);

		if (count($get))	 $this->get 	= $get;
		if (count($post))	 $this->post	= $post;
		if (count($request)) $this->request	= $request;
		$this->ModelItemHandle($get);
		$this->ModelItemHandle($post);
	}
    /**
     * 外部から来る変数のバリテーションを行う
     *
     * @param string $value
     * @return string
     */
	private function _validate($value){
		$value = $this->_delete_null_byte($value);
		return $value;
	}
    /**
     * nullbyteの除去を行う
     *
     * @param string $value
     * @return string
     */
	private function _delete_null_byte($value)
	{
	#パーフェクトPHPよりnullバイト除去
		if (is_string($value) === true) {
			$value = str_replace("\0","",$value);
		} elseif (is_array($value) === true) {
			$value = array_map(array($this,"_delete_null_byte"),$value);
		}
		return $value;
	}

    /**
     * リクエストメソッドがPOSTかどうか判定
     *
     * @return boolean
     */
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }
    /**
     * ホスト名を取得
     *
     * @return string
     */
    public function getHost()
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    /**
     * SSLでアクセスされたかどうか判定
     *
     * @return boolean
     */
    public function isSsl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }
        return false;
    }

    /**
     * リクエストURIを取得
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * ベースURLを取得
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $script_name = $_SERVER['SCRIPT_NAME'];

        $request_uri = $this->getRequestUri();

        if (0 === strpos($request_uri, $script_name)) {
            return $script_name;
        } else if (0 === strpos($request_uri, dirname($script_name))) {
            return rtrim(dirname($script_name), '/');
        }

        return '';
    }

    /**
     * PATH_INFOを取得
     *
     * @return string
     */
    public function getPathInfo()
    {
        $base_url = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();

        if (false !== ($pos = strpos($request_uri, '?'))) {
            $request_uri = substr($request_uri, 0, $pos);
        }

        $path_info = (string)substr($request_uri, strlen($base_url));

        return $path_info;
    }
    /**
     * CSRFトークンを生成
     *
     * @param string $form_name
     * @return string $token
     */
    protected function generateCsrfToken($form_name)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = isset($_SESSION[$key]) ? $_SESSION[$key] : array();
        if (count($tokens) >= 10) {	//同時に１０個までは保持する。超えたら古い物から消す
            array_shift($tokens);
        }

        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $_SESSION[$key] = $tokens;

        return $token;
    }

    /**
     * CSRFトークンが妥当かチェック
     *
     * @param string $form_name
     * @param string $token
     * @return boolean
     */
    protected function checkCsrfToken($form_name, $token)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = isset($_SESSION[$key]) ? $_SESSION[$key] : array();

        if (false !== ($pos = array_search($token, $tokens, true))) {
            unset($tokens[$pos]);
            $_SESSION[$key] = $tokens;

            return true;
        }

        return false;
    }

}


