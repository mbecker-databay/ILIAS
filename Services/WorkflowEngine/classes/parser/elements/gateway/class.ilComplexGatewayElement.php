<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilComplexGatewayElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilComplexGatewayElement extends ilBaseElement
{
	public $element_varname;
	
	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		$code = "";
		$this->element_varname = '$_v_'.$element['attributes']['id'];

		$event_definition = null;

		$class_object->registerRequire('./Services/WorkflowEngine/classes/nodes/class.ilPluginNode.php');
		$code .= '
			' . $this->element_varname . ' = new ilPluginNode($this);
			// Details how this works need to be further carved out.
			$this->addNode(' . $this->element_varname . ');
		';
		return $code;
	}
} 