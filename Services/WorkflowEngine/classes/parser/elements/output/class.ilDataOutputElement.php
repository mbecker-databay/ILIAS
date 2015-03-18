<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilDataOutputElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilDataOutputElement extends ilBaseElement
{
	public $element_varname;

	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		// TODO: We do not know, though, how runtime vars are passed from the workflow. (Sequence flow issue.)
		$name = $element['name'];
		if($element['attributes']['id'])
		{
			$name = $element['attributes']['id'];
		}
		if($element['attributes']['name'])
		{
			$name = $element['attributes']['name'];
		}
		$code = "";
		$code .= '
			$this->defineOutputVar("'.$name.'");
		';
		return $code;
	}
} 