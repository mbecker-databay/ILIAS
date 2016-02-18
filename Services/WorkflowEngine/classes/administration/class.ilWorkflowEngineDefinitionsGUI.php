<?php

class ilWorkflowEngineDefinitionsGUI
{
	/** @var  ilObjWorkflowEngineGUI */
	protected $parent_gui;

	public function __construct($parent_gui)
	{
		$this->parent_gui = $parent_gui;
	}

	public function handle($command)
	{
		switch(strtolower($command))
		{
			case 'uploadform':
				return $this->showUploadForm();
				break;

			case 'upload':
				return $this->handleUploadSubmit();
				break;

			case 'applyfilter':
				return $this->applyFilter();
				break;

			case 'resetfilter':
				return $this->resetFilter();
				break;

			case 'start':
				return $this->startProcess();
				break;

			case 'view':
			default:
				return $this->showDefinitionsTable();
		}
	}

	public function showDefinitionsTable()
	{
		$this->initToolbar();
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineDefinitionsTableGUI.php';
		$table_gui = new ilWorkflowEngineDefinitionsTableGUI($this->parent_gui, 'definitions.view');
		$table_gui->setFilterCommand("definitions.applyfilter");
		$table_gui->setResetCommand("definitions.resetFilter");
		$table_gui->setDisableFilterHiding(false);
		return $table_gui->getHTML();
	}

	public function applyFilter()
	{
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineDefinitionsTableGUI.php';
		$table_gui = new ilWorkflowEngineDefinitionsTableGUI($this->parent_gui, 'definitions.view');
		$table_gui->writeFilterToSession();
		$table_gui->resetOffset();
		return $this->showDefinitionsTable();
	}

	public function resetFilter()
	{
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineDefinitionsTableGUI.php';
		$table_gui = new ilWorkflowEngineDefinitionsTableGUI($this->parent_gui, 'definitions.view');
		$table_gui->resetOffset();
		$table_gui->resetFilter();
		return $this->showDefinitionsTable();
	}

	public function showUploadForm()
	{
		require_once './Services/WorkflowEngine/classes/administration/class.ilUploadDefinitionForm.php';
		$form_definition = new ilUploadDefinitionForm();
		$form = $form_definition->getForm(
				$this->parent_gui->ilCtrl->getLinkTarget($this->parent_gui,'definitions.upload')
		);
		return $form->getHTML();
	}

	public function handleUploadSubmit()
	{

		$this->processUploadFormCancellation();

		require_once './Services/WorkflowEngine/classes/administration/class.ilUploadDefinitionForm.php';
		$form_definition = new ilUploadDefinitionForm();
		$form = $form_definition->getForm(
				$this->parent_gui->ilCtrl->getLinkTarget($this->parent_gui,'definitions.upload')
		);

		if(!$form->checkInput())
		{
			$form->setValuesByPost();
			return $form->getHTML();
		}

		$repo_dir_name = ilObjWorkflowEngine::getRepositoryDir();
		if(!is_dir($repo_dir_name))
		{
			mkdir($repo_dir_name, 0777, true);
		}

		$temp_dir_name =  ilObjWorkflowEngine::getTempDir();
		if(!is_dir($temp_dir_name))
		{
			mkdir($temp_dir_name, 0777, true);
		}

		$file_name = $_FILES['process_file']['name'];
		$temp_name = $_FILES['process_file']['tmp_name'];
		move_uploaded_file($temp_name, $temp_dir_name.$file_name);

		$repo_base_name = 'il'.substr($file_name,0,strpos($file_name,'.'));
		$version = 0;
		$next_version_found = false;
		while($next_version_found == false)
		{
			$version++;
			if(!file_exists($repo_dir_name.'wfd.'.$repo_base_name.'_v'.$version.'.php'))
			{
				$next_version_found = true;
			}
		}
		$repo_name = $repo_base_name.'_v'.$version.'.php';

		// Parse
		require_once './Services/WorkflowEngine/classes/parser/class.ilBPMN2Parser.php';
		$parser = new ilBPMN2Parser();
		$bpmn = file_get_contents($temp_dir_name.$file_name);
		$code = $parser->parseBPMN2XML($bpmn,$repo_name);

		file_put_contents($repo_dir_name.'wfd.'.$repo_name,$code);
		file_put_contents($repo_dir_name.'wfd.'.$repo_base_name.'_v'.$version.'.bpmn2', $bpmn);
		unlink($temp_dir_name.$file_name);

		ilUtil::sendSuccess($this->parent_gui->lng->txt('upload_parse_success'), true);
		ilUtil::redirect(
				html_entity_decode($this->parent_gui->ilCtrl->getLinkTarget($this->parent_gui, 'definitions.view'))
		);
	}

	public function initToolbar()
	{
		require_once './Services/UIComponent/Button/classes/class.ilLinkButton.php';
		$upload_wizard_button = ilLinkButton::getInstance();
		$upload_wizard_button->setCaption($this->parent_gui->lng->txt('upload_process'), false);
		$upload_wizard_button->setUrl(
				$this->parent_gui->ilCtrl->getLinkTarget($this->parent_gui, 'definitions.uploadform')
		);
		$this->parent_gui->ilToolbar->addButtonInstance($upload_wizard_button);
	}

	protected function processUploadFormCancellation()
	{
		if (isset($_POST['cmd']['cancel'])) {
			ilUtil::redirect(
					html_entity_decode(
							$this->parent_gui->ilCtrl->getLinkTarget($this->parent_gui, 'definitions.view')
					)
			);
		}
	}

	public function startProcess()
	{
		$identifier = basename($_GET['process_id']);

		require_once ilObjWorkflowEngine::getRepositoryDir() . $identifier . '.php';
		$class = substr($identifier,4);
		/** @var ilBaseWorkflow $workflow_instance */
		$workflow_instance = new $class;

		$workflow_instance->setWorkflowClass($class);
		$workflow_instance->setWorkflowLocation(ilObjWorkflowEngine::getRepositoryDir());

		$workflow_instance->startWorkflow();
		$workflow_instance->handleEvent(
				array(
						'time_passed',
						'time_passed',
						'none',
						0,
						'none',
						0
				)
		);
		require_once './Services/WorkflowEngine/classes/utils/class.ilWorkflowDbHelper.php';
		ilWorkflowDbHelper::writeWorkflow( $workflow_instance );

		ilUtil::sendSuccess($this->parent_gui->lng->txt('process_started'), true);
		ilUtil::redirect(
				html_entity_decode(
						$this->parent_gui->ilCtrl->getLinkTarget($this->parent_gui, 'definitions.view')
				)
		);
	}
}