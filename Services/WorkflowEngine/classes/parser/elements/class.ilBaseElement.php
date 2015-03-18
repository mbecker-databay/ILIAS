<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilBaseElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
abstract class ilBaseElement
{
	protected $bpmn2_array;

	/**
	 * @return mixed
	 */
	public function getBpmn2Array()
	{
		return $this->bpmn2_array;
	}

	/**
	 * @param mixed $bpmn2_array
	 */
	public function setBpmn2Array($bpmn2_array)
	{
		$this->bpmn2_array = $bpmn2_array;
	}

}