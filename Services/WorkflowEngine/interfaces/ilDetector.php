<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * ilDetector Interface is part of the petri net based workflow engine.
 *
 * Please see the reference implementations for details:
 * @see class.ilSimpleDetector.php
 * @see class.ilCounterDetector.php
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
interface ilDetector
{
	public function trigger($a_params);
	public function getDetectorState();
	public function onActivate();
	public function onDeactivate();
}