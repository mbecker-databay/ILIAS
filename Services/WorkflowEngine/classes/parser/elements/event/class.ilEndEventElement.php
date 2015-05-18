<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilEndEventElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilEndEventElement extends ilBaseElement
{
	public $element_varname;

	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		$code = "";
		$this->element_varname = '$_v_'.$element['attributes']['id'];

		$event_definition = null;

		$terminate = false;

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
				if($child['name'] == 'terminateEventDefinition')
				{
					$terminate = true;
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
			$class_object->registerRequire('./Services/WorkflowEngine/classes/activities/class.ilEventRaisingActivity.php');
			$code .= '
				' . $this->element_varname . '_throwEventActivity = new ilEventRaisingActivity(' . $this->element_varname . ');
				' . $this->element_varname . '_throwEventActivity->setName(\'' . $this->element_varname . '_throwEventActivity\');
				' . $this->element_varname . '_throwEventActivity->setEventType("'.$event_definition['type'].'");
				' . $this->element_varname . '_throwEventActivity->setEventName("'.$event_definition['content'].'");
				' . $this->element_varname . '->addActivity(' . $this->element_varname . '_throwEventActivity);
			';
		}

		if($terminate)
		{
			$class_object->registerRequire('./Services/WorkflowEngine/classes/activities/class.ilStopWorkflowActivity.php');
			$code .= '
				' . $this->element_varname . '_terminationEventActivity = new ilStopWorkflowActivity(' . $this->element_varname . ');
				' . $this->element_varname . '->addActivity(' . $this->element_varname . '_terminationEventActivity);
			';
		}
		$code .= $this->handleDataAssociations($element, $class_object, $this->element_varname);
		return $code;
	}
} 