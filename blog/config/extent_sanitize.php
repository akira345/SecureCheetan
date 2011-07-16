<?php
//ちいたんのサニタイズクラスを拡張する。
class CMySanitize extends CSanitize
{
    function CMySanitize()
    {
		//コンストラクタ

    }
	function html( $data )
	{
		$data	= htmlentities( $data,ENT_QUOTES );
		return $data;
	}
}


?>