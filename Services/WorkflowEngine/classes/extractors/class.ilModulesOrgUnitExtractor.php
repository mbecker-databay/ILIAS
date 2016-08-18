<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilModulesOrgUnitExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('orgunit');

		switch($event)
		{
			case 'assignUsersToEmployeeRole':
			case 'assignUsersToSuperiorRole':
			case 'deassignUserFromEmployeeRole':
			case 'deassignUserFromSuperiorRole':
			case 'assignUserToLocalRole':
			case 'deassignUserFromLocalRole':
				$this->extractWithUser($parameters);
				break;
			case 'initDefaultRoles':
			case 'delete':
				$this->extractWithoutUser($parameters);
		}

		return $this->ilExtractedParams;
	}

	protected function extractWithUser($parameters)
	{

		$this->ilExtractedParams->setSubjectId($parameters['obj_id']);
		$this->ilExtractedParams->setContextType('usr_id');
		$this->ilExtractedParams->setContextId($parameters['user_id']); // usr_id in many other places
	}
}