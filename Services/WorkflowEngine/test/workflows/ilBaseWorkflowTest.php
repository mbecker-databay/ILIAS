<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * ilBaseWorkflowTest is part of the petri net based workflow engine.
 *
 * This class holds all tests for the class 
 * workflows/class.BaseWorkflow 
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilBaseWorkflowTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		include_once("./Services/PHPUnit/classes/class.ilUnitUtil.php");
		//ilUnitUtil::performInitialisation();
		
		// Empty workflow as test fixture for the abstract class.
		require_once './Services/WorkflowEngine/classes/workflows/class.ilEmptyWorkflow.php';	
	}
	
	public function tearDown()
	{
		global $ilSetting;
		if ($ilSetting !=  NULL)
		{
			$ilSetting->delete( 'IL_PHPUNIT_TEST_TIME' );
			$ilSetting->delete( 'IL_PHPUNIT_TEST_MICROTIME' );
		}
	}
	
	public function testStartWorkflow()
	{
		$this->markTestIncomplete(
			'$ilDB is not available during unit tests.'
		);
		// ilWorkflowDbHelper needs it, so, though luck atm.
	}
	
	public function testStopWorkflow()
	{
		$this->markTestIncomplete(
			'$ilDB is not available during unit tests.'
		);
		// ilWorkflowDbHelper needs it, so, though luck atm.
	}

	public function testOnStartWorkflow()
	{
		$this->markTestIncomplete(
			'$ilDB is not available during unit tests.'
		);
		// ilWorkflowDbHelper needs it, so, though luck atm.
	}
	
	public function testHasInstanceVarSet()
	{
		// Arrange
		$workflow = new ilEmptyWorkflow();
		
		$ex_name = 'PHPUnit';
		$ex_value = 'TEST';
		$workflow->setInstanceVar($ex_name, $ex_value);
		
		// Act
		$actual = $workflow->hasInstanceVar($ex_name);
		
		// Assert
		$this->assertTrue($actual);
	}
	
	public function testHasInstanceVarUnset()
	{
		// Arrange
		$workflow = new ilEmptyWorkflow();
		
		$ex_name = 'PHPUnit';
		
		// Act
		$actual = $workflow->hasInstanceVar($ex_name);
		
		// Assert
		$this->assertFalse($actual);		
	}
	
	public function testSetGetInstanceVar()
	{
		// Arrange
		$workflow = new ilEmptyWorkflow();
		
		$ex_name = 'PHPUnit';
		$ex_value = 'TEST';
		
		// Act
		$workflow->setInstanceVar($ex_name, $ex_value);
		$actual = $workflow->getInstanceVar($ex_name);
		
		// Assert
		$this->assertEquals($actual, $ex_value);		
	}
	
	public function testGetInstanceVarsPop()
	{
		// Arrange
		$workflow = new ilEmptyWorkflow();
		
		$ex_name = 'PHPUnit';
		$ex_value = 'TEST';
		
		// Act
		$workflow->setInstanceVar($ex_name, $ex_value);
		$actual = $workflow->getInstanceVars();
		
		// Assert
		$this->assertEquals($actual[$ex_name], $ex_value);		
	}
	
	public function testGetInstanceVarUnpop()
	{
		// Arrange
		$workflow = new ilEmptyWorkflow();
		
		// Act
		$actual = $workflow->getInstanceVars();
		
		// Assert
		$this->assertEquals($actual, array());				
	}
	
	public function testFlushInstanceVars()
	{
		// Arrange
		$workflow = new ilEmptyWorkflow();
		$ex_name = 'PHPUnit';
		$ex_value = 'TEST';		
		$workflow->setInstanceVar($ex_name, $ex_value);
		
		// Act
		$workflow->flushInstanceVars();
		$actual = $workflow->getInstanceVars();
		
		// Assert
		$this->assertEquals($actual, array());				
		
	}
}