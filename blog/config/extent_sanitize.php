<?php
//��������Υ��˥��������饹���ĥ���롣
class CMySanitize extends CSanitize
{
    function CMySanitize()
    {
		//���󥹥ȥ饯��

    }
	function html( $data )
	{
		$data	= htmlentities( $data,ENT_QUOTES );
		return $data;
	}
}


?>