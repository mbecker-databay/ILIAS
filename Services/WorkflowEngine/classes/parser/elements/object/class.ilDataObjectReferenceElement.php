<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilDataObjectReferenceElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilDataObjectReferenceElement extends ilBaseElement
{
	public $element_varname;

	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		// Good folks, we're a data object reference. So what does it mean?
		// We need to register an instance var that is a reference.
		$element_id = ilBPMN2ParserUtils::xsIDToPHPVarname($element['attributes']['id']);
		$name = $element['name'];
		$ext_name = ilBPMN2ParserUtils::extractDataNamingFromElement($element);
		if($ext_name != null)
		{
			$name = $ext_name;
		}
		$code = "";
		$code .= '
			$this->defineInstanceVar("'.$element_id.'","'.$name.'", true, "'.$element['attributes']['dataObjectRef'].'" );
		';

		return $code;
	}
} 