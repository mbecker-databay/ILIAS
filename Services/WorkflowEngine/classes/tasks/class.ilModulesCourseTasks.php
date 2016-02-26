<?php

class ilModulesCourseTasks
{
	/**
	 * @param $context
	 * @param $params
	 * @return array
	 */
	public static function readLearnersFromCourse($context, $params)
	{
		/*
		 * Modelling:

      <bpmn2:extensionElements>
          <ilias:properties>
              <ilias:libraryCall location="Services/WorkflowEngine/classes/tasks/class.ilModulesCourseTasks.php" api="ilModulesCourseTasks" method="readLearnersFromCourse" />
          </ilias:properties>
      </bpmn2:extensionElements>

		 */
		require_once './Modules/Course/classes/class.ilCourseParticipants.php';
		$input_params = $params[0];
		$output_params = $params[1];

		$participants = ilCourseParticipants::_getInstanceByObjId(ilObject::_lookupObjectId(current($input_params)));
		$learners = $participants->getMembers();
		$retval = array($output_params[0] => $learners);
		return $retval;
	}

	/**
	 * @param $context
	 * @param $params
	 * @return array
	 */
	public static function readTutorsFromCourse($context, $params)
	{
		/*
		 * Modelling:

      <bpmn2:extensionElements>
          <ilias:properties>
              <ilias:libraryCall location="Services/WorkflowEngine/classes/tasks/class.ilModulesCourseTasks.php" api="ilModulesCourseTasks" method="readTutorsFromCourse" />
          </ilias:properties>
      </bpmn2:extensionElements>

		 */

		require_once './Modules/Course/classes/class.ilCourseParticipants.php';
		$input_params = $params[0];
		$output_params = $params[1];

		$participants = ilCourseParticipants::_getInstanceByObjId(ilObject::_lookupObjectId(current($input_params)));
		$tutors = $participants->getTutors();
		$retval = array($output_params[0] => $tutors);
		return $retval;
	}

	/**
	 * @param $context
	 * @param $params
	 * @return array
	 */
	public static function readAdminsFromCourse($context, $params)
	{
		/*
		 * Modelling:

      <bpmn2:extensionElements>
          <ilias:properties>
              <ilias:libraryCall location="Services/WorkflowEngine/classes/tasks/class.ilModulesCourseTasks.php" api="ilModulesCourseTasks" method="readAdminsFromCourse" />
          </ilias:properties>
      </bpmn2:extensionElements>

		 */

		require_once './Modules/Course/classes/class.ilCourseParticipants.php';
		$input_params = $params[0];
		$output_params = $params[1];

		$participants = ilCourseParticipants::_getInstanceByObjId(ilObject::_lookupObjectId(current($input_params)));
		$admins = $participants->getAdmins();
		$retval = array($output_params[0] => $admins);
		return $retval;
	}

	/**
	 * @param $context
	 * @param $params
	 * @return array
	 */
	public static function createCourse($context, $params)
	{
		/*
		 * Modelling:

      <bpmn2:extensionElements>
          <ilias:properties>
              <ilias:libraryCall location="Services/WorkflowEngine/classes/tasks/class.ilModulesCourseTasks.php" api="ilModulesCourseTasks" method="createCourse" />
          </ilias:properties>
      </bpmn2:extensionElements>

		 */


		$input_params = array_values($params[0]);
		$output_params = array_values($params[1]);

		require_once './Modules/Course/classes/class.ilObjCourse.php';

		$course_object = new ilObjCourse();
		$course_object->setType('crs');
		$course_object->setTitle($input_params[1]);
		$course_object->setDescription("");
		$course_object->create(true); // true for upload
		$course_object->createReference();
		$course_object->putInTree($input_params[0]);
		$course_object->setPermissions($input_params[0]);

		$participants = ilCourseParticipants::_getInstanceByObjId(ilObject::_lookupObjectId(current($input_params)));
		$participants->add(6,IL_CRS_ADMIN);

		$retval = array($output_params[0] => $course_object->getRefId());
		return $retval;
	}

	/**
	 * @param $context
	 * @param $params
	 * @return array
	 */
	public static function assignLearnersToCourse($context, $params)
	{
		/*
		 * Modelling:

      <bpmn2:extensionElements>
          <ilias:properties>
              <ilias:libraryCall location="Services/WorkflowEngine/classes/tasks/class.ilModulesCourseTasks.php" api="ilModulesCourseTasks" method="assignLearnersToCourse" />
          </ilias:properties>
      </bpmn2:extensionElements>

		 */

		require_once './Modules/Course/classes/class.ilCourseParticipants.php';
		$input_params = $params[0];
		$output_params = $params[1];

		$participants = ilCourseParticipants::_getInstanceByObjId(ilObject::_lookupObjectId(current($input_params)));
		$admins = $participants->getAdmins();
		$retval = array($output_params[0] => $admins);
		return $retval;
	}

}