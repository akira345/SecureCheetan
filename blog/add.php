<?php
//����ե�����ȥե졼�������ɤ߹���
    require_once( "./config/config.php" );
    require_once( "../cheetan/cheetan.php" );

function action( &$c )
{
//���������
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
	//�ƥ�ץ졼������
	$c->SetViewFile( "./view/add.html");

}
?>