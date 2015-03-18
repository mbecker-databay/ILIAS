<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilCaseNode.php';

		class ExclusiveGateway_Join_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_ExclusiveGateway_1 = new ilBasicNode($this);
			$this->addNode($_v_ExclusiveGateway_1);
		
			$_v_IntermediateCatchEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_2);
		
			$_v_IntermediateCatchEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_1);
		
			$_v_ParallelGateway_1 = new ilCaseNode($this);
			$this->addNode($_v_ParallelGateway_1);
		
			$_v_EndEvent_3 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_3);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>