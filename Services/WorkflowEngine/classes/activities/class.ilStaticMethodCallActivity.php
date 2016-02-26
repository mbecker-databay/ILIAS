<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilActivity.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilNode.php';

/**
 * Class ilStaticMethodCallActivity
 * 
 * This activity calls a given static method with a reference to itself as 
 * and a given array as parameters.
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilStaticMethodCallActivity implements ilActivity, ilWorkflowEngineElement
{
	/** @var ilWorkflowEngineElement $context Holds a reference to the parent object. */
	private $context;

	/** @var string $include_file Filename and path of the class to be loaded. */
	private $include_file = '';

	/**
	 * Holds the value of the method name to be called.
	 * E.g. ilHaumichblau::BuyAPony -> no parentheses.
	 * 
	 * @var string $class_and_method_name Class::Method without parentheses. 
	 */
	private $class_and_method_name = '';

	/** @var array $parameters Holds an array with params to be passed as second argument to the method. */
	private $parameters;

	/** @var array $outputs Holds a list of valid output element IDs passed as third argument to the method. */
	private $outputs;

	/** @var string $name */
	protected $name;

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
	 * Sets the name of the file to be included prior to calling the method..
	 * 
	 * @see $include_file
	 * 
	 * @param string $a_filename Name of the file to be included.
	 * 
	 * @return void
	 */
	public function setIncludeFilename($a_filename)
	{
		$this->include_file = $a_filename;
	}

	/**
	 * Returns the currently set filename of the classfile to be included.
	 * 
	 * @return string 
	 */
	public function getIncludeFilename()
	{
		return $this->include_file;
	}

	/***
	 * Sets the class- and methodname of the method to be called.
	 * E.g. ilPonyStable::getPony 
	 * 
	 * @see $method_name
	 * 
	 * @param string $a_name Classname::Methodname.
	 *
	 * @return void
	 */
	public function setClassAndMethodName($a_name)
	{
		$this->class_and_method_name = $a_name;
	}

	/**
	 * Returns the currently set class- and methodname of the method to be called.
	 * 
	 * @return string
	 */
	public function getClassAndMethodName()
	{
		return $this->class_and_method_name;
	}

	/**
	 * Sets an array with params for the method. This will be set as second 
	 * parameter. 
	 * 
	 * @param array Array with parameters.
	 *
	 * @return void
	 */
	public function setParameters($a_params)
	{
		$this->parameters = $a_params;
	}

	/**
	 * Returns the currently set parameters to be passed to the method.
	 * 
	 * @return array 
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @return array
	 */
	public function getOutputs()
	{
		return $this->outputs;
	}

	/**
	 * @param array $outputs
	 */
	public function setOutputs($outputs)
	{
		$this->outputs = $outputs;
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
		/** @noinspection PhpIncludeInspection */
		require_once './' . $this->include_file;
		$name = explode('::', $this->class_and_method_name);

		$list = (array)$this->context->getContext()->getInstanceVars();
		$params = array();
		foreach($this->parameters as $parameter)
		{
			$params[$parameter] = $parameter;
			foreach($list as $instance_var)
			{
				if($instance_var['id'] == $parameter)
				{
					$params[$parameter] = $this->context->getContext()->getInstanceVarById($parameter);
				}
			}
		}

		/** @var array $return_value */
		$return_value = call_user_func_array(
			array($name[0], $name[1]), 
			array($this, array($params, $this->outputs))
		);
		foreach((array) $return_value as $key => $value)
		{
			$this->context->getContext()->setInstanceVarById($key, $value);
		}
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

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}


}