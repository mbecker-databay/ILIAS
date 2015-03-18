<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilDetector.php';
/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilNode.php';

/**
 * ilWorkflow Interface is part of the petri net based workflow engine.
 *
 * Please see the reference / tutorial implementations for details:
 * @see class.ilBaseWorkflow.php (Base class)
 * @see class.ilPetriNetWorkflow1.php (Abstract Tutorial Part I)
 * @see class.ilPetriNetWorkflow2.php (Abstract Tutorial Part II)
 * @see class.ilBasicComplianceWorkflow.php (Real World Example)
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
interface ilWorkflow
{
	// Event handling
	public function handleEvent($a_params);

	// Node management
	public function addNode(ilNode $a_node);
	public function setStartNode(ilNode $a_node);
	public function registerDetector(ilDetector $a_detector);

	// Status
	public function startWorkflow();
	public function stopWorkflow();
	public function isActive();

	public function onStartWorkflow();
	public function onStopWorkflow();
	public function onWorkflowFinished();

	// Persistence scheme.
	public function getWorkflowData();
	public function getWorkflowSubject();
	public function getWorkflowContext();
	public function getWorkflowClass();
	public function getWorkflowLocation();
	public function setDbId($a_id);
	public function getDbId();
	public function hasDbId();

	public function isDataPersistenceRequired();
	public function resetDataPersistenceRequirement();

	// Instance vars (data objects)
	public function defineInstanceVar($name);
	public function hasInstanceVar($a_name);
	public function getInstanceVar($a_name);
	public function setInstanceVar($a_name, $a_value);
	public function getInstanceVars();
	public function flushInstanceVars();

	// Data Inputs
	public function defineInputVar($name);
	public function hasInputVar($name);
	public function readInputVar($name);
	public function writeInputVar($name, $value);
	public function getInputVars();

	// Data Outputs
	public function defineOutputVar($name);
	public function hasOutputVar($name);
	public function readOutputVar($name);
	public function writeOutputVar($name, $value);
	public function getOutputVars();

}