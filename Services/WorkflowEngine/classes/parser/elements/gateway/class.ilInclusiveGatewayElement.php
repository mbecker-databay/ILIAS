<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilInclusiveGatewayElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilInclusiveGatewayElement extends ilBaseElement
{
	public $element_varname;
	
	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		$code = "";
		$this->element_varname = '$_v_'.$element['attributes']['id'];

		$event_definition = null;

		$class_object->registerRequire('./Services/WorkflowEngine/classes/nodes/class.ilCaseNode.php');
		$code .= '
			' . $this->element_varname . ' = new ilCaseNode($this);
			$this->addNode(' . $this->element_varname . ');
		';
		return $code;
	}
} 