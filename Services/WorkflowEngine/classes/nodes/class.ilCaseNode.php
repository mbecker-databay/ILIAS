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
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilWorkflowEngineElement.php';

/**
 * Case node of the petri net based workflow engine.
 *
 * The case node is a deciding node. It features a multiple set of emitters
 * and no activities.
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilCaseNode implements ilNode, ilWorkflowEngineElement
{
	/**
	 * This holds a reference to the parent workflow.
	 * 
	 * @var ilWorkflow
	 */
	private $context;

	/**
	 * This holds a list of detectors attached to the node.
	 * 
	 * @var \ilDetector Array of ilDetector 
	 */
	private $detectors;

	private $condition_emitter_pairs;

	/**
	 * This holds a list of activities attached to the node.
	 * In this node type, these are the 'else' activities.
	 * 
	 * @var \ilActivity Array of ilActivity
	 */
	private $activities;

	/**
	 * This holds the activation status of the node.
	 * 
	 * @var boolean 
	 */
	private $active = false;

	private $is_exclusive_join;

	private $is_exclusive_fork;

	/** @var string $name */
	protected $name;

	/**
	 * Default constructor.
	 * 
	 * @param ilWorkflow Reference to the parent workflow.
	 */
	public function __construct(ilWorkflow $a_context)
	{
		$this->context = $a_context;
		$this->detectors = array();
		$this->emitters = array();
		$this->else_emitters = array();
		$this->activities = array();
		$this->is_exclusive = false;
	}

	/**
	 * @param mixed $is_exclusive
	 */
	public function setIsExclusiveJoin($is_exclusive)
	{
		$this->is_exclusive_join = $is_exclusive;
	}

	/**
	 * @param mixed $is_exclusive
	 */
	public function setIsExclusiveFork($is_exclusive)
	{
		$this->is_exclusive_fork = $is_exclusive;
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

	/**
	 * Activates the node. 
	 */
	public function activate()
	{
		$this->active = true;
		foreach($this->detectors as $detector)
		{
			$detector->onActivate();
		}
		$this->onActivate();
		$this->attemptTransition();
	}

	/**
	 * Deactivates the node. 
	 */
	public function deactivate()
	{
		$this->active = false;
		foreach($this->detectors as $detector)
		{
			$detector->onDeactivate();
		}
		$this->onDeactivate();
	}

	/**
	 * Checks, if the preconditions of the node to transit are met.
	 * 
	 * Please note, that in a conditional node, this means the node can transit
	 * to one or another outcome. This method only returns false, if the return
	 * value of the method is neither true nor false.
	 * 
	 * @return boolean True, if node is ready to transit. 
	 */
	public function checkTransitionPreconditions()
	{
		// queries the $detectors if their conditions are met.
		$isPreconditionMet = true;
		foreach ($this->detectors as $detector)
		{
			if ($isPreconditionMet == true)
			{
				$isPreconditionMet = $detector->getDetectorState();
				if($isPreconditionMet && ($this->is_exclusive_join || $this->is_exclusive_fork || $this->is_exclusive))
				{
					break;
				}
			}
		}
		return $isPreconditionMet;
	}

	/**
	 * Attempts to transit the node.
	 * 
	 * Basically, this checks for preconditions and transits, returning true or
	 * false if preconditions are not met, aka detectors are not fully satisfied.
	 * 
	 * @return boolean True, if transition succeeded. 
	 */	
	public function attemptTransition()
	{
		if ($this->checkTransitionPreconditions() == true)
		{
			$this->executeTransition();
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Executes the 'then'-transition of the node. 
	 */
	public function executeTransition()
	{
		$this->deactivate();
		if (count($this->activities) != 0)
		{
			foreach ($this->activities as $activity)
			{
				$activity->execute();
			}
		}

		foreach((array)$this->condition_emitter_pairs as $pair)
		{
			$eval_function = create_function('$this', $pair['expression']);
			if($eval_function($this->detectors) === true)
			{
				$emitter = $pair['emitter'];
				$emitter->emit();
				if($this->is_exclusive_fork || $this->is_exclusive_join)
				{
					return;
				}
			}
		}
	}

	/**
	 * Adds a detector to the list of detectors attached to the node.
	 * 
	 * @param ilDetector $a_detector 
	 */
	public function addDetector(ilDetector $a_detector)
	{
		$this->detectors[] = $a_detector;
		$this->context->registerDetector($a_detector);
	}

	/**
	 * Adds an emitter to one of the lists attached to the node.
	 * 
	 * @param ilEmitter	$a_emitter
	 * @param boolean	$else_emitter True, if the emitter should be an 'else'-emitter.
	 */
	public function addEmitter(ilEmitter $a_emitter, $expression = 'return true;')
	{
		$this->condition_emitter_pairs[] = array(
			'emitter'	=> $a_emitter,
			'expression'	=> $expression
		);
	}

	/**
	 * Adds an activity to one of the lists attached to the node.
	 * 
	 * @param ilActivity $a_activity
	 */
	public function addActivity(ilActivity $a_activity)
	{
		$this->activities[] = $a_activity;
	}

	/**
	 * Returns a reference to the parent workflow.
	 * @return ilWorkflow Reference to the parent workflow.
	 */
	public function getContext()
	{
		return $this->context;
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
	 * This method is called by detectors, that just switched to being satisfied.
	 * 
	 * @param ilDetector $a_detector ilDetector which is now satisfied.
	 */
	public function notifyDetectorSatisfaction(ilDetector $a_detector) 
	{
		if ($this->isActive())
		{
			$this->attemptTransition();
		}
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
	 * Returns all currently set detectors
	 * 
	 * @return Array Array with objects of ilDetector 
	 */
	public function getDetectors()
	{
		return $this->detectors;
	}

	public function getEmitters()
	{
		return $this->emitters;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

}