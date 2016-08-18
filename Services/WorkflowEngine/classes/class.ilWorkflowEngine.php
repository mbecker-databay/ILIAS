<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * ilWorkflowEngine is part of the petri net based workflow engine.
 *
 * The workflow engine is the central hub of activity in the system. It takes
 * care for hibernating/waking of workflows, handling and dispatching events 
 * and so on.
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilWorkflowEngine
{
	/**
	 * True, if this instance is made to handle a lot of requests.
	 * @var boolean
	 */
	private $mass_action;

	/**
	 * ilWorkflowEngine constructor.
	 *
	 * @param bool $a_mass_action
	 */
	public function __construct($a_mass_action = false)
	{
		$this->mass_action = (bool) $a_mass_action;
	}

	/**
	 * @param string  $a_type
	 * @param string  $a_content
	 * @param string  $a_subject_type
	 * @param integer $a_subject_id
	 * @param string  $a_context_type
	 * @param integer $a_context_id
	 */
	public function processEvent(
		$a_type,
		$a_content,
		$a_subject_type,
		$a_subject_id,
		$a_context_type,
		$a_context_id
	)
	{

		// Get listening event-detectors.
		require_once './Services/WorkflowEngine/classes/utils/class.ilWorkflowDbHelper.php';
		$workflows = ilWorkflowDbHelper::getDetectors(
			$a_type, 
			$a_content, 
			$a_subject_type, 
			$a_subject_id, 
			$a_context_type, 
			$a_context_id
		);

		if (count($workflows) != 0)
		{
			foreach ($workflows as $workflow_id)
			{
				$wf_instance = ilWorkflowDbHelper::wakeupWorkflow($workflow_id);
				$wf_instance->handleEvent(
					array(
						$a_type, 
						$a_content, 
						$a_subject_type, 
						$a_subject_id, 
						$a_context_type, 
						$a_context_id
					)
				);
				ilWorkflowDbHelper::writeWorkflow($wf_instance);
			}
		}
	}

	public function handleEvent($a_component, $a_event, $a_parameter)
	{
		// Event incoming, check ServiceDisco (TODO, for now we're using a non-disco factory), call appropriate extractors.

		require_once './Services/WorkflowEngine/classes/extractors/class.ilExtractorFactory.php';
		require_once './Services/WorkflowEngine/interfaces/ilExtractor.php';

		$extractor = ilExtractorFactory::getExtractorByEventDescriptor($a_component);

		if($extractor instanceof ilExtractor)
		{
			$extracted_params = $extractor->extract($a_event, $a_parameter);

			$this->processEvent(
				$a_component,
				$a_event,
				$extracted_params->getSubjectType(),
				$extracted_params->getSubjectId(),
				$extracted_params->getContextType(),
				$extracted_params->getContextId()
			);
		}
	}
}