<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesMembershipExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('membership');

		switch($event)
		{
			case 'addParticipant':
			case 'deleteParticipant':
				$this->extractWithUser($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}
}