<?php

class ilWorkflowEngineDefinitionsGUI
{
	/** @var  ilObjWorkflowEngineGUI */
	protected $parent_gui;

	public function __construct($parent_gui)
	{
		$this->parent_gui = $parent_gui;
	}

	public function handle($command)
	{
		return "Hello, world";
	}
}