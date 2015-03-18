<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilExternalDetector.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilWorkflow.php';

/**
 * ilBaseWorkflow is part of the petri net based workflow engine.
 *
 * The base workflow class is the ancestor for all concrete workflow implementations.
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
abstract class ilBaseWorkflow implements ilWorkflow
{
	/**
	 *Holds a list of references nodes attached to the workflow.
	 * 
	 * @var \ilNode Array of ilNode
	 */
	protected $nodes;

	/**
	 * Holds a list of references to all external detectors of all nodes attached to the workflow.
	 * 
	 * @var \ilExternalDetector Array of ilDetector 
	 */
	protected $detectors;

	/**
	 * Holds a reference to the start node of the workflow.
	 * 
	 * @var ilNode Node, which is to be activated to start the workflow. 
	 */
	protected $start_node;

	/**
	 * Holds the activation state of the workflow.
	 * 
	 * @var boolean 
	 */
	protected $active;

	/**
	 * This holds the database id of the workflow
	 * 
	 * @var integer
	 */
	protected $db_id;

	/**
	 * Holds the type of the workflow. 
	 * 
	 * Aka its name for easy identification in case a manual search needs to be
	 * done on the database. eg. cmpl_crs_ref_48
	 * 
	 * This is intended to be a per-workflow information.
	 * 
	 * @var string Name of type of the workflow.
	 */
	protected $workflow_type;

	/**
	 * Holds a content description of the workflow instance.
	 * 
	 * Also, just to make this man-handleable. E.g. cmpl_usr_id_6 
	 * 
	 * This is intended to be a per-instance information,
	 * 
	 * @var string Content description of the workflow.
	 */
	protected $workflow_content;

	/**
	 * Holds the classname of the workflow definition.
	 * @var string Name of the class. e.g. ComplianceWorkflow1 for class.ilComplianceWorkflow1.php
	 */
	protected $workflow_class;

	/**
	 * Holds the path to the workflow definition class relative to the applications root.
	 * @var string Path to class, e.g. Services/WorkflowEngine for './Services/WorkflowEngine/classes/class..."
	 */
	protected $workflow_location;

	/**
	 * Holding the subject type of the workflow.
	 * 
	 * This setting holds the identifier 'what kind of' the workflow is about.
	 * E.g. crs, usr
	 * @var string Name of the subject type. 
	 */
	protected $workflow_subject_type;

	/**
	 * This is the actual identifier of the 'who'. If subject_type is a usr, this
	 * is a usr_id. If subject_type is a grp, this is a group_id. (or  group ref id)
	 * 
	 * @var integer Identifier of the events subject.
	 * 
	 */
	protected $workflow_subject_identifier;

	/**
	 * Type of the workflows context.
	 * 
	 * This is the second 'what kind of' the workflow is rigged to.
	 * 
	 * @var string Type if the events context type.
	 */
	protected $workflow_context_type;

	/**
	 * Identifier of the workflows context.
	 * 
	 * This is the 'who' for second entity the workflow is bound to.
	 * 
	 * @var integer Identifier of the events context. 
	 */
	protected $workflow_context_identifier;	// 48 (ref_id if ctx. is crs.

	/**
	 * Array of instance variables to be shared across the workflow.
	 * 
	 * @var Array Associative array of  mixed.
	 */
	protected $instance_vars;

	/** @var array $data_inputs Input data for the workflow (readonly). */
	protected $data_inputs;

	/** @var array $data_outputs Output data for the workflow. */
	protected $data_outputs;

	/** @var bool $require_data_persistence True, if the persistence needs to deal with data. */
	protected $require_data_persistence = false;

	/**
	 * Default constructor
	 * 
	 * Here the definition of the workflow is to be done. 
	 */
	public abstract function __construct();

	/**
	 * Starts the workflow, activating the start_node. 
	 */
	public function startWorkflow()
	{
		// Write the workflow to the database, so detectors find a parent id
		// to save with them.
		require_once './Services/WorkflowEngine/classes/utils/class.ilWorkflowDbHelper.php';
		$this->active = true;
		//ilWorkflowDbHelper::writeWorkflow($this);  TODO Check if this is necessarily to be done by the workflow.
		$this->onStartWorkflow();

		// Figure out, if there is a start-node set - or nodes at all.
		if ($this->start_node == null)
		{
			if (count($this->nodes) != 0)
			{
				$this->start_node = $this->nodes[0];
			} else {
				//ilWorkflowDbHelper::deleteWorkflow($this);
				throw new Exception ('No start_node, no node, no start. Doh.');
			}
		}
		$this->start_node->activate();
		// Why was this in here? DAFUQ? ilWorkflowDbHelper::writeWorkflow($this);
	}

	/**
	 * Stops the workflow, deactivating all nodes. 
	 */
	public function stopWorkflow()
	{
		$this->active = false;
		foreach ($this->nodes as $node)
		{
			$node->deactivate();
		}
		$this->onStopWorkflow();
	}

	/**
	 * Method called on start of the workflow, prior to activating the first node. 
	 * @return void
	 */
	public function onStartWorkflow()
	{
		return;
	}

	/**
	 * Method called on stopping of the workflow, after deactivating all nodes.
	 * 
	 * Please note: Stopping a workflow 'cancels' the execution. The graceful
	 * end of a workflow is handled with @see onWorkflowFinished().
	 * @return void
	 */
	public function onStopWorkflow()
	{
		return;
	}

	/**
	 * Method called after workflow is finished, after detecting no more nodes
	 * are active.
	 * This is the graceful end of the workflow.
	 * Forced shutdown of a workflow is handled in @see onStopWorkflow().
	 * @return void 
	 */
	public function onWorkflowFinished()
	{
		return;
	}

	/**
	 * Returns the activation status of the workflow.
	 * 
	 * @return boolean 
	 */
	public function isActive()
	{
		return (bool)$this->active;
	}

	/**
	 * Handles an event.
	 * 
	 * The event is passed to all active event handlers.
	 * 
	 * @param type $a_type
	 * @param type $a_params 
	 */
	public function handleEvent($a_params)
	{
		$active_nodes_available = false;
		// Hier nur an aktive Nodes dispatchen.
		foreach ($this->detectors as $detector)
		{
			$node = $detector->getContext();
			if ($node->isActive())
			{
				$detector->trigger($a_params);
				$node = $detector->getContext();
				if ($node->isActive())
				{
					$active_nodes_available = true;
				}
			}
		}
		if ($active_nodes_available == false)
		{
			$this->active = false;
			$this->onWorkflowFinished();
		}
	}

	public function registerDetector(ilDetector $a_detector)
	{
		$reflection_class = new ReflectionClass($a_detector);
		if (in_array('ilExternalDetector', $reflection_class->getInterfaceNames()))
		{
			$this->detectors[] = $a_detector;
		}
	}

	/**
	 * Returns the workflow type and content currently set to the workflow.
	 * 
	 * @return  array array('type' => $this->workflow_type, 'content' => $this->workflow_content)
	 */
	public function getWorkflowData()
	{
		return array('type' => $this->workflow_type, 'content' => $this->workflow_content);
	}

	/**
	 * Get the workflow subject set to the workflow.
	 * 
	 * @return array array('type' => $this->workflow_subject_type, 'identifier' => $this->workflow_subject_identifier)
	 */
	public function getWorkflowSubject()
	{
		return array('type' => $this->workflow_subject_type, 'identifier' => $this->workflow_subject_identifier);
	}

	/**
	 * Get the event context set to the workflow.
	 * 
	 * @return array array('type' => $this->workflow_context_type, 'identifier' => $this->workflow_context_identifier)
	 */
	public function getWorkflowContext()
	{
		return array('type' => $this->workflow_context_type, 'identifier' => $this->workflow_context_identifier);
	}

	/**
	 * Sets the database id of the detector.
	 * 
	 * @param integer $a_id 
	 */
	public function setDbId($a_id)
	{
		$this->db_id = $a_id;
	}

	/**
	 * Returns the database id of the detector if set.
	 * 
	 * @return integer 
	 */
	public function getDbId()
	{
		if ($this->db_id != null)
		{
			return $this->db_id;
		} 
		else
		{
			require_once './Services/WorkflowEngine/exceptions/ilWorkflowObjectStateException.php';
			throw new ilWorkflowObjectStateException('No database ID set.');
		}
	}

	/**
	 * Returns, if the detector has a database id.
	 * @return boolean If a database id is set.
	 */
	public function hasDbId()
	{
		if ($this->db_id == null)
		{
			return false;
		}
		return true;
	}

	/**
	 * Sets the start node of the workflow. This node is activated, when the
	 * workflow is started.
	 * 
	 * @param ilNode $a_node 
	 */
	public function setStartNode(ilNode $a_node)
	{
		$this->start_node = $a_node;
	}

	/**
	 * This method adds a node to the workflow.
	 * 
	 * @param ilNode $a_node 
	 */
	public function addNode(ilNode $a_node)
	{
		$this->nodes[] = $a_node;
	}

	/**
	 * Sets the classname of the workflow definition.
	 * 
	 * @see $this->workflow_class
	 * 
	 * @param string $a_class 
	 */
	public function setWorkflowClass($a_class)
	{
		$this->workflow_class = $a_class;
	}

	/**
	 * Returns the currently set workflow class definition name.
	 * 
	 * @see $this->workflow_class
	 * 
	 * @return string Class name 
	 */
	public function getWorkflowClass()
	{
		return $this->workflow_class;
	}

	/**
	 * Sets the location of the workflow definition file as relative path.
	 * 
	 * @see $this->workflow_location
	 * 
	 * @param string $a_path e.g. Services/WorkflowEngine 
	 */
	public function setWorkflowLocation($a_path)
	{
		$this->workflow_location = $a_path;
	}

	/**
	 * Returns the currently set path to the workflow definition.
	 * 
	 * @see $this->workflow_location
	 * 
	 * @return string 
	 */
	public function getWorkflowLocation()
	{
		return $this->workflow_location;
	}

	/**
	 * Autoloader function to dynamically include files for instantiation of 
	 * objects during deserialization.
	 * 
	 * @param string $class_name 
	 */
	public static function autoload($class_name)
	{
		// Activities
		if (strtolower(substr($class_name, strlen($class_name)-8, 8)) == 'activity')
		{
			require_once './Services/WorkflowEngine/classes/activities/class.'.$class_name.'.php';
		}

		// Detectors
		if (strtolower(substr($class_name, strlen($class_name)-8, 8)) == 'detector')
		{
			require_once './Services/WorkflowEngine/classes/detectors/class.'.$class_name.'.php';
		}

		// Emitters
		if (strtolower(substr($class_name, strlen($class_name)-7, 7)) == 'emitter')
		{
			require_once './Services/WorkflowEngine/classes/emitters/class.'.$class_name.'.php';
		}

		// Nodes
		if (strtolower(substr($class_name, strlen($class_name)-4, 4)) == 'node')
		{
			require_once './Services/WorkflowEngine/classes/nodes/class.'.$class_name.'.php';
		}

	}

	#region InstanceVars

	public function defineInstanceVar($name)
	{
		$this->instance_vars[$name] = null;
	}
	
	/**
	 * Returns if an instance variable of the given name is set.
	 * 
	 * @param string $a_name
	 * 
	 * @return boolean True, if a variable is set.
	 */
	public function hasInstanceVar($a_name)
	{
		return array_key_exists($a_name, (array)$this->instance_vars);
	}

	/**
	 * Returns the given instance variables content  
	 * 
	 * @param string $a_name Name of the variable.
	 * 
	 * @return mixed Content of the variable.
	 */
	public function getInstanceVar($a_name)
	{
		return $this->instance_vars[$a_name];
	}

	/**
	 * Sets the given instance var with the given content.
	 * 
	 * @param string $a_name Name of the variable
	 * @param mixed $a_value 
	 */
	public function setInstanceVar($a_name, $a_value)
	{
		$this->instance_vars[$a_name] = $a_value;
	}

	/**
	 * Returns an array with all set instance variables.
	 * 
	 * @return array Associative array of mixed.
	 */
	public function getInstanceVars()
	{
		return (array) $this->instance_vars;
	}

	/**
	 * Empties the instance variables. 
	 */
	public function flushInstanceVars()
	{
		$this->instance_vars = array();
	}

	#endregion

	#region Data IO

	public function defineInputVar($name)
	{
		$this->data_inputs[$name] = null;
		$this->require_data_persistence = true;
	}

	public function defineOutputVar($name)
	{
		$this->data_outputs[$name] = null;
		$this->require_data_persistence = true;
	}

	public function readInputVar($name)
	{
		if($this->data_inputs[$name])
		{
			return $this->data_inputs[$name];
		}
		return null;
	}

	public function hasInputVar($name)
	{
		return array_key_exists($name, (array)$this->data_inputs);
	}

	public function hasOutputVar($name)
	{
		return array_key_exists($name, (array)$this->data_outputs);
	}

	public function writeInputVar($name, $value)
	{
		$this->data_inputs[$name] = $value;
		$this->require_data_persistence = true;
	}

	public function readOutputVar($name)
	{
		if($this->data_outputs[$name])
		{
			return $this->data_outputs[$name];
		}
		return null;
	}

	public function writeOutputVar($name, $value)
	{
		$this->data_outputs[$name] = $value;
		$this->require_data_persistence = true;
	}

	public function getInputVars()
	{
		return (array)$this->data_inputs;
	}

	public function getOutputVars()
	{
		return (array)$this->data_outputs;
	}

	public function isDataPersistenceRequired()
	{
		return $this->require_data_persistence;
	}

	public function resetDataPersistenceRequirement()
	{
		$this->require_data_persistence = false;
	}

	#endregion
}

spl_autoload_register(array('ilBaseWorkflow', 'autoload'));