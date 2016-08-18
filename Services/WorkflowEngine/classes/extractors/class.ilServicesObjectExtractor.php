<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesObjectExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('object');

		switch($event)
		{
			case 'create':
			case 'update':
				$this->extractObject($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}

	protected function extractObject($parameters)
	{
		$this->ilExtractedParams->setSubjectId($parameters['obj_id']);
		$this->ilExtractedParams->setContextType($parameters['obj_type']);
		$this->ilExtractedParams->setContextId(0);
	}
}