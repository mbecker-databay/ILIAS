<?php

class ilWorkflowEngineSettingsGUI
{
	/** @var  ilObjWorkflowEngineGUI */
	protected $parent_gui;

	public function __construct(ilObjWorkflowEngineGUI $parent_gui)
	{
		$this->parent_gui = $parent_gui;
	}

	public function handle($command)
	{
		return "Hello, world";
	}
}