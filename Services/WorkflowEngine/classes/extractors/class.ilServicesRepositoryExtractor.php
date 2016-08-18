<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesRepositoryExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('repository');

		switch($event)
		{
			case 'toTrash':
			case 'delete':
			case 'undelete':
				$this->extractWithoutUser($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}
}