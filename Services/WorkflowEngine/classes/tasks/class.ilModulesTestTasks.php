<?php

class ilModulesTestTasks
{

	public static function createTest($context, $params)
	{
		//IN: targetref, titlestring
		//OUT: refid
		$input_params = $params[0];
		$output_params =$params[1];

		require_once './Modules/Test/classes/class.ilObjTest.php';

		$test_object = new ilObjTest();
		$test_object->setType('crs');
		$test_object->setTitle($input_params['crsTitle']);
		$test_object->setDescription("");
		$test_object->create(true); // true for upload
		$test_object->createReference();
		$test_object->putInTree($input_params['destRefId']);
		$test_object->setPermissions($input_params['destRefId']);

		$retval = array($output_params[0] => $test_object->getRefId());
		return $retval;
	}

	public static function assignUsersToTest($context, $params)
	{
		require_once './Modules/Test/classes/class.ilObjTest.php';
		//IN: anonuserlist
		//OUT: void

		$input_params = $params[0];
		$output_params =$params[1];

		$test_object = new ilObjTest($input_params['refId']);
		foreach($input_params['usrIdList'] as $user_id)
		{
			$test_object->inviteUser($user_id);
		}
	}

}