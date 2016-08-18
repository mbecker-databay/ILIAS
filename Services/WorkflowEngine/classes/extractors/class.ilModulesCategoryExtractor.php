<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilModulesCategoryExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('category');

		switch($event)
		{
			case 'delete':
				$this->extractWithoutUser($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}
}