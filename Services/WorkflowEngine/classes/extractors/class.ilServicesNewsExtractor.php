<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesNewsExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('news');

		switch($event)
		{
			case 'readNews':
			case 'unreadNews':
				$this->extractNews($parameters);
				break;
		}
		return $this->ilExtractedParams;
	}

	protected function extractNews($parameters)
	{
		$this->ilExtractedParams->setSubjectId(0);
		$this->ilExtractedParams->setContextType('news_ids');
		$this->ilExtractedParams->setContextId(implode(',', $parameters['news_ids']));
	}
}