<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilPluginNode.php';

		class ComplexGateway_Blanko_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_ComplexGateway_2 = new ilPluginNode($this);
			// Details how this works need to be further carved out.
			$this->addNode($_v_ComplexGateway_2);
		
			$_v_EndEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_2);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>