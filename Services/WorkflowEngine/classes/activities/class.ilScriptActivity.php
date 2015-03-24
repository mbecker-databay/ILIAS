<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilActivity.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilNode.php';

/**
 * Class ilScriptActivity
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *  
 * @ingroup Services/WorkflowEngine
 */
class ilScriptActivity implements ilActivity, ilWorkflowEngineElement
{
	/** @var ilWorkflowEngineElement $context Holds a reference to the parent object */
	private $context;

	private $method = '';

	/**
	 * Default constructor.
	 * 
	 * @param ilNode $a_context 
	 */
	public function __construct(ilNode $a_context)
	{
		$this->context = $a_context;
	}

	public function setMethod($a_value)
	{
		$this->method = $a_value;
	}

	/**
	 * Returns the value of the setting to be set.
	 * 
	 * @see $setting_value
	 * 
	 * @return string 
	 */
	public function getScript()
	{
		return $this->method;
	}

	/**
	 * Executes this action according to its settings.
	 * 
	 * @todo Use exceptions / internal logging.
	 *
	 * @return void
	 */
	public function execute()
	{
		$method = $this->method;
		$this->context->getContext()->$method($this);
	}

	/**
	 * Returns a reference to the parent node.
	 * 
	 * @return ilNode Reference to the parent node. 
	 */
	public function getContext()
	{
		return $this->context;
	}
}