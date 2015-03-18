<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/activities/class.ilEventRaisingActivity.php';

		class IntermediateThrowEvent_Signal_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
			$_v_IntermediateThrowEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateThrowEvent_1);
		
				$_v_IntermediateThrowEvent_1_throwEventActivity = new ilEventRaisingActivity($_v_IntermediateThrowEvent_1);
				$_v_IntermediateThrowEvent_1_throwEventActivity->setEventType("Modules/Course");
				$_v_IntermediateThrowEvent_1_throwEventActivity->setEventName("UserLeft");
				$_v_IntermediateThrowEvent_1->addActivity($_v_IntermediateThrowEvent_1_throwEventActivity);
			
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>