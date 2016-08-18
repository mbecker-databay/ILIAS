<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesTrackingExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		//$this->ilExtractedParams->setSubjectType('tracking'); See below what we do here different from other impl.

		switch($event)
		{
			case 'updateStatus':
				$this->extractTracking($parameters);
				break;
		}

		return $this->ilExtractedParams;
	}

	protected function extractTracking($parameters)
	{
		$this->ilExtractedParams->setSubjectType('tracking_' . $parameters['status']);
		$this->ilExtractedParams->setSubjectId($parameters['obj_id']);
		$this->ilExtractedParams->setContextType('usr_id');
		$this->ilExtractedParams->setContextId($parameters['usr_id']);
	}
}