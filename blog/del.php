<?php
//����ե�����ȥե졼�������ɤ߹���
    require_once( "./config/config.php" );
    require_once( "../cheetan/cheetan.php" );

function action( &$c )
{
    if( count( $_POST ) )
    {
        $c->blog_data->del( "id=" . $_POST["id"] );
		$c->redirect( "." );
    }
    $c->set( "data", $c->blog_data->findone( "id=" . $_GET["id"] ) );
	//�ƥ�ץ졼������
	$c->SetViewFile( "./view/del.html");
}
?>