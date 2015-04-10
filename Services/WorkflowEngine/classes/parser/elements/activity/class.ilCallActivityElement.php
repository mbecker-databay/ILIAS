<?php
/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilCallActivityElement
 *
 * @author Maximilian Becker <mbecker@databay.de>
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilCallActivityElement extends ilBaseElement
{
	public $element_varname;
	
	public function getPHP($element, ilWorkflowScaffold $class_object)
	{
		$code = "";
		$this->element_varname = '$_v_'.$element['attributes']['id'];

		$library_definition = ilBPMN2ParserUtils::extractILIASLibraryCallDefinitionFromElement($element);

		$class_object->registerRequire('./Services/WorkflowEngine/classes/nodes/class.ilBasicNode.php');
		$class_object->registerRequire('./Services/WorkflowEngine/classes/activities/class.ilStaticMethodCallActivity.php');

		$code .= '
			' . $this->element_varname . ' = new ilBasicNode($this);
			$this->addNode(' . $this->element_varname . ');
			' . $this->element_varname . '->setName(\'' . $this->element_varname . '\');
			
			' . $this->element_varname . '_callActivity = new ilStaticMethodCallActivity(' . $this->element_varname . ');
			' . $this->element_varname . '_callActivity->setName(\'' . $this->element_varname . '_callActivity\');
			' . $this->element_varname . '_callActivity->setIncludeFilename("'.$library_definition['include_filename'].'");
			' . $this->element_varname . '_callActivity->setClassAndMethodName("'.$library_definition['class_and_method'].'");
			' . $this->element_varname . '_callActivity_params = array(); // Requires Parsing of Data Associations!
			' . $this->element_varname . '_callActivity->setParameters($this, ' . $this->element_varname . '_callActivity_params);
			' . $this->element_varname . '->addActivity(' . $this->element_varname . '_callActivity);
		';
		return $code;
	}
} 