<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/activities/class.ilStaticMethodCallActivity.php';

		class Task_CallActivity_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_Task_1 = new ilBasicNode($this);
			$this->addNode($_v_Task_1);
			$_v_Task_1_callActivity = new ilStaticMethodCallActivity($_v_Task_1);
			$_v_Task_1_callActivity->setIncludeFilename("Modules/Course.php");
			$_v_Task_1_callActivity->setClassAndMethodName("CourseParticipants::RemoveParticipant");
			$_v_Task_1_callActivity_params = array(); // Requires Parsing of Data Associations!
			$_v_Task_1_callActivity->setParameters($this, $_v_Task_1_callActivity_params);
			$_v_Task_1->addActivity($_v_Task_1_callActivity);
		
			$_v_EndEvent_4 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_4);
		
			$_v_StartEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_2);
		
			$this->setStartNode($_v_StartEvent_2);
			
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>