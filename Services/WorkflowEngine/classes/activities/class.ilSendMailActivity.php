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
class ilSendMailActivity implements ilActivity, ilWorkflowEngineElement
{
	/** @var ilWorkflowEngineElement $context Holds a reference to the parent object */
	private $context;

	/** @var string ID of the message to be sent. */
	private $message_name;

	/** @var string $name */
	protected $name;

	/** @var array $parameters Holds an array with params to be passed as second argument to the method. */
	private $parameters;

	/** @var array $outputs Holds a list of valid output element IDs passed as third argument to the method. */
	private $outputs;

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
	 * Executes this action according to its settings.
	 *
	 * @todo Use exceptions / internal logging.
	 *
	 * @return void
	 */
	public function execute()
	{
		$mail_data = $this->context->getContext()->getMessageDefinition($this->message_name);
		$mail_text = $this->decodeMessageText($mail_data['content']);
		$mail_text = $this->processPlaceholders($mail_text);


		$list = (array)$this->context->getContext()->getInstanceVars();
		$params = array();
		foreach($this->parameters as $parameter)
		{
			$set = false;
			foreach($list as $instance_var)
			{
				if($instance_var['id'] == $parameter)
				{
					$set = true;
					$role = $instance_var['role'];
					if($instance_var['reference'] == true)
					{
						foreach($list as $definitions)
						{
							if($definitions['id'] == $instance_var['target'])
							{
								$role = $definitions['role'];
							}
						}
					}
					$params[$role] = $this->context->getContext()->getInstanceVarById($parameter);
				}
			}
			if(!$set)
			{
				$params[$parameter] = $parameter;
			}
		}

		$a = 1;
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

	/**
	 * @return string
	 */
	public function getMessageName()
	{
		return $this->message_name;
	}

	/**
	 * @param string $message_name
	 */
	public function setMessageName($message_name)
	{
		$this->message_name = $message_name;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
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

	public function decodeMessageText($message_text)
	{
		return base64_decode($message_text);
	}

	public function processPlaceholders($message_text)
	{
		$matches = array();
		preg_match_all('/\[(.*?)\]/',$message_text, $matches, PREG_PATTERN_ORDER);

		foreach($matches[0] as $match)
		{
			$placeholder = substr($match, 1, strlen($match)-2);
			$content = $this->context->getContext()->getInstanceVarById($placeholder);
			if(strlen($content))
			{
				$message_text = str_replace($match, $content, $message_text);
			}
		}
		return $message_text;
	}
}