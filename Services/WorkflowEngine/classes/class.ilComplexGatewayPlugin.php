<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once './Services/Component/classes/class.ilPlugin.php';

/**
* Abstract parent class for all ComplexGateway plugin classes.
*
* @author Maximilian Becker <mbecker@databay.de>
* @version $Id$
*
* @ingroup ServicesWorkflowEngine
*/
abstract class ilComplexGatewayPlugin extends ilPlugin
{
	/**
	 * Get Component Type
	 *
	 * @return string Component Type (Service)
	 */
	final function getComponentType()
	{
		return IL_COMP_SERVICE;
	}

	/**
	 * Get Component Name.
	 *
	 * @return string Component Name (WorkflowEngine)
	 */
	final function getComponentName()
	{
		return 'WorkflowEngine';
	}

	/**
	 * Get Slot Name.
	 *
	 * @return string Slot Name (ComplexGateway)
	 */
	final function getSlot()
	{
		return 'ComplexGateway';
	}

	/**
	 * Get Slot ID.
	 *
	 * @return string Slot Id (wfecg)
	 */
	final function getSlotId()
	{
		return "wfecg";
	}

	/**
	 * Object initialization done by slot.
	 */
	protected final function slotInit() {}
}
