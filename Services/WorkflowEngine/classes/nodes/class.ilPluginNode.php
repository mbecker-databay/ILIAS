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
 * Plugin node of the petri net based workflow engine.
 *
 * The plugin node is a deciding node. It features a multiple set of emitters
 * and activities.
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilPluginNode implements ilNode, ilWorkflowEngineElement
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
	
	/**
	 * This holds a list of emitters attached to the node.
	 * In this node type, these are the 'then' emitters.
	 * 
	 * @var \ilEmitter Array of ilEmitter
	 */
	private $emitters;
	
	/**
	 * This holds a list of emitters attached to the node.
	 * In this node type, these are the 'else' emitters.
	 * 
	 * @var \ilEmitter Array of ilEmitter
	 */
	private $else_emitters;
	
	/**
	 * This holds a list of activities attached to the node.
	 * In this node type, these are the 'then' activities.
	 * 
	 * @var \ilActivity Array of ilActivity 
	 */
	private $activities;
	
	/**
	 * This holds a list of activities attached to the node.
	 * In this node type, these are the 'else' activities.
	 * 
	 * @var \ilActivity Array of ilActivity
	 */
	private $else_activities;
	
	/**
	 * This holds the activation status of the node.
	 * 
	 * @var boolean 
	 */
	private $active = false;
	
	/**
	 * This holds the piece of code used to determine if the 'then' or the 'else'
	 * sets of activities and emitters are to be used.
	 * 
	 * @var string PHP code to be executed to determine the 'decision' of the node.
	 */
	private $evaluation_expression = "return true;";

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
		$this->else_activities = array();
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
	 * Passes a trigger to attached detectors.
	 * @deprecated
	 *  
	 * @param type $a_type
	 * @param type $a_params 
	 */	
	public function trigger($a_type, $a_params = null)
	{
		if ($this->active == true && count($this->detectors) != 0)
		{
			foreach($this->detectors as $detector)
			{
				if (get_class($detector) == $a_type)
				{
					$detector->trigger($a_params);
				}
			}
		}
		$this->attemptTransition();
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
		// TODO Call Plugin here.
		$eval_function = create_function('$detectors', $this->evaluation_expression);

		if ($eval_function($this->detectors) === null)
		{
			return false;
		}
		
		if ($eval_function($this->detectors) === true)
		{
			return true;
		} 
		else
		{
			return true;
		}
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
		
		// TODO Call Plugin here.
		$eval_function = create_function('$detectors', $this->evaluation_expression);

		if ($eval_function($this->detectors) === null)
		{
			return false;
		}
		
		if ($eval_function($this->detectors) === true)
		{
			$this->executeTransition();
			return true;
		} 
		else
		{
			$this->executeElseTransition();
			return true;
		}
	}

	/**
	 * Executes all 'then'-activities attached to the node.
	 */
	private function executeActivities()
	{
		if (count($this->activities) != 0)
		{
			foreach ($this->activities as $activity)
			{
				$activity->execute();
			}
		}
	}
	
	/**
	 * Executes all 'then'-emitters attached to the node.
	 */
	private function executeEmitters()
	{
		if (count($this->emitters) != 0)
		{
			foreach ($this->emitters as $emitter)
			{
				$emitter->emit();
			}
		}
	}

	/**
	 * Executes the 'then'-transition of the node. 
	 */
	public function executeTransition()
	{
		$this->deactivate();
		$this->executeActivities();
		$this->executeEmitters();
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
	public function addEmitter(ilEmitter $a_emitter, $else_emitter = false)
	{
		if (!$else_emitter)
		{
			$this->emitters[] = $a_emitter;
		}
		else
		{
			$this->else_emitters[] = $a_emitter;
		}
	}

	/**
	 * Adds an activity to one of the lists attached to the node.
	 * 
	 * @param ilActivity $a_activity
	 * @param boolean    $else_activity True, if the activity should be an 'else'-activity.
	 */
	public function addActivity(ilActivity $a_activity, $else_activity = false)
	{
		if (!$else_activity)
		{
			$this->activities[] = $a_activity;
		}
		else
		{
			$this->else_activities[] = $a_activity;
		}
	}
	
	/**
	 * This sets the evaluation expression for the node.
	 *
	 * Example:
	 * 		$evaluation_expression=
	 *	'
	 *		# detector 0 is lp-start,
	 *		# detector 1 is lp-timeout
	 *		$retval = null;
	 *
	 *		if ($detectors[0]->getDetectorState() == false 
	 *			&& $detectors[1]->getDetectorState() == true)
	 *		{
	 *			#lp-timeout before lp-start -> else-execution
	 *			$retval = false; 
	 *		}
	 *		
	 *		if ($detectors[0]->getDetectorState() == true
	 *			&& $detectors[1]->getDetectorState() == false)
	 *		{
	 *			#lp-start before lp-timeout -> then-execution
	 *			$retval = true;
	 *		}
	 *		return $retval;
	 *	';
	 * The method gets an array containing the nodes detectors as parameters, named
	 * as $detectors. 
	 * 
	 * Please note that the method here returns 'tri-state' - true, false, null.
	 * 
	 * Null is used to tell, that no decision is possible due to the state of the
	 * detectors. In the above example, if none of both detectors were satisfied,
	 * the node should neither transit the 'then'-set nor the 'else'-set.
	 * 
	 * @var string PHP code to be executed to determine the 'decision' of the node.
	 */
	public function setEvaluationExpression($a_expression)
	{
		// TODO Rework to use a Plugin here.
		$this->evaluation_expression = $a_expression;
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
	 * @param boolean $else True, if else activities should be returned.
	 * 
	 * @return Array Array with objects of ilActivity 
	 */
	public function getActivities($else = false)
	{
		if ($else)
		{
			return $this->else_activities;
		}
		return $this->activities;
	}	
	
	/**
	 * Returns all currently set emitters
	 * 
	 * @param boolean $else True, if else emitters should be returned.
	 * 
	 * @return Array Array with objects of ilEmitter 
	 */
	public function getEmitters($else = false)
	{
		if ($else)
		{
			return $this->else_emitters;
		}
		return $this->emitters;
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

}