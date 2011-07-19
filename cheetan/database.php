<?php
/*-----------------------------------------------------------------------------
cheetan is licensed under the MIT license.
copyright (c) 2006 cheetan all right reserved.
http://php.cheetan.net/
modified by akira 2011
-----------------------------------------------------------------------------*/
define( "DBKIND_MYSQL", "0" );
define( "DBKIND_PGSQL", "1" );
define( "DBKIND_TEXTSQL", "2" );


class CDatabaseConfig extends CObject
{
	var	$host;
	var	$user;
	var $pswd;
	var	$db;
	var $kind;
	var $port;
}


class CDatabase extends CObject
{
	var	$config			= array();
	var $connection		= array();
	var	$driver			= array();
	var $class			= array(
							"CDBMysql",
							"CDBPgsql",
							"CDBTextsql",
							);
	
//modified by akira	
//	function add( $name, $host, $user, $pswd, $db, $kind = 0, $port = 0 )
	function add( $name, $host, $user, $pswd, $db, $chrset, $kind = 0, $port = 0 )
	{
		$config					= array();
		$config['host']			= $host;
		$config['user']			= $user;
		$config['pswd']			= $pswd;
		$config['db']			= $db;
		$config['kind']			= $kind;
		$config['port']			= $port;
		//akira add
		$config['chrset']		= $chrset;
		//
		$this->config[$name]	= $config;
	}
	
	
	function &GetDriver( $name )
	{
		if( empty( $this->config[$name] ) )	return null;
		if( empty( $this->driver[$name] ) )
		{
//akira modified			$this->driver[$name]		= &new $this->class[$this->config[$name]['kind']]();
			$this->driver[$name]		= new $this->class[$this->config[$name]['kind']]();
		}
		if( empty( $this->connection[$name] ) )
		{
//akira modified			$this->connect( $name );
			$this->connect( $name ) or die;
		}
		
		return $this->driver[$name];
	}
	
	
	function connect( $name )
	{
		$config		= $this->config[$name];
		$connect	= $this->driver[$name]->connect( $config );
		if( !$connect )
		{
			print "Failed connect to $name.<br>";
			return false;
		}
		$this->connection[$name] = $connect;
		return true;
	}
	
	
	function query( $query, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		$ret	= $driver->query( $query, $this->connection[$name] );
		if( !$ret )
		{
			print "[DBERR] $query<BR>";
			exit;	//akira add
		}
		
		return $ret;
	}
	
	
	function GetFindQuery( $query, $condition = "", $order = "", $limit = "", $group = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->GetFindQuery( $query, $condition, $order, $limit, $group );
	}
	
	
	function findquery( $query, $condition = "", $order = "", $limit = "", $group = "", $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->findquery( $this->connection[$name], $query, $condition, $order, $limit, $group );
	}
	
	
	function findall($table, $condition = null, $order = "", $limit = "", $group = "", $name = "") {
		$driver	=& $this->GetDriver( $name );
		return $driver->findall($this->connection[$name], $table, $condition, $order, $limit, $group);
	}
	
	
	function find( $query, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->find( $query, $this->connection[$name] );
	}
	
	
	function count( $query, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->count( $query, $this->connection[$name] );
	}
	
	
	function insert( $table, $datas, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->insert( $table, $datas, $this->connection[$name] );
	}
	
	
	function getcount($table, $condition = null, $limit = "", $name = "") {
		$driver	=& $this->GetDriver($name);
		return $driver->getcount($this->connection[$name], $table, $condition, $limit);
	}
	
	
	function update( $table, $datas, $condition, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->update( $table, $datas, $condition, $this->connection[$name] );
	}
	
	
	function del( $table, $condition, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->del( $table, $condition, $this->connection[$name] );
	}
	
	
	function CreateCondition( $field, $value, $name = "" )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->CreateCondition( $field, $value );
	}
	
	
	function escape( $str, $name = '' )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->escape( $str );
	}
	
	
	function GetLastInsertId( $name = '' )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->GetLastInsertId();
	}
	
	
	function GetAffectedRows( $name = '' )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->GetAffectedRows();
	}
	
	
	function GetLastError( $name = '' )
	{
		$driver	=& $this->GetDriver( $name );
		return $driver->GetLastError();
	}
	
	
	function GetSqlLog()
	{
		$logs	= array();
		foreach( $this->driver as $name => $driver )
		{
			$logs[$name]	= $driver->GetSqlLog();
		}
		return $logs;
	}
	
	
	function describe($table, $name = '') {
		$driver =& $this->GetDriver($name);
		return $driver->describe($this->connection[$name], $table);
	}
}
?>