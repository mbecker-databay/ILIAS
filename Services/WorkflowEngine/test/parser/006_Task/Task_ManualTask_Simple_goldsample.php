<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';

		class Task_ManualTask_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_EndEvent_4 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_4);
		
			$_v_StartEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_2);
		
			$this->setStartNode($_v_StartEvent_2);
			
			$_v_ManualTask_1 = new ilBasicNode($this);
			$this->addNode($_v_ManualTask_1);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>