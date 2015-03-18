<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/detectors/class.ilEventDetector.php';

		class EventBasedGateway_Blanko_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_EventBasedGateway_1 = new ilBasicNode($this);
			// TODO This item needs rework during sequence flow development, so following intermediate catch event
			// nodes or receive message activities can be deactivated on arrival of one event, so only one outgoing
			// path is followed.
			$this->addNode($_v_EventBasedGateway_1);
		
			$_v_ParallelGateway_1 = new ilBasicNode($this);
			$this->addNode($_v_ParallelGateway_1);
		
			$_v_IntermediateCatchEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_1);
		
			$_v_IntermediateCatchEvent_1_detector = new ilEventDetector($_v_IntermediateCatchEvent_1);
			$_v_IntermediateCatchEvent_1_detector->setEvent(			"Course", 			"UserWasAssigned");
			$_v_IntermediateCatchEvent_1_detector->setEventSubject(	"usr", 	"0");
			$_v_IntermediateCatchEvent_1_detector->setEventContext(	"crs", 	"0");
			$_v_IntermediateCatchEvent_1_detector->setListeningTimeframe(0, 0);
			$_v_IntermediateCatchEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_2);
		
			$_v_IntermediateCatchEvent_2_detector = new ilEventDetector($_v_IntermediateCatchEvent_2);
			$_v_IntermediateCatchEvent_2_detector->setEvent(			"Course", 			"UserLeft");
			$_v_IntermediateCatchEvent_2_detector->setEventSubject(	"usr", 	"0");
			$_v_IntermediateCatchEvent_2_detector->setEventContext(	"crs", 	"0");
			$_v_IntermediateCatchEvent_2_detector->setListeningTimeframe(0, 0);
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
		}
		
?>