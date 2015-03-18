<?php
require_once './Services/WorkflowEngine/classes/workflows/class.ilBaseWorkflow.php';
require_once './Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php';
require_once './Services/WorkflowEngine/classes/activities/class.ilScriptActivity.php';

		class Task_ScriptTask_Simple extends ilBaseWorkflow
		{
		
			public static $startEventRequired = false;
		
			public function __construct()
			{
		
			$_v_StartEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_StartEvent_1);
		
			$this->setStartNode($_v_StartEvent_1);
			
			$_v_ScriptTask_1 = new ilBasicNode($this);
			$this->addNode($_v_ScriptTask_1);
		
			$_v_ScriptTask_1_scriptActivity = new ilScriptActivity($_v_ScriptTask_1);
			$_v_ScriptTask_1_scriptActivity->setMethod('_v_ScriptTask_1_script');
			$_v_ScriptTask_1->addActivity($_v_ScriptTask_1_scriptActivity);
			
			$_v_EndEvent_1 = new ilBasicNode($this);
			$this->addNode($_v_EndEvent_1);
		
			// sequence_flow_missing
		
			// sequence_flow_missing
		
			}
			
			public function _v_ScriptTask_1_script($context)
			 {
			 global $ilLog;
$ilLog->write('Test Log Entry');
			 }
			
		}
		
?>