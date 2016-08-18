<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilModulesCourseExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('course');

		switch($event)
		{
			case 'addSubscriber':
			case 'addToWaitingList':
				$this->extractWithUser($parameters);
				break;
			case 'create':
			case 'delete':
			case 'update':
				$this->extractWithoutUser($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}
}