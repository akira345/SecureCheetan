<?php
class CCheetan_session extends CModel
{
	function CCheetan_session()
	{
		CModel::CModel();
		session_set_save_handler(
			array( &$this, 'open' ),
			array( &$this, 'close' ),
			array( &$this, 'read' ),
			array( &$this, 'write' ),
			array( &$this, 'destroy' ),
			array( &$this, 'gc' )
		);
	}
	
	
	function open()
	{
		return true;
	}
	
	
	function close()
	{
		return true;
	}
	
	
	function read( $id )
	{
		$id			= $this->escape( $id );
		$data		= $this->findone( "id='$id'" );
		return $data['data'];
	}
	
	
	function write( $id, $data )
	{
		$id			= $this->escape( $id );
		$expires	= $this->escape( time() );
		$data		= $this->escape( $data );
		$query		= "REPLACE INTO $this->table VALUES( '$id', '$data', '$expires' )";
		return $this->query( $query );
	}
	
	
	function destroy( $id )
	{
		$id			= $this->escape( $id );
		return $this->del( "id='$id'" );
	}
	
	
	function gc( $max )
	{
		$old		= $this->escape( time() - $max );
		return $this->del( "expires<'$old'" );
	}
}