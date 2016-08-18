<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilModulesExerciseExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('exercise');

		switch($event)
		{
			case 'createAssignment':
			case 'updateAssignment':
			case 'deleteAssignment':
			case 'delete':
				$this->extractWithoutUser($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}
}