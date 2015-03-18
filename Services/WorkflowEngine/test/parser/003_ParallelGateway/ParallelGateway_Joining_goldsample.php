<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';

		class ParallelGateway_Joining extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_ParallelGateway_1 = new ilBasicNode($this);
			$this->addNode($_v_ParallelGateway_1);
		
			$_v_IntermediateThrowEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateThrowEvent_1);
		
			$_v_IntermediateThrowEvent_3 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateThrowEvent_3);
		
			$_v_ParallelGateway_2 = new ilBasicNode($this);
			$this->addNode($_v_ParallelGateway_2);
		
			$_v_IntermediateThrowEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateThrowEvent_2);
		
			$_v_EndEvent_6 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_6);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>