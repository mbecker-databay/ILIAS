<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/activities/class.ilEventRaisingActivity.php';

		class EndEvent_Message_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_EndEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_2);
		
				$_v_EndEvent_2_throwEventActivity = new ilEventRaisingActivity($_v_EndEvent_2);
				$_v_EndEvent_2_throwEventActivity->setEventType("Course");
				$_v_EndEvent_2_throwEventActivity->setEventName("UserWasAssigned");
				$_v_EndEvent_2->addActivity($_v_EndEvent_2_throwEventActivity);
			
			// sequence_flow_missing
		
			}
		}
		
?>