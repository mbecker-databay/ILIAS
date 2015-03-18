<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/** @noinspection PhpIncludeInspection */
require_once './Services/WorkflowEngine/interfaces/ilDetector.php';

/**
 * ilExternalDetector Interface is part of the petri net based workflow engine.
 *
 * Please see the reference implementations for details:
 * @see class.ilEventDetector.php
 * @see class.ilTimerDetector.php
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
interface ilExternalDetector extends ilDetector
{
	// Event listener table persistence scheme.
	public function setDbId($a_id);
	public function getDbId();
	public function hasDbId();
	public function writeDetectorToDb();
	public function deleteDetectorFromDb();
	
	// Listening only at certain times scheme.
	public function isListening();
	public function getListeningTimeframe();
	public function setListeningTimeframe($a_listening_start, $a_listening_end);
	
	// Event description scheme.
	public function getEvent();
	public function getEventSubject();
	public function getEventContext();
}