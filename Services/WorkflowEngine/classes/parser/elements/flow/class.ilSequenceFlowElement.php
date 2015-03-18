<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilSequenceFlowElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilSequenceFlowElement extends ilBaseElement
{
	public $element_varname;
	
	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		$code = "";
		$this->element_varname = '$_v_'.$element['attributes']['id'];

		$source_element = '$_v_'.$element['attributes']['sourceRef'];
		$target_element = '$_v_'.$element['attributes']['targetRef'];

		$code .= '
			'.$target_element.'_detector = new ilSimpleDetector('.$target_element.');
			'.$target_element.'->addDetector('.$target_element.'_detector);
			'.$source_element.'_emitter = new ilActivationEmitter('.$source_element.');
			'.$source_element.'_emitter->setTargetDetector('.$target_element.'_detector);
			'.$source_element.'->addEmitter('.$source_element.'_emitter);
		';

		$class_object->registerRequire('./Services/WorkflowEngine/classes/emitters/class.ilActivationEmitter.php');
		$class_object->registerRequire('./Services/WorkflowEngine/classes/detectors/class.ilSimpleDetector.php');
		return $code;
	}
} 