<?php
//��������ΥХ�ǡ��ȥ��饹���ĥ���롣
class CMyValidate extends CValidate
{
    function CMyValidate()
    {
		//���󥹥ȥ饯��
    }
	//URL�������뤫�����å�����
	function chk_url($data,$errmsg = ""){
		return $this->_check(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',$data),$errmsg);
	}
}


?>