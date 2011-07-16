<?php
class CBlog_data extends CModel
{
	var $validatefunc	= array(
							"title" => "notempty",
							"body" => "notempty"
							);
	var $validatemsg	= array(
							"title" => "Please input title.<br>",
							"body" => "Please input body.<br>"
							);
}
?>