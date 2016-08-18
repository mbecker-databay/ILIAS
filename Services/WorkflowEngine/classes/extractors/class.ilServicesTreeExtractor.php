<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesTreeExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('tree');

		switch($event)
		{
			case 'moveTree':
				$this->extractTree($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}

	protected function extractTree($parameters)
	{
		$this->ilExtractedParams->setSubjectId(0);
		$this->ilExtractedParams->setContextType('tree');
		$this->ilExtractedParams->setContextId($parameters['tree']);
	}
}