<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * ilWorkflowIliasComplianceEvents is part of the petri net based workflow engine.
 *
 * This class collects events that are used throughout the workflow engine.
 * The main benefit to use this class is to place a static call with just 
 * the necessary set of information instead of using the ugly 
 * processEvent-signature of the ilWorkflowEngine.
 * Also, this class serves as a "reference" for event signatures that are 
 * used with the workflow engine.
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 * @deprecated
 * @ingroup Services/WorkflowEngine
 */
class ilWorkflowIliasComplianceEvents
{
	/**
	 * Handles the generic time_passed event.
	 * 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleTimePassedEvent($a_workflow_engine = null)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'time_passed', 
			'time_passed', 
			'none', 
			0, 
			'none', 
			0
		);
	}
	
	/**
	 * Handles an event, in which a user was assigned to a course.
	 * 
	 * Concept: (1) Nutzer wird einem Kurs zugewiesen
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 * 
	 * List of calls:
	 * -------------------------------------------------------------------------
	 * Modules/Course/classes/class.ilCourseParticipants.php F::add()
	 * Modules/Course/classes/class.ilCourseCron.php F::assignUserToCourse()
	 * 
	 */
	public static function handleUserWasAssignedToCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'was_assigned', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}

	/**
	 * Handles an event, in which a user started learning in a course.
	 * 
	 * Concept: (2) Nutzer beginnt lernen
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserStartsLearningInCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'started_learning', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which a user continues to learn in a course.
	 * 
	 * Concept: (3) Nutzer setzt lernen fort.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id
	 * @param ilWorkflowEngine $a_workflow_engine  
	 */
	public static function handleUserContinuesLearningInCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'continued_learning', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which a user enters a course.
	 * 
	 * Concept: (4) Nutzer betritt Kurs.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserEntersCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'user_entered', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}		
	
	/**
	 * Handles an event, in which a user enters a course again.
	 * 
	 * Concept: (5) Nutzer betritt Kurs ein weiteres Mal.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserEntersAgainCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'again_entered', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which a user finishes learning successfully.
	 * 
	 * Concept: (6) Nutzer absolviert lernen erfolgreich.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserFinishedSuccessfullyCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'crs_event', 
			'finished_successfully', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which a user finishes learning unsuccessfully.
	 * 
	 * Concept: (7) Nutzer fällt durch.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserFinishedUnsuccessfullyCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'finished_unsuccessfully', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which a user excedes learning time.
	 * 
	 * Concept: (8) Lernfrist überschritten.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserExceedsLearningTimeCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'exceeds_learningtime', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which a users learning time started.
	 * 
	 * Concept: (9) Learning time started.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserLearningTimeStartedCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'started_learningtime', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which a a message was sent due to compliance settings.
	 * 
	 * Concept: (10) Nachricht verschickt aufgrund von Compliance Einstellungen.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUserSentComplianceNotificationCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'sent_compliancenotification', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which compliance is started.
	 * 
	 * Concept: (11) Compliance starten.
	 * 
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleComplianceStartedCourse(
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'started_compliance', 
			'usr', 
			0, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which compliance is stopped for a course.
	 * 
	 * Concept: (12) Compliance stoppen.
	 * 
	 * @param integer $course_ref_id
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleComplianceStoppedCourse(
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'stopped_compliance', 
			'usr', 
			0, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which a a course is set to offline.
	 * 
	 * Concept: (13) Kurs wird offline gestellt.
	 * 
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleSetOfflineCourse(
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'set_offline', 
			'usr', 
			0, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which a user finishes learning successfully.
	 * 
	 * Concept: (14) Kurs wird online gestellt.
	 * 
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleSetOnlineCourse(
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'set_online', 
			'usr', 
			0, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which a user leaves the course.
	 * 
	 * Concept: (15) Nutzer verlässt Kurs.
	 * 
	 * @param integer $user_id
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 * 
	 * 
	 * List of calls:
	 * -------------------------------------------------------------------------
	 * Modules/Course/classes/class.ilCourseParticipants.php F::delete()
	 * Modules/Course/classes/class.ilCourseCron.php F::deassignUserFromCourse()
	 */
	public static function handleUserLeftCourse(
		$a_user_id, 
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'user_left', 
			'usr', 
			$a_user_id, 
			'crs', 
			$a_course_ref_id
		);		
	}	
	
	/**
	 * Handles an event, in which compliance is set to silence.
	 * 
	 * Concept: (16) Compliance wird auf 'silent' gesetzt.
	 * 
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleSetSilentCourse(
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'compliance_setsilent', 
			'usr', 
			0, 
			'crs', 
			$a_course_ref_id
		);		
	}	

	/**
	 * Handles an event, in which compliance is set to not silent.
	 * 
	 * Concept: (17) Compliance-Silence wird abgeschaltet.
	 * 
	 * @param integer $course_ref_id 
	 * @param ilWorkflowEngine $a_workflow_engine 
	 */
	public static function handleUnsetSilentCourse(
		$a_course_ref_id,
		$a_workflow_engine = null
	)
	{
		if (!$a_workflow_engine)
		{
			require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
			$a_workflow_engine = new ilWorkflowEngine();
		}
		
		$a_workflow_engine->processEvent(
			'course_event', 
			'compliance_unsetsilent', 
			'usr', 
			0, 
			'crs', 
			$a_course_ref_id
		);		
	}
	
	/**
	 * Dispatches a workflow event from an LP-Update event.
	 * 
	 * Implemented LP-Status-Provider:
	 * ilLPStatusCollection (partial: crs only)
	 * 
	 * @param integer		$a_user_id
	 * @param string		$a_status_class_name
	 * @param ilLPStatus	$a_status_class 
	 */
	public static function dispatchLPEvent($a_user_id, $a_status_class_name, $a_status_info, $a_lp_status)
	{
		switch (strtolower($a_status_class_name))
		{
			case 'illpstatuscollection':
				if ($a_lp_status == 2)
				{
					self::handleUserFinishedSuccessfullyCourse($a_user_id, self::getContainerCrsRefIdByTstRefId($a_status_info['collections'][0]));		
				} 
				if ($a_lp_status == 3)
				{
					self::handleUserFinishedUnsuccessfullyCourse($a_user_id, self::getContainerCrsRefIdByTstRefId($a_status_info['collections'][0]));				
				}
				break;
		}
	}
	
	public static function getContainerCrsRefIdByTstRefId($a_tst_ref_id)
	{
				// We need to listen to a test. We need to find this out.
		global $ilDB;
		$result = $ilDB->query(
			'SELECT ref_id 
			FROM ut_lp_collections 
			JOIN object_reference 
			ON ut_lp_collections.obj_id = object_reference.obj_id 
			WHERE item_id = ' . $ilDB->quote($a_tst_ref_id, 'integer')
		);
		$row = $ilDB->fetchAssoc($result);
		return (int) $row['ref_id'];
	}
	
	public static function dispatchProgressEvent($a_user_id, $a_obj_id, $a_ref_id, $a_obj_type)
	{
		if ($a_obj_type == '')
		{
			return;
		}
		
		require_once './Services/WorkflowEngine/classes/class.ilWorkflowEngine.php';
		$workflow_engine = new ilWorkflowEngine();
		$workflow_engine->processEvent(
			$a_obj_type.'_event', 
			$a_obj_type.'_entered', 
			'usr', 
			$a_user_id, 
			$a_obj_type, 
			$a_ref_id
		);
	}
}