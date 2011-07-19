<?php
//ちいたんのサニタイズクラスを拡張する。
class CMySanitize extends CSanitize
{
    function CMySanitize()
    {
		//コンストラクタ

    }
	/**
	*何もしない。setメソッドでビューに渡すときにサニタイズを行っている
	*@param string $data
	*
	*@return string
	*/
	function html( $data )
	{
		return $data;
	}
}


