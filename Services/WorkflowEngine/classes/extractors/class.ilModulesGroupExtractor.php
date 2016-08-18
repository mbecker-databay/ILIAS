<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilModulesGroupExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('group');

		switch($event)
		{
			case 'addSubscriber':
			case 'addToWaitingList':
				$this->extractWithUser($parameters);
				break;
			case 'create':
			case 'update':
			case 'delete':
				$this->extractWithoutUser($parameters);
		}

		return $this->ilExtractedParams;
	}
}