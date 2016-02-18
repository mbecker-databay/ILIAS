<?php
/* Copyright (c) 1998-2016 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once './Services/Table/classes/class.ilTable2GUI.php';
require_once './Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php';

/**
 * Class ilWorkflowEngineDefinitionsTableGUI
 *
 * @author Maximilian Becker <mbecker@databay.de>
 *
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 */
class ilWorkflowEngineDefinitionsTableGUI extends ilTable2GUI
{
	/** @var ilCtrl $ilCtrl */
	protected $ilCtrl;

	/** @var ilLanguage $lng */
	protected $lng;

	public function __construct($parent_obj, $parent_cmd, $template_context ="")
	{
		$this->setId('wfedef');
		parent::__construct($parent_obj, $parent_cmd, $template_context);

		global $ilCtrl, $lng;
		$this->ilCtrl = $ilCtrl;
		$this->lng = $lng;

		$this->initColumns();
		$this->setEnableHeader(true);

		$this->setFormAction($this->ilCtrl->getFormAction($parent_obj));

		$this->initFilter();

		$this->setRowTemplate("tpl.wfe_def_row.html", "Services/WorkflowEngine");
		$this->getProcessesForDisplay();

		$this->setTitle($this->lng->txt("definitions"));
	}

	public function initFilter()
	{
		require_once './Services/Form/classes/class.ilTextInputGUI.php';
		$title_filter_input = new ilTextInputGUI($this->lng->txt("title"), "title");
		$title_filter_input->setMaxLength(64);
		$title_filter_input->setSize(20);
		$this->addFilterItem($title_filter_input);
		$title_filter_input->readFromSession();
		$this->filter["title"] = $title_filter_input->getValue();

		require_once './Services/Form/classes/class.ilCheckboxInputGUI.php';
		$instances_filter_input = new ilCheckboxInputGUI($this->lng->txt('instances'), 'instances');
		$this->addFilterItem($instances_filter_input);
		$instances_filter_input->readFromSession();
		$this->filter['instances'] = $instances_filter_input->getChecked();
	}

	public function initColumns()
	{
		$this->addColumn($this->lng->txt("title"), "title", "20%");

		$selected_columns = $this->getSelectedColumns();

		if(in_array('file', $selected_columns))
		{
			$this->addColumn($this->lng->txt("file"), "file", "30%");
		}

		if(in_array('version', $selected_columns))
		{
			$this->addColumn($this->lng->txt("version"), "version", "10%");
		}

		if(in_array('status', $selected_columns))
		{
			$this->addColumn($this->lng->txt("status"), "status", "10%");
		}

		if(in_array('instances', $selected_columns))
		{
			$this->addColumn($this->lng->txt("instances"), "instances", "15%");
		}

		$this->addColumn($this->lng->txt("actions"), "", "10%");

	}
	public function getSelectableColumns()
	{
		$cols["file"] = array(
			"txt" => $this->lng->txt("file"),
			"default" => true);
		$cols["version"] = array(
			"txt" => $this->lng->txt("version"),
			"default" => true);
		$cols["status"] = array(
			"txt" => $this->lng->txt("status"),
			"default" => true);
		$cols["instances"] = array(
			"txt" => $this->lng->txt("instances"),
			"default" => true);
		return $cols;
	}

	public function getProcessesForDisplay()
	{
		global $ilDB;
		$query = 'SELECT workflow_class, count(workflow_id) total, sum(active) active
				  FROM wfe_workflows
				  GROUP BY workflow_class';
		$result = $ilDB->query($query);
		$stats = array();
		while($row = $ilDB->fetchAssoc($result))
		{
			$stats[$row['workflow_class']] = array( 'total' => $row['total'], 'active' => $row['active'] );
		}

		$entries = scandir(ilObjWorkflowEngine::getRepositoryDir());
		$base_list = array();
		foreach($entries as $entry)
		{
			if( $entry == '.' || $entry == '..' )
			{
				continue;
			}
			if(substr($entry, strlen($entry)-6) == '.bpmn2')
			{
				$file_entry = array();
				$file_entry['file'] = $entry;
				$file_entry['id'] = substr($entry, 0, strlen($entry)-6);
				$parts = explode('_', substr($entry, 6, strlen($entry)-12));

				$file_entry['status'] = 'OK';
				if(!file_exists(ilObjWorkflowEngine::getRepositoryDir() . $file_entry['id']. '.php'))
				{
					$file_entry['status'] = $this->lng->txt('missing_parsed_class');
				}

				$file_entry['version'] = substr(array_pop($parts),1);
				$file_entry['title'] = implode(' ', $parts);
				$file_entry['instances'] = $stats[substr($file_entry['id'],4)];

				if(!$this->isFiltered($file_entry))
				{
					$base_list[] = $file_entry;
				}
			}
		}

		$this->setDefaultOrderField("nr");
		$this->setDefaultOrderDirection("asc");
		$this->setData($base_list);
	}

	public function isFiltered($row)
	{
		// Title filter
		$title_filter = $this->getFilterItemByPostVar('title');
		if($title_filter->getValue() != null)
		{
			if(strpos(strtolower($row['title']),strtolower($title_filter->getValue())) === false)
			{
				return true;
			}
		}

		// Instances filter
		$instances_filter = $this->getFilterItemByPostVar('instances');
		if($instances_filter->getChecked() && $row['instances']['active'] == 0)
		{
			return true;
		}

		return false;
	}

	protected function fillRow($set)
	{

		$this->tpl->setVariable('VAL_TITLE', $set['title']);

		$selected_columns = $this->getSelectedColumns();

		if(in_array('file', $selected_columns))
		{
			$this->tpl->setVariable('VAL_FILE', $set['file']);
		}

		if(in_array('version', $selected_columns))
		{
			$this->tpl->setVariable('VAL_VERSION', $set['version']);
		}

		if(in_array('status', $selected_columns))
		{
			if($set['status'] != 'OK')
			{
				$this->tpl->setVariable('VAL_STATUS', $set['status']);
			}
			else
			{
				$this->tpl->setVariable('VAL_STATUS', $this->lng->txt('ok'));
			}
		}

		if(in_array('instances', $selected_columns))
		{
			$this->tpl->setVariable('TXT_INSTANCES_TOTAL', $this->lng->txt('total'));
			$this->tpl->setVariable('VAL_INSTANCES_TOTAL', $set['instances']['total']);
			$this->tpl->setVariable('TXT_INSTANCES_ACTIVE', $this->lng->txt('active'));
			$this->tpl->setVariable('VAL_INSTANCES_ACTIVE', $set['instances']['active']);
		}

		$action = new ilAdvancedSelectionListGUI();
		$action->setId('asl_' . $set['id']);
		$action->setListTitle($this->lng->txt('actions'));
		$this->ilCtrl->setParameter($this->parent_obj, 'process_id', $set['id']);
		$action->addItem(
			$this->lng->txt('start_process'),
			'start',
			$this->ilCtrl->getLinkTarget($this->parent_obj ,'definitions.start')
		);

		$this->tpl->setVariable('HTML_ASL', $action->getHTML());
	}
}