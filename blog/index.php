<?php
//����ե�����ȥե졼�������ɤ߹���
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
	
function action( &$c )
{
//���������
	//�ӥ塼�˥ǡ����򥻥å�
	$c->set( "datas", $c->blog_data->find( "", "modified DESC" ) );
	//�ƥ�ץ졼������
	$c->SetViewFile( "./view/index_.html" );
}
?>