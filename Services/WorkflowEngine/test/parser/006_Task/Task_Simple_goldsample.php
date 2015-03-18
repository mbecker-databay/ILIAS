<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';

		class Task_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_2);
		
			$this->setStartNode($_v_StartEvent_2);
			
			$_v_Task_1 = new ilBasicNode($this);
			$this->addNode($_v_Task_1);
		
			$_v_EndEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_2);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>