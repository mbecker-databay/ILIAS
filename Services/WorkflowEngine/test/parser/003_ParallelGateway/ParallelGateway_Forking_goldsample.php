<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';

		class ParallelGateway_Forking extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_EndEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_2);
		
			$_v_ParallelGateway_1 = new ilBasicNode($this);
			$this->addNode($_v_ParallelGateway_1);
		
			$_v_EndEvent_4 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_4);
		
			$_v_EndEvent_5 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_5);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>