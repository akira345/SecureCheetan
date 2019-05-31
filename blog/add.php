<?php
//設定ファイルとフレームワークを読み込む
    require_once( "./config/config.php" );
    require_once( "../cheetan/cheetan.php" );

function action( &$c )
{
//アクション
	$errmsg	= "";
    if( count( $_POST ) )
    {
		$errmsg	= $c->blog_data->validatemsg( $c->data["blog"] );
		if( $errmsg == "" )
		{
	        $c->blog_data->insert( $c->data["blog"] );
			$c->redirect( "./" );
		}
    }
	
	$c->set( "errmsg", $errmsg ,"TRUE" );
	//テンプレート設定
	$c->SetViewFile( "./view/add.html");

}