<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilCaseNode.php';

		class InclusiveGateway_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_EndEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_2);
		
			$_v_InclusiveGateway_1 = new ilCaseNode($this);
			$this->addNode($_v_InclusiveGateway_1);
		
			$_v_ParallelGateway_1 = new ilBasicNode($this);
			$this->addNode($_v_ParallelGateway_1);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>