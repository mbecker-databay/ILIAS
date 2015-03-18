<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilEmitter.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilDetector.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilNode.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilWorkflowEngineElement.php';

/**
 * ilSimpleEmitter is part of the petri net based workflow engine.
 * 
 * The simple emitter is the internal signals yeoman, doing nothing but triggering
 * the designated simple detector.
 *
 * @todo Implement a WorkflowEngineHelper class to make dealing with detectors/emitters even easier.
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilSimpleEmitter implements ilEmitter, ilWorkflowEngineElement
{
	/**
	 * This holds a reference to the detector, which is to be triggered.
	 * 
	 * @var ilDetector 
	 */
	private $target_detector;

	/**
	 * This holds a reference to the parent ilNode.
	 * 
	 * @var ilNode 
	 */
	private $context;
	
	/**
	 * Default constructor.
	 * 
	 * @param ilNode Reference to the parent node. 
	 */
	public function __construct(ilNode $a_context)
	{
		$this->context = $a_context;
	}
	
	/**
	 * Sets the target detector for this emitter.
	 * 
	 * @param ilDetector $a_target_detector 
	 */
	public function setTargetDetector(ilDetector $a_target_detector)
	{
		$this->target_detector = $a_target_detector;
	}

	/**
	 * Gets the currently set target detector of this emitter.
	 * 
	 * @return ilDetector Reference to the target detector. 
	 */
	public function getTargetDetector()
	{
		return $this->target_detector;
	}
	
	/**
	 * Returns a reference to the parent node of this emitter.
	 * 
	 * @return ilNode Reference to the parent node.
	 */
	public function getContext()
	{
		return $this->context;
	}
	
	/**
	 * Executes this emitter. 
	 */
	public function emit()
	{
		$this->target_detector->trigger(array());
	}
}