<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesUserExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('user');

		switch($event)
		{
			case 'afterCreate':
			case 'afterUpdate':
				$this->extractUser($parameters);
				break;
			case 'deleteUser':
				$this->extractUserById($parameters);
		}

		return $this->ilExtractedParams;
	}

	protected function extractUser($parameters)
	{
		$this->ilExtractedParams->setSubjectId($parameters['usr_obj']->getId());
		$this->ilExtractedParams->setContextType('null');
		$this->ilExtractedParams->setContextId(0);
	}

	protected function extractUserById($parameters)
	{
		$this->ilExtractedParams->setSubjectId($parameters['usr_id']);
		$this->ilExtractedParams->setContextType('null');
		$this->ilExtractedParams->setContextId(0);
	}
}