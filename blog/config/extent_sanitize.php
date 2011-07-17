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
	//入力段階ではサニタイズしない。（setメソッドでビューに渡すときに行っている）
		return $data;
	}
}


?>