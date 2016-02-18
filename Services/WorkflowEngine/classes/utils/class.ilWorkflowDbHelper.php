<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * ilWorkflowDbHelper is part of the petri net based workflow engine.
 *
 * This helper takes care of all database related actions which are part of the
 * internal workings of the workflow engine.
 * 
 * Hint: This is not the place to stuff your db-calls for activities, kid!
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilWorkflowDbHelper
{
	const DB_MODE_CREATE = 0;
	const DB_MODE_UPDATE = 1;
	
	/**
	 * Takes a workflow as an argument and saves it to the database.
	 * 
	 * @global ilDB $ilDB
	 * 
	 * @param ilWorkflow $a_workflow 
	 */
	public static function writeWorkflow(ilWorkflow $a_workflow)
	{
		global $ilDB;

		$require_data_persistance = $a_workflow->isDataPersistenceRequired();
		$a_workflow->resetDataPersistenceRequirement();

		if ($a_workflow->hasDbId())
		{
			$wf_id = $a_workflow->getDbId();
			$mode = self::DB_MODE_UPDATE;
		} 
		else
		{
			$wf_id = $ilDB->nextId('wfe_workflows');
			$a_workflow->setDbId($wf_id);
			$mode = self::DB_MODE_CREATE;
		}

		$wf_data = $a_workflow->getWorkflowData();
		$wf_subject = $a_workflow->getWorkflowSubject();
		$wf_context = $a_workflow->getWorkflowContext();
		$active = $a_workflow->isActive();
		$instance = serialize($a_workflow);

		if ($mode == self::DB_MODE_UPDATE)
		{
			$ilDB->update('wfe_workflows', 
				array(
					'workflow_type'		=> array ('text', $wf_data['type'] ),
					'workflow_content'	=> array ('text', $wf_data['content']),
					'workflow_class'	=> array ('test', $a_workflow->getWorkflowClass()),
					'workflow_location' => array ('test', $a_workflow->getWorkflowLocation()),
					'subject_type'		=> array ('text', $wf_subject['type']),
					'subject_id'		=> array ('integer', $wf_subject['identifier']),
					'context_type'		=> array ('text', $wf_context['type']),
					'context_id'		=> array ('integer', $wf_context['identifier']),
					'workflow_instance' => array ('clob', $instance),
					'active'			=> array ('integer', (int)$active)
				),
				array(
					'workflow_id'		=> array ('integer', $wf_id)
				)
			);
		}

		if ($mode == self::DB_MODE_CREATE)
		{
			$ilDB->insert('wfe_workflows', 
				array(
					'workflow_id'		=> array ('integer', $wf_id),
					'workflow_type'		=> array ('text', $wf_data['type'] ),
					'workflow_class'	=> array ('test', $a_workflow->getWorkflowClass()),
					'workflow_location' => array ('test', $a_workflow->getWorkflowLocation()),
					'workflow_content'	=> array ('text', $wf_data['content']),
					'subject_type'		=> array ('text', $wf_subject['type']),
					'subject_id'		=> array ('integer', $wf_subject['identifier']),
					'context_type'		=> array ('text', $wf_context['type']),
					'context_id'		=> array ('integer', $wf_context['identifier']),
					'workflow_instance' => array ('clob', $instance),
					'active'			=> array ('integer', (int)$active)
				)
			);
		}

		if($require_data_persistance)
		{
			self::persistWorkflowIOData($a_workflow);
		}
	}

	public static function persistWorkflowIOData(ilWorkflow $a_workflow)
	{
		global $ilDB;
		$workflow_id = $a_workflow->getId();

		$input_data = $a_workflow->getInputVars();
		foreach($input_data as $name => $value)
		{
			$ilDB->replace(
				'wfe_io_inputs', 
				array('workflow_id' => $workflow_id, 'name' => $name),
				array('value' => $value)
			);
		}

		$output_data = $a_workflow->getOutputVars();
		foreach($output_data as $name => $value)
		{
			$ilDB->replace(
				'wfe_io_outputs',
				array('workflow_id' => $workflow_id, 'name' => $name),
				array('value' => $value)
			);
		}
	}

	/**
	 * Takes a workflow as an argument and deletes the corresponding entry
	 * from the database.
	 * 
	 * @global ilDB $ilDB
	 * 
	 * @param ilWorkflow $a_workflow 
	 */
	public static function deleteWorkflow(ilWorkflow $a_workflow)
	{
		global $ilDB;
		
		if ($a_workflow->hasDbId())
		{
			$ilDB->manipulate(
				'DELETE 
				FROM wfe_workflows
				WHERE workflow_id = ' . $ilDB->quote($a_workflow->getDbId(), 'integer')
			);
			
			// This should not be necessary, actually. Still this call makes sure 
			// that there won't be orphan records polluting the database.
			$ilDB->manipulate(
				'DELETE
				FROM wfe_det_listening
				WHERE workflow_id = ' . $ilDB->quote($a_workflow->getDbId(), 'integer')
			);

			$ilDB->manipulate(
				'DELETE
				FROM wfe_io_inputs
				WHERE workflow_id = ' . $ilDB->quote($a_workflow->getDbId(), 'integer')
			);

			$ilDB->manipulate(
				'DELETE
				FROM wfe_io_outputs
				WHERE workflow_id = ' . $ilDB->quote($a_workflow->getDbId(), 'integer')
			);
		}
		else
		{
			return;
		}
	}
	
	/**
	 * Takes a detector as an argument and saves it to the database.
	 * 
	 * @global ilDB $ilDB
	 * 
	 * @param ilDetector $a_detector 
	 */
	public static function writeDetector(ilDetector $a_detector)
	{
		global $ilDB;
		
		if ($a_detector->hasDbId())
		{
			$det_id = $a_detector->getDbId();
			$mode = self::DB_MODE_UPDATE;
		} 
		else
		{
			$det_id = $ilDB->nextId('wfe_det_listening');
			$a_detector->setDbId($det_id);
			$mode = self::DB_MODE_CREATE;
		}

		$node = $a_detector->getContext();
		$workflow = $node->getContext();
		if($workflow->hasDbId())
		{
			$wf_id = $workflow->getDbId();
		} else {
			$wf_id = null;
		}

		$det_data = $a_detector->getEvent();
		$det_subject = $a_detector->getEventSubject();
		$det_context = $a_detector->getEventContext();
		$det_listen = $a_detector->getListeningTimeframe();

		if ($mode == self::DB_MODE_UPDATE)
		{
			$ilDB->update('wfe_det_listening', 
				array(
					'workflow_id'		=> array ('integer', $wf_id),
					'type'				=> array ('text', $det_data['type'] ),
					'content'			=> array ('text', $det_data['content']),
					'subject_type'		=> array ('text', $det_subject['type']),
					'subject_id'		=> array ('integer', $det_subject['identifier']),
					'context_type'		=> array ('text', $det_context['type']),
					'context_id'		=> array ('integer', $det_context['identifier']),
					'listening_start'	=> array ('integer', $det_listen['listening_start']),
					'listening_end'		=> array ('integer', $det_listen['listening_end'])
				),
				array(
					'detector_id'		=> array ('integer', $det_id)
				)
			);
		}
		
		if ($mode == self::DB_MODE_CREATE)
		{
			$ilDB->insert('wfe_det_listening', 
				array(
					'detector_id'		=> array ('integer', $det_id),
					'workflow_id'		=> array ('integer', $wf_id),
					'type'				=> array ('text', $det_data['type'] ),
					'content'			=> array ('text', $det_data['content']),
					'subject_type'		=> array ('text', $det_subject['type']),
					'subject_id'		=> array ('integer', $det_subject['identifier']),
					'context_type'		=> array ('text', $det_context['type']),
					'context_id'		=> array ('integer', $det_context['identifier']),
					'listening_start'	=> array ('integer', $det_listen['listening_start']),
					'listening_end'		=> array ('integer', $det_listen['listening_end'])
				)
			);
		}
	}
	
	/**
	 * Takes a detector as an argument and deletes the corresponding entry
	 * from the database.
	 * 
	 * @global ilDB $ilDB
	 * 
	 * @param ilDetector $a_detector 
	 */
	public static function deleteDetector(ilExternalDetector $a_detector)
	{
		global $ilDB;
		
		if ($a_detector->hasDbId())
		{
			$ilDB->manipulate(
				'DELETE
				FROM wfe_det_listening
				WHERE detector_id = ' . $ilDB->quote($a_detector->getDbId(), 'integer')
			);
			$a_detector->setDbId(null);
		}
		else
		{
			return;
		}
	}

	/**
	 * Gets a list of all listening detectors for the given event.
	 * 
	 * @global ilDB $ilDB
	 * 
	 * @param string  $a_type Type of the event.
	 * @param string  $a_content Content of the event.
	 * @param string  $a_subject_type Type of the subject, e.g. usr.
	 * @param integer $a_subject_id Identifier of the subject, eg. 6.
	 * @param string  $a_context_type Type of the context, e.g. crs.
	 * @param integer $a_context_id Identifier of the context, e.g. 48
	 * 
	 * @return \integer	Array of workflow ids with listening detectors. 
	 */
	public static function getDetectors(
		$a_type,
		$a_content,
		$a_subject_type,
		$a_subject_id,
		$a_context_type,
		$a_context_id			
	)
	{
		global $ilDB;
		require_once './Services/WorkflowEngine/classes/utils/class.ilWorkflowUtils.php';
		$now = ilWorkflowUtils::time();
		$workflows = array();
		
		$result = $ilDB->query(
			'SELECT workflow_id
			FROM wfe_det_listening
			WHERE type = ' . $ilDB->quote($a_type, 'text') . '
			AND content = ' . $ilDB->quote($a_content, 'text') . '
			AND subject_type = ' . $ilDB->quote($a_subject_type, 'text') . '
			AND subject_id = ' . $ilDB->quote($a_subject_id, 'integer') . '
			AND context_type = ' . $ilDB->quote($a_context_type, 'text') . '
			AND context_id = ' . $ilDB->quote($a_context_id, 'integer') . '			
			AND (listening_start = ' . $ilDB->quote(0, 'integer') . ' 
				 OR (listening_start < ' . $ilDB->quote($now, 'integer') . ' AND listening_end = '. $ilDB->quote(0, 'integer') . ') 
				 OR listening_end > ' . $ilDB->quote($now, 'integer') . ')'
		);
		
		while ($row = $ilDB->fetchAssoc($result))
		{
			$workflows[] = $row['workflow_id'];
		}
		
		return $workflows;
	}
	
	/**
	 * Wakes a workflow from the database.
	 * 
	 * @global ilDB $ilDB
	 * 
	 * @param integer $a_id workflow_id.
	 * 
	 * @return \ilWorkflow An ilWorkflow-implementing instance.
	 *  
	 */
	public static function wakeupWorkflow($a_id)
	{
		global $ilDB;
		$result = $ilDB->query(
			'SELECT workflow_class, workflow_location, workflow_instance
			FROM wfe_workflows
			WHERE workflow_id = ' . $ilDB->quote($a_id, 'integer')
		);
		
		$workflow = $ilDB->fetchAssoc($result);
		
		require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
		$path = './' . $workflow['workflow_location'] . '/' . $workflow['workflow_class'];
		require_once $path;
		$instance = unserialize($workflow['workflow_instance']);

		return $instance;
	}
}