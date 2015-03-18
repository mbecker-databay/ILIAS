<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilDetector.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilEmitter.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilActivity.php';

/**
 * ilNode of the petri net based workflow engine.
 *
 * Please see the reference implementations for details:
 * @see class.ilBasicNode.php
 * @see class.ilConditionalNode.php
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
interface ilNode
{
	public function attemptTransition();
	public function checkTransitionPreconditions();
	public function executeTransition();
	public function addDetector(ilDetector $a_detector);
	public function addEmitter(ilEmitter $a_emitter);
	public function addActivity(ilActivity $a_activity);
	public function activate();
	public function deactivate();
	public function onActivate();
	public function onDeactivate();
	public function notifyDetectorSatisfaction(ilDetector $a_detector);
}