<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/detectors/class.ilEventDetector.php';

		class IntermediateCatchEvent_Timer_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_IntermediateCatchEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_2);
		
			$_v_IntermediateCatchEvent_2_detector = new ilEventDetector($_v_IntermediateCatchEvent_2);
			$_v_IntermediateCatchEvent_2_detector->setEvent(			"time_passed", 			"time_passed");
			$_v_IntermediateCatchEvent_2_detector->setEventSubject(	"none", 	"0");
			$_v_IntermediateCatchEvent_2_detector->setEventContext(	"none", 	"0");
			$_v_IntermediateCatchEvent_2_detector->setListeningTimeframe(1299841994, 0);
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>