<?php
/* Copyright (c) 1998-2016 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once "./Services/Object/classes/class.ilObject2GUI.php" ;

/**
 * Class ilObjWorkflowEngineGUI
 *
 * @author Maximilian Becker <mbecker@databay.de>
 *
 * @version $Id$
 *
 * @ingroup Services/WorkflowEngine
 *
 * @ilCtrl_IsCalledBy ilObjWorkflowEngineGUI: ilAdministrationGUI
 */
class ilObjWorkflowEngineGUI extends ilObject2GUI
{
	/** @var ilCtrl $ilCtrl */
	public $ilCtrl;

	/** @var ilTabsGUI $ilTabs */
	public $ilTabs;

	/** @var ilLanguage $lng */
	public $lng;

	/** @var ilTemplate $tpl */
	public $tpl;

	/** @var ilTree $tree */
	public $tree;

	/** @var ilLocator $ilLocator */
	public $ilLocator;

	/** @var ilToolbarGUI $ilToolbar */
	public $ilToolbar;

	public function __construct()
	{
		global $ilTabs, $lng, $ilCtrl, $tpl, $tree, $ilLocator, $ilToolbar;

		$this->ilTabs = $ilTabs;
		$this->lng = $lng;
		$lng->loadLanguageModule('wfe');
		$this->ilCtrl = $ilCtrl;
		$this->tpl = $tpl;
		$this->tree = $tree;
		$this->ilLocator = $ilLocator;
		$this->ilToolbar = $ilToolbar;

		parent::__construct((int)$_GET['ref_id']);
		$this->assignObject();
	}

	function getType()
	{
		return null; // Just sayin.
	}

	/**
	 * @return bool
	 */
	public function executeCommand()
	{
		$next_class = $this->ilCtrl->getNextClass();

		if($next_class == '')
		{
			$this->prepareAdminOutput();
			$this->tpl->setContent($this->dispatchCommand($this->ilCtrl->getCmd('dashboard.view')));
		}
	}

	public function dispatchCommand($cmd)
	{
		$cmd_parts = explode('.', $cmd);

		switch($cmd_parts[0])
		{
			case 'definitions':
				return $this->dispatchToDefinitions($cmd_parts[1]);

			case 'instances':
				return $this->dispatchToInstances($cmd_parts[1]);

			case 'settings':
				return $this->dispatchToSettings($cmd_parts[1]);

			case 'dashboard':
				return $this->dispatchToDashboard($cmd_parts[1]);

			default:
				return $this->dispatchToDashboard($cmd_parts[0]);
		}
	}

	public function prepareAdminOutput()
	{
		$this->tpl->getStandardTemplate();

		$this->tpl->setTitleIcon(ilUtil::getImagePath('icon_wfe.svg'));
		$this->tpl->setTitle($this->object->getPresentationTitle());
		$this->tpl->setDescription($this->object->getLongDescription());

		$this->initLocator();
	}

	public function initTabs($section)
	{
		$this->ilTabs->addTab(
				'dashboard',
				$this->lng->txt('dashboard'),
				$this->ilCtrl->getLinkTarget($this, 'dashboard.view')
		);

		$this->ilTabs->addTab(
				'definitions',
				$this->lng->txt('definitions'),
				$this->ilCtrl->getLinkTarget($this, 'definitions.view')
		);

		/*
		$this->ilTabs->addTab(
				'instances',
				$this->lng->txt('instances'),
				$this->ilCtrl->getLinkTarget($this, 'instances.view')
		);

		$this->ilTabs->addTab(
				'settings',
				$this->lng->txt('settings'),
				$this->ilCtrl->getLinkTarget($this, 'settings.view')
		);
		*/

		$this->ilTabs->setTabActive($section);
	}

	public function initLocator()
	{
		$path = $this->tree->getPathFull((int)$_GET["ref_id"]);
		foreach ((array)$path as $key => $row) {
			if ($row["title"] == "Workflow Engine") {
				$row["title"] = $this->lng->txt("obj_wfe");
			}

			$this->ilCtrl->setParameter($this, "ref_id", $row["child"]);
			$this->ilLocator->addItem(
					$row["title"],
					$this->ilCtrl->getLinkTarget($this, "dashboard.view"),
					ilFrameTargetInfo::_getFrame("MainContent"),
					$row["child"]
			);
			$this->ilCtrl->setParameter($this, "ref_id", $_GET["ref_id"]);
		}
		$this->tpl->setLocator();
	}

	public function dispatchToDashboard($command)
	{
		$this->initTabs('dashboard');
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineDashboardGUI.php';
		$target_handler = new ilWorkflowEngineDashboardGUI($this);
		return $target_handler->handle($command);
	}

	public function dispatchToDefinitions($command)
	{
		$this->initTabs('definitions');
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineDefinitionsGUI.php';
		$target_handler = new ilWorkflowEngineDefinitionsGUI($this);
		return $target_handler->handle($command);
	}

	public function dispatchToInstances($command)
	{
		$this->initTabs('instances');
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineInstancesGUI.php';
		$target_handler = new ilWorkflowEngineInstancesGUI($this);
		return $target_handler->handle($command);
	}

	public function dispatchToSettings($command)
	{
		$this->initTabs('settings');
		require_once './Services/WorkflowEngine/classes/administration/class.ilWorkflowEngineSettingsGUI.php';
		$target_handler = new ilWorkflowEngineSettingsGUI($this);
		return $target_handler->handle($command);
	}
}