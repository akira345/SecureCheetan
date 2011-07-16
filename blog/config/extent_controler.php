<?php
//��������Υ���ȥ��饯�饹���ĥ���롣
class CMyController extends CController
{
	protected $encoding = "EUC-JP";	//���󥳡��ǥ���

    function CMyController()
    {
		//���󥹥ȥ饯��
		CController::CController();
    }

	function chk_encoding()
	{
		//����������ѿ���ʸ�������ɤ�����å�����
		$vars = array($_GET, $_POST, $_COOKIE, $_SERVER, $_REQUEST);
		array_walk_recursive($vars, array($this,"_validate_encoding"));
	}

	private function _validate_encoding($val, $key) {
	    if (!mb_check_encoding($key,$this->encoding) || !mb_check_encoding($val,$this->encoding)) {
	        trigger_error('Invalid charactor encoding detected.');
	        exit;
	    }
	}

	function setEncoding($encode = "EUC-JP"){//�ǥե���Ȥϣţգäˤ���
		$this->encoding = $encode;
	//htmlentities��html��ʸ�������ɻ���ǻȤ���ʸ�������ɻ��꤫�����å�
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
	function getEncoding(){
		return $this->encoding;
	}
	function setEscape($value){
	//http://soft.fpso.jp/develop/php/entry_1891.html�򻲹ͤ˺������Ƥߤ�
		if (is_string($value) === true) {
			$value = htmlentities($value, ENT_QUOTES,$this->encoding);
		} elseif (is_array($value) === true) {
			$value = array_map(array($this,"setEscape"),$value);
		}
		return $value;
	}

	function set( $name, $value, $out_tag_flg = FALSE )
	{
		//���ϻ���htmlentities���̤�����������
		//�������ϥե饰��ON�ξ��ϥ��롼����
		If ($out_tag_flg == FALSE){
			$this->variables[$name] = $this->setEscape($value);
		}else{
			$this->variables[$name]	= $value;
		}
	}

	function redirect( $url, $is301 = FALSE )
	{
	#�ѡ��ե�����PHP�������Ҽ�

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
		###madhatter����Υ����ɤ��Ҽڤ�����������
		if(!$_COOKIE[session_name()]){
			$url .= ( strpos($url, "?") != false ? "&" : "?" ) . urlencode(session_name()) . "=" . $this->setEscape(session_id());
		}
		###

		header( "Location: " . $url );
		exit();
	}

	function RequestHandle()
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

	private function _validate($value){
		$value = $this->delete_null_byte($value);
		return $value;
	}

	#�ѡ��ե�����PHP���null�Х��Ƚ���

	function delete_null_byte($value)
	{
		if (is_string($value) === true) {
			$value = str_replace("\0","",$value);
		} elseif (is_array($value) === true) {
			$value = array_map(array($this,"delete_null_byte"),$value);
		}
		return $value;
	}
/**
 * Request.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
    /**
     * �ꥯ�����ȥ᥽�åɤ�POST���ɤ���Ƚ��
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
     * �ۥ���̾�����
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
     * SSL�ǥ����������줿���ɤ���Ƚ��
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
     * �ꥯ������URI�����
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * �١���URL�����
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
     * PATH_INFO�����
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
     * CSRF�ȡ����������
     *
     * @param string $form_name
     * @return string $token
     */
    protected function generateCsrfToken($form_name)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = isset($_SESSION[$key]) ? $_SESSION[$key] : array();
        if (count($tokens) >= 10) {	//Ʊ���ˣ����ĤޤǤ��ݻ����롣Ķ������Ť�ʪ����ä�
            array_shift($tokens);
        }

        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $_SESSION[$key] = $tokens;

        return $token;
    }

    /**
     * CSRF�ȡ����������������å�
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


?>