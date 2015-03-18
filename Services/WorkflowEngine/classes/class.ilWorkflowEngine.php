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

	public function __construct($a_mass_action = false)
	{
		$this->mass_action = (bool) $a_mass_action;
	}

	public function processEvent(
		$a_type,
		$a_content,
		$a_subject_type,
		$a_subject_id,
		$a_context_type,
		$a_context_id
	)
	{
		// TODO During process, it needs to check for uninitialized workflows that start on an event.
		// Get listening events.
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
		// Event incoming, check ServiceDisco, call appropriate extractors.
		#$extractor = ilExtractorFactory::getExtractorByEventDescriptorPair($a_component, $a_event);
		#$extracted_params = $extractor->extract($a_parameter);
		#Until we have this goodness, we return here.
		return;

		$extracted_params = array(
			'subject_type' 	=> 'example_subject',
			'subject_id'	=> 123,
			'context_type'	=> 'example_context',
			'context_id'	=> 456
		);

		$this->processEvent(
			$a_component, 
			$a_event, 
			$extracted_params['subject_type'],
			$extracted_params['subject_id'],
			$extracted_params['context_type'],
			$extracted_params['context_id']
		);
	}
}