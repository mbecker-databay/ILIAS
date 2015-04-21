<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilNode.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilEmitter.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilDetector.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilActivity.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilWorkflow.php';

/**
 * Class ilBaseNode
 */
abstract class ilBaseNode implements ilNode
{
	/**
	 * This holds a reference to the parent ilNode.
	 * 
	 * @var ilNode 
	 */
	protected $context;

	/**
	 * This holds an array of detectors attached to this node.
	 * 
	 * @var \ilDetector Array if ilDetector 
	 */
	protected $detectors;

	/**
	 * This holds an array of emitters attached to this node.
	 * 
	 * @var \ilEmitter Array of ilEmitter 
	 */
	protected $emitters;

	/**
	 * This holds an array of activities attached to this node.
	 * 
	 * @var \ilActivity Array of ilActivity 
	 */
	protected $activities;
	/**
	 * This holds the activation status of the node.
	 * 
	 * @var boolean
	 */
	protected $active = false;
	/** @var string $name */
	protected $name;
	/** @var array $runtime_vars */
	protected $runtime_vars;

	/**
	 * Adds a detector to the list of detectors.
	 * 
	 * @param ilDetector $a_detector 
	 */
	public function addDetector(ilDetector $a_detector)
	{
		$this->detectors[] = $a_detector;
		$this->context->registerDetector( $a_detector );
	}

	/**
	 * Returns all currently set detectors
	 * 
	 * @return Array Array with objects of ilDetector 
	 */
	public function getDetectors()
	{
		return $this->detectors;
	}

	/**
	 * Adds an emitter to the list of emitters.
	 * 
	 * @param ilEmitter $a_emitter 
	 */
	public function addEmitter(ilEmitter $a_emitter)
	{
		$this->emitters[] = $a_emitter;
	}

	/**
	 * Returns all currently set emitters
	 * 
	 * @return Array Array with objects of ilEmitter 
	 */
	public function getEmitters()
	{
		return $this->emitters;
	}

	/**
	 * Adds an activity to the list of activities.
	 * 
	 * @param ilActivity $a_activity 
	 */
	public function addActivity(ilActivity $a_activity)
	{
		$this->activities[] = $a_activity;
	}

	/**
	 * Returns all currently set activites
	 * 
	 * @return Array Array with objects of ilActivity 
	 */
	public function getActivities()
	{
		return $this->activities;
	}

	/**
	 * Returns a reference to the parent workflow object.
	 * 
	 * @return \ilWorkflow 
	 */
	public function getContext()
	{
		return $this->context;
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
	 * @return array
	 */
	public function getRuntimeVars()
	{
		return $this->runtime_vars;
	}

	/**
	 * @param array $runtime_vars
	 */
	public function setRuntimeVars($runtime_vars)
	{
		$this->runtime_vars = $runtime_vars;
	}

	public function getRuntimeVar($name)
	{
		return $this->runtime_vars[$name];
	}

	public function setRuntimeVar($name, $value)
	{
		$this->runtime_vars[$name] = $value;
	}

	/**
	 * Method called on activation of the node.
	 * @return void
	 */
	public function onActivate()
	{
		return;
	}

	/**
	 * Method calles on deactivation of the node.
	 * @return void
	 */
	public function onDeactivate()
	{
		return;
	}

	/**
	 * Returns the activation status of the node.
	 *
	 * @return boolean Activation status of the node.
	 */
	public function isActive()
	{
		return $this->active;
	}

	abstract public function attemptTransition();

	abstract public function checkTransitionPreconditions();

	abstract public function executeTransition();

	abstract public function activate();

	abstract public function deactivate();

	abstract public function notifyDetectorSatisfaction(ilDetector $a_detector);

} 