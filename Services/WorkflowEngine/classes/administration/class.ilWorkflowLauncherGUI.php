<?php

class ilWorkflowLauncherGUI
{
	/** @var string $form_action */
	protected $form_action;

	/**
	 * ilWorkflowLauncherGUI constructor.
	 *
	 * @param string $form_action
	 */
	public function __construct($form_action)
	{
		$this->form_action = $form_action;
	}

	/**
	 * @param array $input_vars
	 * @return ilPropertyFormGUI
	 */
	public function getForm($input_vars)
	{
		global $lng;

		require_once './Services/Form/classes/class.ilPropertyFormGUI.php';
		$form = new ilPropertyFormGUI();
		$form->setTitle($lng->txt('input_variables_required'));
		$form->setDescription($lng->txt('input_variables_desc'));

		foreach($input_vars as $input_var)
		{
			$item = new ilTextInputGUI($input_var, $input_var);
			$item->setRequired(true);
			$form->addItem($item);
		}

		$form->addCommandButton('start', $lng->txt('start_process'));
		$form->addCommandButton('cancel', $lng->txt('cancel'));
		return $form;
	}
}