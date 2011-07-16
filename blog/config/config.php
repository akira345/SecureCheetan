<?php

//���å�������Ѥ��뤫�ɤ��������ꤷ�ޤ���
//�ǥե����(�ؿ���������ʤ���)�ϥ��å�������Ѥ���Τǡ�
//���Ѥ������ʤ����ϴؿ����������false���֤��Ƥ���������
function is_session()
{
	return false;
}

//DB����³����ؿ�
//��������ʣ������³�����Ѥ������Ȥ��μ��̻ҤǤ���
//�äˣ��Ĥ������Ѥ��ʤ����ϻ���򤷤ʤ��ȥǥե���Ȥ�����ǸƤӽФ���ޤ���
//���̻ҡ��ۥ��ȡ�ID��PW��DB̾,���饤����ȤΥ���饯�����åȡ�DB�μ��ࡢ�ݡ����ֹ�
//���饤����ȤΥ���饯�����åȤϡ��ʲ����̤ꡣ
//
//MySql�ξ�硢mysql_set_charset()�����ݡ��Ȥ��륭��饯�����å�(PHP5.2.3�ʾ塢MySQL5.0.7�ʾ�)
//PostgreSQL�ξ�硢pg_set_client_encoding()�����ݡ��Ȥ��륭��饯�����å�
//txtsql�ξ����б����Ƥ��ޤ���Τǡ�̵����Ǥ���
//DB�μ���ϡ�DBKIND_MYSQL,DBKIND_PGSQL,DBKIND_TEXTSQL�ǡ���ά����DBKIND_MYSQL�����򤵤�ޤ���
//(����������ϡ�database.php���������Ƥ��ޤ�)
//�ݡ����ֹ�Ͼ�ά��ǽ�ǡ���ά���ϥǥե���Ȥ����ꤵ��ޤ���
function config_database( &$db )
{
	$db->add( "", "localhost", "root", "", "cheetandb","ujis" );
}
//��ǥ���������
function config_models( &$controller )
{
//dirname(__FILE__) �Ϥ��Υե�����Υѥ�������ʺǸ��\�Ϥʤ�)
	$controller->AddModel( dirname(__FILE__) . "/../model/blog_data.php" );
	$controller->AddModel( dirname(__FILE__) . "/../model/cheetan_session.php");
}
//����ݡ��ͥ�Ȥ��������
function config_components( &$controller )
{
//�褦�ϥ桼��������饹������ࡣ�裲�����ϥ��饹̾����ꤹ�롣
//�ե�����̾���裳������Ʊ���ˤ��롩
//������ˡ�Ϥ���ʴ���
//$c->mylib->cr_to_br( $in_str );
//	$controller->AddComponent(  dirname(__FILE__) . "/../component/mylib.php", 'mylib', 'mylib' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/session.php", 'Session', 'Session' );
//	$controller->AddComponent(  dirname(__FILE__) . "/../component/cookie.php", 'Cookie', 'cookie' );

}
//���������ʥ���ȥ���ˤ��ƤФ��ľ���˼¹Ԥ����ؿ�
//������ȥ��鶦�̤��������򤵤�������礳�������ꤹ��
function config_controller( &$controller )
{
	if($controller->GetSession)
	{
		//���å�����������������å����롣
		$controller->session->set_secret_words("hogehogefoobar");	//�ե��󥬡��ץ��Ȥ���̩��ʸ����
		$controller->session->chk_session();
	}

	//�ǥХå��⡼��
	$controller->SetDebug( true );

	//ʸ�������ɤ����ꤹ��(HTML¦)̵�������EUC-JP�Ǥ���
	//POST��GETʸ����Υ��󥳡��ɤ򤳤�ʸ�������ɤ��ɤ��������å����ޤ�
	$controller->setEncoding('EUC-JP');
	$controller->chk_encoding();	//������������ѿ���ʸ�������ɥ����å���Ԥ���


	//���̥ƥ�ץ졼���ɤ߹���
	$controller->SetTemplateFile( dirname(__FILE__) ."/../view/template.html" );
}
//��������Υ���ȥ��饯�饹���ĥ����
function config_controller_class()
{
    require_once( 'extent_controler.php' );
    return 'CMyController';
}
//��������Υӥ塼���饹���ĥ����
function config_view_class()
{
    class CMyView extends CView
    {

    }
    return 'CMyView';
}

//��������󤬸ƤФ줿ľ��˸ƤФ��ؿ��Ǥ���
//�����Ǥϡ�header�ؿ���ʸ�������ɤ�������ԤäƤ��ޤ���
function after_action( &$controller )
{
	//ʸ������������
	header( 'Content-Type: text/html; charset=' . $controller->getEncoding  );
	//����å��夵���ʤ�
	header("Expires: Wed, 10 Jan 1990 01:01:01 GMT");
	header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
}

//�ӥ塼���ƤФ줿ľ��˸ƤФ��ؿ��Ǥ���
//�ä˲��򤹤�٤��Ȥ����ؿ��ǤϤ���ޤ���Τǡ�ɬ�פ˱����ƻ��Ѥ��ޤ���
function after_render( &$controller )
{

}

//����������å��ؿ�
//����ȥ���is_secure�ؿ���true�λ�ư��
//ǧ�ڽ���������롣
function check_secure( &$controller )
{
//�����󥻥å����̵���ä��顢index.php�إ�����쥯��
//    if( empty( $controller->session->get("USER") ) )
//    {
//        $controller->redirect( "index.php" );
//    }
}
//���å���󳫻ϴؿ�
//���å����ͭ���λ�ư��
//session_start�����˽����򤷤�������ɲ�
//�ؿ���̤����ξ��ϡ�session_start()���ƤФ��
function after_session_start( &$controller )
{
	//���å����̾���ѹ����Ƥߤ�
	session_name ("BlogID");

}


//////////////////////////////////////////
//��������Υ��˥��������饹���ĥ����
function config_sanitize_class()
{
    require_once( 'extent_sanitize.php' );
    return 'CMySanitize';
}
//��������ΥХ�ǡ��ȥ��饹���ĥ����
function config_validate_class()
{
    require_once( 'extent_validate.php' );
    return 'CMyValidate';
}





//////////////////////////////////////////



















function InitTime( $time )
{
	$year	= substr( $time, 0, 4 );
	$month	= substr( $time, 4, 2 );
	$day	= substr( $time, 6, 2 );
	$hour	= substr( $time, 8, 2 );
	$minute	= substr( $time, 10, 2 );
	$second	= substr( $time, 12, 2 );
	return "$year-$month-$day $hour:$minute:$second";
}
?>