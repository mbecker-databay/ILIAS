<?php

require_once './Services/WorkflowEngine/classes/extractors/class.ilBaseExtractor.php';

class ilServicesAuthenticationExtractor extends ilBaseExtractor
{
	public function extract($event, $parameters)
	{
		$this->ilExtractedParams->setSubjectType('authentication');

		switch($event)
		{
			case 'afterLogin':
				$this->extractAfterLogin($parameters);
				break;
			// case 'expiredSessionDetected': Can this be supported? No params... TODO: Add some thinking to it...
			// case 'reachedSessionPoolLimit': Can this be supported? No params... TODO: Add some thinking to it...

		}

		return $this->ilExtractedParams;
	}

	protected function extractAfterLogin($parameters)
	{
		$this->ilExtractedParams->setSubjectId(0);
		$this->ilExtractedParams->setContextType('user');
		$this->ilExtractedParams->setContextId(ilObjUser::_lookupId($parameters['username']));
	}
}