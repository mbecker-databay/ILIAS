<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/activities/class.ilStopWorkflowActivity.php';

		class EndEvent_Terminate_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
				$_v_EndEvent_1_terminationEventActivity = new ilStopWorkflowActivity($_v_EndEvent_1);
				$_v_EndEvent_1->addActivity($_v_EndEvent_1_terminationEventActivity);
			
			// sequence_flow_missing
		
			}
		}
		
?>