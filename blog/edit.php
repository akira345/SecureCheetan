<?php
//設定ファイルとフレームワークを読み込む
    require_once( "./config/config.php" );
    require_once( "../cheetan/cheetan.php" );

function action( &$c )
{
	$errmsg	= "";
    if( count( $_POST ) )
    {
		$errmsg	= $c->blog_data->validatemsg( $c->data["blog"] );
		if( $errmsg == "" )
		{
	        $c->blog_data->update( $c->data["blog"] );
			$c->redirect( "." );
		}
    }
	$c->set( "errmsg", $errmsg );
    $c->set( "data", $c->blog_data->findone( "id=" . $_GET["id"] ) );
	//テンプレート設定
	$c->SetViewFile( "./view/edit.html");
}
