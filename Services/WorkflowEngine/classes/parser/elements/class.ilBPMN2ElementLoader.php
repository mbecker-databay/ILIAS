<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once './Services/WorkflowEngine/classes/parser/elements/class.ilBaseElement.php';

/**
 * Class ilBPMN2ElementLoader
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilBPMN2ElementLoader 
{
	protected $bpmn2_array;

	public function __construct($bpmn2_array)
	{
		$this->bpmn2_array = $bpmn2_array;
	}

	public function load($element_name)
	{
		preg_match( '/[A-Z]/', $element_name, $matches, PREG_OFFSET_CAPTURE );
		$type = strtolower(substr($element_name, @$matches[0][1]));
		if ($type == 'basedgateway')
		{
			// Fixing a violation of the standards naming convention by the standard here.
			$type = 'gateway';
		}

		if ($type == 'objectreference')
		{
			// Fixing a violation of the standards naming convention by the standard here.
			$type = 'object';
		}

		require_once './Services/WorkflowEngine/classes/parser/elements/' . $type . '/class.il' . ucfirst($element_name) . 'Element.php';
		$classname = 'il'.ucfirst($element_name).'Element';
		$instance = new $classname;
		$instance->setBPMN2Array($this->bpmn2_array);

		return $instance;
	}
} 