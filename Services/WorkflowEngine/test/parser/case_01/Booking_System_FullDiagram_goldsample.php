<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/detectors/class.ilEventDetector.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilCaseNode.php';
require_once './Services/WorkflowEngine/classes/activities/class.ilScriptActivity.php';

		class Booking_System_FullDiagram extends ilBaseWorkflow
		{
		
			public static $startEventRequired = true;
			
			public static function getStartEventInfo()
			{
				$events[] = array(
					'type' 			=> '', 
					'content' 		=> '', 
					'subject_type' 	=> '', 
					'subject_id'	=> '', 
					'context_type'	=> '', 
					'context_id'	=> '', 
				);
				
				$events[] = array(
					'type' 			=> '', 
					'content' 		=> '', 
					'subject_type' 	=> '', 
					'subject_id'	=> '', 
					'context_type'	=> '', 
					'context_id'	=> '', 
				);
				
				$events[] = array(
					'type' 			=> '', 
					'content' 		=> '', 
					'subject_type' 	=> '', 
					'subject_id'	=> '', 
					'context_type'	=> '', 
					'context_id'	=> '', 
				);
				
				$events[] = array(
					'type' 			=> '', 
					'content' 		=> '', 
					'subject_type' 	=> '', 
					'subject_id'	=> '', 
					'context_type'	=> '', 
					'context_id'	=> '', 
				);
				
				$events[] = array(
					'type' 			=> '', 
					'content' 		=> '', 
					'subject_type' 	=> '', 
					'subject_id'	=> '', 
					'context_type'	=> '', 
					'context_id'	=> '', 
				);
				
				$events[] = array(
					'type' 			=> '', 
					'content' 		=> '', 
					'subject_type' 	=> '', 
					'subject_id'	=> '', 
					'context_type'	=> '', 
					'context_id'	=> '', 
				);
				
				return $events; 
			}
			
			public function __construct()
			{
		
			$_v_StartEvent_2 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_2);
		
			$_v_StartEvent_2_detector = new ilEventDetector($_v_StartEvent_2);
			$_v_StartEvent_2_detector->setEvent(			"", 			"");
			$_v_StartEvent_2_detector->setEventSubject(	"", 	"");
			$_v_StartEvent_2_detector->setEventContext(	"", 	"");
			
			$this->defineInstanceVar("Data Object 1");
		
			$this->defineInstanceVar("Data Object 2");
		
			$_v_ServiceTask_1 = new ilBasicNode($this);
			$this->addNode($_v_ServiceTask_1);
		
			$_v_ExclusiveGateway_1 = new ilCaseNode($this);
			$this->addNode($_v_ExclusiveGateway_1);
		
			$_v_SendTask_1 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_1);
		
			$_v_ServiceTask_2 = new ilBasicNode($this);
			$this->addNode($_v_ServiceTask_2);
		
			$this->defineInstanceVar("Data Object 3");
		
			$_v_SendTask_2 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_2);
		
			$_v_ExclusiveGateway_3 = new ilCaseNode($this);
			$this->addNode($_v_ExclusiveGateway_3);
		
			$_v_EndEvent_3 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_3);
		
			$_v_StartEvent_3 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_3);
		
			$_v_StartEvent_3_detector = new ilEventDetector($_v_StartEvent_3);
			$_v_StartEvent_3_detector->setEvent(			"", 			"");
			$_v_StartEvent_3_detector->setEventSubject(	"", 	"");
			$_v_StartEvent_3_detector->setEventContext(	"", 	"");
			
			$_v_ScriptTask_1 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_1);
		
			$_v_ScriptTask_1_scriptActivity = new ilScriptActivity($_v_ScriptTask_1);
			$_v_ScriptTask_1_scriptActivity->setMethod('_v_ScriptTask_1_script');
			$_v_ScriptTask_1->addActivity($_v_ScriptTask_1_scriptActivity);
			
			$_v_SendTask_3 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_3);
		
			$_v_EndEvent_4 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_4);
		
			$_v_StartEvent_4 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_4);
		
			$_v_StartEvent_4_detector = new ilEventDetector($_v_StartEvent_4);
			$_v_StartEvent_4_detector->setEvent(			"", 			"");
			$_v_StartEvent_4_detector->setEventSubject(	"", 	"");
			$_v_StartEvent_4_detector->setEventContext(	"", 	"");
			
			$_v_ScriptTask_2 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_2);
		
			$_v_ScriptTask_2_scriptActivity = new ilScriptActivity($_v_ScriptTask_2);
			$_v_ScriptTask_2_scriptActivity->setMethod('_v_ScriptTask_2_script');
			$_v_ScriptTask_2->addActivity($_v_ScriptTask_2_scriptActivity);
			
			$_v_SendTask_4 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_4);
		
			$_v_IntermediateThrowEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateThrowEvent_1);
		
			$_v_EndEvent_6 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_6);
		
			$_v_StartEvent_5 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_5);
		
			$_v_StartEvent_5_detector = new ilEventDetector($_v_StartEvent_5);
			$_v_StartEvent_5_detector->setEvent(			"", 			"");
			$_v_StartEvent_5_detector->setEventSubject(	"", 	"");
			$_v_StartEvent_5_detector->setEventContext(	"", 	"");
			
			$_v_ScriptTask_3 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_3);
		
			$_v_ScriptTask_3_scriptActivity = new ilScriptActivity($_v_ScriptTask_3);
			$_v_ScriptTask_3_scriptActivity->setMethod('_v_ScriptTask_3_script');
			$_v_ScriptTask_3->addActivity($_v_ScriptTask_3_scriptActivity);
			
			$_v_ExclusiveGateway_4 = new ilCaseNode($this);
			$this->addNode($_v_ExclusiveGateway_4);
		
			$_v_ScriptTask_5 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_5);
		
			$_v_ScriptTask_5_scriptActivity = new ilScriptActivity($_v_ScriptTask_5);
			$_v_ScriptTask_5_scriptActivity->setMethod('_v_ScriptTask_5_script');
			$_v_ScriptTask_5->addActivity($_v_ScriptTask_5_scriptActivity);
			
			$_v_SendTask_5 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_5);
		
			$_v_ExclusiveGateway_5 = new ilCaseNode($this);
			$this->addNode($_v_ExclusiveGateway_5);
		
			$_v_EndEvent_7 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_7);
		
			$_v_StartEvent_6 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_6);
		
			$_v_StartEvent_6_detector = new ilEventDetector($_v_StartEvent_6);
			$_v_StartEvent_6_detector->setEvent(			"", 			"");
			$_v_StartEvent_6_detector->setEventSubject(	"", 	"");
			$_v_StartEvent_6_detector->setEventContext(	"", 	"");
			
			$this->defineInstanceVar("Data Object 4");
		
			$_v_IntermediateCatchEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_IntermediateCatchEvent_1);
		
			$_v_IntermediateCatchEvent_1_detector = new ilEventDetector($_v_IntermediateCatchEvent_1);
			$_v_IntermediateCatchEvent_1_detector->setEvent(			"time_passed", 			"time_passed");
			$_v_IntermediateCatchEvent_1_detector->setEventSubject(	"none", 	"0");
			$_v_IntermediateCatchEvent_1_detector->setEventContext(	"none", 	"0");
			$_v_IntermediateCatchEvent_1_detector->setListeningTimeframe(0, 0);
			$_v_ExclusiveGateway_6 = new ilCaseNode($this);
			$this->addNode($_v_ExclusiveGateway_6);
		
			$_v_SendTask_6 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_6);
		
			$_v_ExclusiveGateway_7 = new ilCaseNode($this);
			$this->addNode($_v_ExclusiveGateway_7);
		
			$_v_EndEvent_9 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_9);
		
			$_v_StartEvent_7 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_7);
		
			$_v_StartEvent_7_detector = new ilEventDetector($_v_StartEvent_7);
			$_v_StartEvent_7_detector->setEvent(			"", 			"");
			$_v_StartEvent_7_detector->setEventSubject(	"", 	"");
			$_v_StartEvent_7_detector->setEventContext(	"", 	"");
			
			$_v_SendTask_7 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_7);
		
			$_v_ScriptTask_7 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_7);
		
			$_v_ScriptTask_7_scriptActivity = new ilScriptActivity($_v_ScriptTask_7);
			$_v_ScriptTask_7_scriptActivity->setMethod('_v_ScriptTask_7_script');
			$_v_ScriptTask_7->addActivity($_v_ScriptTask_7_scriptActivity);
			
			$_v_ScriptTask_8 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_8);
		
			$_v_ScriptTask_8_scriptActivity = new ilScriptActivity($_v_ScriptTask_8);
			$_v_ScriptTask_8_scriptActivity->setMethod('_v_ScriptTask_8_script');
			$_v_ScriptTask_8->addActivity($_v_ScriptTask_8_scriptActivity);
			
			$_v_ParallelGateway_1 = new ilBasicNode($this);
			$this->addNode($_v_ParallelGateway_1);
		
			$_v_SendTask_8 = new ilBasicNode($this);
			$this->addNode($_v_SendTask_8);
		
			$_v_InclusiveGateway_1 = new ilCaseNode($this);
			$this->addNode($_v_InclusiveGateway_1);
		
			$_v_EndEvent_10 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_10);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			// association_missing
		
			}
			
			public function _v_ScriptTask_1_script($context)
			 {
			 
			 }
			
			
			public function _v_ScriptTask_2_script($context)
			 {
			 
			 }
			
			
			public function _v_ScriptTask_3_script($context)
			 {
			 
			 }
			
			
			public function _v_ScriptTask_5_script($context)
			 {
			 
			 }
			
			
			public function _v_ScriptTask_7_script($context)
			 {
			 
			 }
			
			
			public function _v_ScriptTask_8_script($context)
			 {
			 
			 }
			
		}
		
?>