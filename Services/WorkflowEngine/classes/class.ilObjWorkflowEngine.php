<?php

class ilObjWorkflowEngine extends ilObject
{
	public function __construct($a_id = 0, $a_call_by_reference = true)
	{
		$this->type = "wfe";
		parent::__construct($a_id,$a_call_by_reference);
	}

	public static function getTempDir()
	{
		return ILIAS_DATA_DIR . '/' . CLIENT_ID . '/wfe/upload_temp/';
	}

	public static function getRepositoryDir()
	{
		return ILIAS_DATA_DIR . '/' . CLIENT_ID . '/wfe/repository/';
	}
}