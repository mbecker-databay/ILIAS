<?php

require_once './Services/WorkflowEngine/interfaces/ilExtractor.php';

abstract class ilBaseExtractor implements ilExtractor
{
	/** @var ilExtractedParams $ilExtractedParams */
	protected $ilExtractedParams;

	public function __construct(ilExtractedParams $ilExtractedParams)
	{
		$this->ilExtractedParams = $ilExtractedParams;
	}

	abstract public function extract($event, $parameters);

	protected function extractWithUser($parameters)
	{
		$this->ilExtractedParams->setSubjectId($parameters['obj_id']);
		$this->ilExtractedParams->setContextType('usr_id');
		$this->ilExtractedParams->setContextId($parameters['usr_id']);
	}

	protected function extractWithoutUser($parameters)
	{
		$this->ilExtractedParams->setSubjectId($parameters['obj_id']);
		$this->ilExtractedParams->setContextType('null');
		$this->ilExtractedParams->setContextId(0);
	}
}