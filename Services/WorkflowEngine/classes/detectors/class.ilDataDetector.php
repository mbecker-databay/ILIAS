<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilDetector.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilWorkflowEngineElement.php';

/**
 * ilDataDetector of the petri net based workflow engine.
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilDataDetector implements ilDetector, ilWorkflowEngineElement
{
	/**
	 * Holds a reference to the parent object
	 * 
	 * @var ilWorkflowEngineElement
	 */	
	private $context;

	/**
	 * Holds the current detection state.
	 * 
	 * @var boolean
	 */
	private $detection_state = false;

	/** @var string $name */
	protected $name;

	/** @var ilNode $source_node */
	protected $source_node;

	/** @var  string $var_name */
	protected $var_name;

	/**
	 * Default constructor.
	 * 
	 * @param ilNode $a_context 
	 */	
	public function __construct(ilNode $a_context)
	{
		$this->context = $a_context;
	}
	
	/**
	 * Returns the parent object. Type is ilNode, implements ilWorkflowEngineElement
	 * @return ilNode Parent node of this element.
	 */
	public function getContext()
	{
		return $this->context;
	}
	
	/**
	 * Trigger this detector. Params are an array. These are part of the interface
	 * but ignored here.
	 * 
	 * @todo Handle ignored $a_params.
	 * 
	 * @param array $a_params
	 * 
	 * @return boolean False, if detector was already satisfied before. 
	 */
	public function trigger($a_params)
	{
		return true;
	}

	/**
	 * Returns if the current detector state is satisfied or not.
	 * 
	 * @return boolean 
	 */
	public function getDetectorState()
	{
		$definitions = $this->getContext()->getContext()->getInstanceVars();

		foreach($definitions as $definition)
		{
			if($definition['id'] == $this->var_name)
			{
				break;
			}
		}
		$this->getContext()->setRuntimeVar(
			$definition['name'],
			$this->getContext()->getContext()->getInstanceVarById($this->var_name)
		);
		$this->getContext()->setRuntimeVar(
			$definition['id'],
			$this->getContext()->getContext()->getInstanceVarById($this->var_name)
		);
		$this->detection_state = true;
		return true;
	}

	/**
	 * Sets a new detector state. 
	 * 
	 * In this case, the only meaningful param is false, since it should only
	 * be set to true, if the detector was triggered.
	 * Reason this method exists, is to allow the workflow controller to 
	 * "fast forward" workflows to set a non-default state. I.e. a workflow
	 * has to be set into a state in the middle of running. Use with care.
	 * 
	 * @param boolean $new_state 
	 */
	public function setDetectorState($new_state)
	{
		$this->detection_state = true;
		$this->context->notifyDetectorSatisfaction($this);
	}

	/**
	 * Method is called, when the parent node is activated.
	 * @return void 
	 */
	public function onActivate()
	{
		return;
	}

	/**
	 * Method is called, when the parent node is deactivated.
	 * @return void 
	 */
	public function onDeactivate()
	{
		return;
	}

	public function getActivated()
	{
		return $this->detection_state;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return ilNode
	 */
	public function getSourceNode()
	{
		return $this->source_node;
	}

	/**
	 * @param ilNode $source_node
	 */
	public function setSourceNode($source_node)
	{
		$this->source_node = $source_node;
	}

	/**
	 * @return string
	 */
	public function getVarName()
	{
		return $this->var_name;
	}

	/**
	 * @param string $var_name
	 */
	public function setVarName($var_name)
	{
		$this->var_name = $var_name;
	}

}
