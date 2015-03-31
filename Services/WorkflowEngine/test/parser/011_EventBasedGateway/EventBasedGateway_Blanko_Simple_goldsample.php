<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/detectors/class.ilEventDetector.php';
require_once './Services/WorkflowEngine/classes/emitters/class.ilActivationEmitter.php';
require_once './Services/WorkflowEngine/classes/detectors/class.ilSimpleDetector.php';

		class EventBasedGateway_Blanko_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_EventBasedGateway_1 = new ilBasicNode($this);
			$_v_EventBasedGateway_1->setIsForwardConditionNode(true);
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
			$_v_IntermediateCatchEvent_1->addDetector($_v_IntermediateCatchEvent_1_detector);
			
			$_v_IntermediateCatchEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_2);
		
			$_v_IntermediateCatchEvent_2_detector = new ilEventDetector($_v_IntermediateCatchEvent_2);
			$_v_IntermediateCatchEvent_2_detector->setEvent(			"Course", 			"UserLeft");
			$_v_IntermediateCatchEvent_2_detector->setEventSubject(	"usr", 	"0");
			$_v_IntermediateCatchEvent_2_detector->setEventContext(	"crs", 	"0");
			$_v_IntermediateCatchEvent_2_detector->setListeningTimeframe(0, 0);
			$_v_IntermediateCatchEvent_2->addDetector($_v_IntermediateCatchEvent_2_detector);
			
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_IntermediateCatchEvent_2_detector = new ilSimpleDetector($_v_IntermediateCatchEvent_2);
			$_v_IntermediateCatchEvent_2->addDetector($_v_IntermediateCatchEvent_2_detector);
			$_v_EventBasedGateway_1_emitter = new ilActivationEmitter($_v_EventBasedGateway_1);
			$_v_EventBasedGateway_1_emitter->setTargetDetector($_v_IntermediateCatchEvent_2_detector);
			$_v_EventBasedGateway_1->addEmitter($_v_EventBasedGateway_1_emitter);
		
			$_v_IntermediateCatchEvent_1_detector = new ilSimpleDetector($_v_IntermediateCatchEvent_1);
			$_v_IntermediateCatchEvent_1->addDetector($_v_IntermediateCatchEvent_1_detector);
			$_v_EventBasedGateway_1_emitter = new ilActivationEmitter($_v_EventBasedGateway_1);
			$_v_EventBasedGateway_1_emitter->setTargetDetector($_v_IntermediateCatchEvent_1_detector);
			$_v_EventBasedGateway_1->addEmitter($_v_EventBasedGateway_1_emitter);
		
			$_v_ParallelGateway_1_detector = new ilSimpleDetector($_v_ParallelGateway_1);
			$_v_ParallelGateway_1->addDetector($_v_ParallelGateway_1_detector);
			$_v_IntermediateCatchEvent_1_emitter = new ilActivationEmitter($_v_IntermediateCatchEvent_1);
			$_v_IntermediateCatchEvent_1_emitter->setTargetDetector($_v_ParallelGateway_1_detector);
			$_v_IntermediateCatchEvent_1->addEmitter($_v_IntermediateCatchEvent_1_emitter);
		
			$_v_ParallelGateway_1_detector = new ilSimpleDetector($_v_ParallelGateway_1);
			$_v_ParallelGateway_1->addDetector($_v_ParallelGateway_1_detector);
			$_v_IntermediateCatchEvent_2_emitter = new ilActivationEmitter($_v_IntermediateCatchEvent_2);
			$_v_IntermediateCatchEvent_2_emitter->setTargetDetector($_v_ParallelGateway_1_detector);
			$_v_IntermediateCatchEvent_2->addEmitter($_v_IntermediateCatchEvent_2_emitter);
		
			$_v_EndEvent_1_detector = new ilSimpleDetector($_v_EndEvent_1);
			$_v_EndEvent_1->addDetector($_v_EndEvent_1_detector);
			$_v_ParallelGateway_1_emitter = new ilActivationEmitter($_v_ParallelGateway_1);
			$_v_ParallelGateway_1_emitter->setTargetDetector($_v_EndEvent_1_detector);
			$_v_ParallelGateway_1->addEmitter($_v_ParallelGateway_1_emitter);
		
			$_v_EventBasedGateway_1_detector = new ilSimpleDetector($_v_EventBasedGateway_1);
			$_v_EventBasedGateway_1->addDetector($_v_EventBasedGateway_1_detector);
			$_v_StartEvent_1_emitter = new ilActivationEmitter($_v_StartEvent_1);
			$_v_StartEvent_1_emitter->setTargetDetector($_v_EventBasedGateway_1_detector);
			$_v_StartEvent_1->addEmitter($_v_StartEvent_1_emitter);
		
			}
		}
		
?>