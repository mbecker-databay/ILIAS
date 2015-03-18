<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * ilStopWorkflowActivityTest is part of the workflow engine.
 *
 * This class holds all tests for the class activities/class.ilStopWorkflowActivity 
 * 
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilStopWorkflowActivityTest extends PHPUnit_Framework_TestCase
{
	/** vfsStream Test Directory, see setup. */
	public $test_dir;

	public function setUp()
	{
		chdir( dirname ( __FILE__ ) );
		chdir('../../../../');

		try
		{
			include_once("./Services/PHPUnit/classes/class.ilUnitUtil.php");
			//ilUnitUtil::performInitialisation();
		}
		catch ( Exception $exception )
		{
			if (!defined('IL_PHPUNIT_TEST'))
			{
				define('IL_PHPUNIT_TEST', FALSE);
			}
		}

		// Empty workflow.
		require_once './Services/WorkflowEngine/classes/workflows/class.ilEmptyWorkflow.php';
		$this->workflow = new ilEmptyWorkflow();
		
		// Basic node
		require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
		$this->node = new ilBasicNode($this->workflow);
		
		// Wiring up so the node is attached to the workflow.
		$this->workflow->addNode($this->node);
				
		require_once './Services/WorkflowEngine/classes/activities/class.ilStopWorkflowActivity.php';

		require_once 'vfsStream/vfsStream.php';
		$this->test_dir = vfsStream::setup('example');
	}

	public function tearDown()
	{
		global $ilSetting;
		if ($ilSetting !=  NULL)
		{
			//$ilSetting->delete('IL_PHPUNIT_TEST_TIME');
			//$ilSetting->delete('IL_PHPUNIT_TEST_MICROTIME');
		}
	}
	
	public function testConstructorValidContext()
	{
		// Act
		$activity = new ilStopWorkflowActivity($this->node);
		
		// Assert
		// No exception - good
		$this->assertTrue(
			true, 
			'Construction failed with valid context passed to constructor.'
		);
	}
	
	/**
     * @expectedException PHPUnit_Framework_Error
     */
	public function testConstructorInvalidContext()
	{
		// Act
		$activity = new ilStopWorkflowActivity($this->workflow);

		// Assert
		$this->assertTrue(
			true, 
			'No exception thrown from constructor on invalid context object.'
		);
	}

	public function testGetContext()
	{
		// Arrange
		$activity = new ilStopWorkflowActivity($this->node);

		// Act
		$actual = $activity->getContext();

		// Assert
		if ($actual === $this->node)
		{
			$this->assertEquals($actual, $this->node);
		} else {
			$this->assertTrue(false, 'Context not identical.');
		}
	}

	public function testExecute()
	{
		$workflowMock = $this->getMockBuilder('ilEmptyWorkflow')
							   ->setMethods(array('stopWorkflow'))
							   ->getMock();

		$workflowMock->expects($this->once())
					   ->method('stopWorkflow');
		$node = new ilBasicNode($workflowMock);
		
		$activity = new ilStopWorkflowActivity($node);
		
		// Act
		$activity->execute();
	}
}