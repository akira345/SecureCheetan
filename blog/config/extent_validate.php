<?php
//ちいたんのバリデートクラスを拡張する。
class CMyValidate extends CValidate
{
    function CMyValidate()
    {
		//コンストラクタ
    }
	/**
	*URLを構成するかチェックする
	*@param string $data
	*@param string $errmsg
	*@return boolian
	*/
	public function chk_url($data,$errmsg = ""){
		return $this->_check(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',$data),$errmsg);
	}
}


