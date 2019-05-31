<?php
//設定ファイルとフレームワークを読み込む
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
	
function action( &$c )
{
//アクション
	//ビューにデータをセット
	$c->set( "datas", $c->blog_data->find( "", "modified DESC" ) );
	//テンプレート設定
	$c->SetViewFile( "./view/index_.html" );
}
