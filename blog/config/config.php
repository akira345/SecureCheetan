<?php

//セッションを使用するかどうかを設定します。
//デフォルト(関数を宣言しない時)はセッションを使用するので、
//使用したくない時は関数を宣言し、falseを返してください。
function is_session()
{
	return false;
}

//DBの接続設定関数
//第一引数は複数の接続を利用したいときの識別子です。
//特に１つしか利用しない場合は指定をしないとデフォルトの設定で呼び出されます。
//識別子、ホスト、ID、PW、DB名,クライアントのキャラクタセット、DBの種類、ポート番号
//クライアントのキャラクタセットは、以下の通り。
//
//MySqlの場合、mysql_set_charset()がサポートするキャラクタセット(PHP5.2.3以上、MySQL5.0.7以上)
//PostgreSQLの場合、pg_set_client_encoding()がサポートするキャラクタセット
//txtsqlの場合は対応していませんので、無指定です。
//DBの種類は、DBKIND_MYSQL,DBKIND_PGSQL,DBKIND_TEXTSQLで、省略時はDBKIND_MYSQLが選択されます。
//(これらの定数は、database.phpに定義されています)
//ポート番号は省略可能で、省略時はデフォルトが設定されます。
function config_database( &$db )
{
	$db->add( "", "localhost", "root", "", "cheetandb","ujis" );
}
//モデルを定義する
function config_models( &$controller )
{
//dirname(__FILE__) はこのファイルのパスが入る（最後の\はなし)
	$controller->AddModel( dirname(__FILE__) . "/../model/blog_data.php" );
	$controller->AddModel( dirname(__FILE__) . "/../model/cheetan_session.php");
}
//コンポーネントを定義する
function config_components( &$controller )
{
//ようはユーザ定義クラスを取り込む。第２引数はクラス名を指定する。
//ファイル名と第３引数は同じにする？
//使用方法はこんな感じ
//$c->mylib->cr_to_br( $in_str );
//	$controller->AddComponent(  dirname(__FILE__) . "/../component/mylib.php", 'mylib', 'mylib' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/session.php", 'Session', 'Session' );
//	$controller->AddComponent(  dirname(__FILE__) . "/../component/cookie.php", 'Cookie', 'cookie' );

}
//アクション（コントローラ）が呼ばれる直前に実行される関数
//全コントローラ共通で前処理をさせたい場合ここに設定する
function config_controller( &$controller )
{
	if($controller->GetSession)
	{
		//セッションの妥当性をチェックする。
		$controller->session->set_secret_words("hogehogefoobar");	//フィンガープリントの秘密の文字列
		$controller->session->chk_session();
	}

	//デバックモード
	$controller->SetDebug( true );

	//文字コードを設定する(HTML側)無指定時はEUC-JPです。
	//POST、GET文字列のエンコードをこの文字コードかどうかチェックします
	$controller->setEncoding('EUC-JP');
	$controller->chk_encoding();	//外部から来る変数の文字コードチェックを行う。


	//共通テンプレート読み込み
	$controller->SetTemplateFile( dirname(__FILE__) ."/../view/template.html" );
}
//ちいたんのコントローラクラスを拡張する
function config_controller_class()
{
    require_once( 'extent_controler.php' );
    return 'CMyController';
}
//ちいたんのビュークラスを拡張する
function config_view_class()
{
    class CMyView extends CView
    {

    }
    return 'CMyView';
}

//アクションが呼ばれた直後に呼ばれる関数です。
//ここでは、header関数で文字コードの送信を行っています。
function after_action( &$controller )
{
	//文字コード設定
	header( 'Content-Type: text/html; charset=' . $controller->getEncoding  );
	//キャッシュさせない
	header("Expires: Wed, 10 Jan 1990 01:01:01 GMT");
	header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
}

//ビューが呼ばれた直後に呼ばれる関数です。
//特に何をするべきとうい関数ではありませんので、必要に応じて使用します。
function after_render( &$controller )
{

}

//ログインチェック関数
//コントローラis_secure関数がtrueの時動く
//認証処理を入れる。
function check_secure( &$controller )
{
//ログインセッションが無かったら、index.phpへリダイレクト
//    if( empty( $controller->session->get("USER") ) )
//    {
//        $controller->redirect( "index.php" );
//    }
}
//セッション開始関数
//セッションが有効の時動く
//session_startの前に処理をしたい場合追加
//関数が未定義の場合は、session_start()が呼ばれる
function after_session_start( &$controller )
{
	//セッション名を変更してみる
	session_name ("BlogID");

}


//////////////////////////////////////////
//ちいたんのサニタイズクラスを拡張する
function config_sanitize_class()
{
    require_once( 'extent_sanitize.php' );
    return 'CMySanitize';
}
//ちいたんのバリデートクラスを拡張する
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