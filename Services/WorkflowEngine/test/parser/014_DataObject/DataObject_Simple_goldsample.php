<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';

		class DataObject_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_Task_1 = new ilBasicNode($this);
			$this->addNode($_v_Task_1);
		
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
			$this->defineInstanceVar("Data Object 1");
		
			//DataObjectReference: This reference makes only sense with sequence flow (data association)
			//This connects a reference like this with a data association and a data object.
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>