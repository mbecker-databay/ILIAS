<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilModulesSessionExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('session');
		switch($event)
		{
			case 'create':
			case 'update':
			case 'delete':
				$this->extractWithUser($parameters);
				break;
			case 'addToWaitingList':
				$this->extractWithUser($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}
}