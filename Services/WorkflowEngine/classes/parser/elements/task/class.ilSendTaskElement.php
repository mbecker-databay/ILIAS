<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilSendTaskElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilSendTaskElement extends ilBaseElement
{
	public $element_varname;

	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		$code = "";
		$this->element_varname = '$_v_'.$element['attributes']['id'];
		$event_definition = null;
		if(count($element['children']))
		{
			foreach ($element['children'] as $child)
			{
				if($child['name'] == 'messageEventDefinition')
				{
					$event_definition = ilBPMN2ParserUtils::extractILIASEventDefinitionFromProcess($child['attributes']['messageRef'], 'message', $this->bpmn2_array);
				}
				if($child['name'] == 'signalEventDefinition')
				{
					$event_definition = ilBPMN2ParserUtils::extractILIASEventDefinitionFromProcess($child['attributes']['signalRef'], 'signal', $this->bpmn2_array);
				}
			}
		}

		$class_object->registerRequire('./Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php');
		$code .= '
			' . $this->element_varname . ' = new ilBasicNode($this);
			$this->addNode(' . $this->element_varname . ');
			' . $this->element_varname . '->setName(\'' . $this->element_varname . '\');
		';

		if(isset($event_definition['type']) && isset($event_definition['content']))
		{
			$class_object->registerRequire('./Services/WorkflowEngine/classes/activities/class.ilStaticMethodCallActivity.php');
			$code .= '
				' . $this->element_varname . '_sendTaskActivity = new ilEventRaisingActivity(' . $this->element_varname . ');
				' . $this->element_varname . '_sendTaskActivity->setName(\'' . $this->element_varname . '_sendTaskActivity\');
				' . $this->element_varname . '_sendTaskActivity->setEventType("'.$event_definition['type'].'");
				' . $this->element_varname . '_sendTaskActivity->setEventName("'.$event_definition['content'].'");
				' . $this->element_varname . '->addActivity(' . $this->element_varname . '_sendTaskActivity);
			';
		}
		$code .= $this->handleDataAssociations($element, $class_object, $this->element_varname);
		return $code;
	}
}