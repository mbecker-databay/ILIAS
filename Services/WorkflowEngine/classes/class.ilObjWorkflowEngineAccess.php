<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once './Services/Object/classes/class.ilObjectAccess.php';

class ilObjWorkflowEngineAccess extends ilObjectAccess
{
	/**
	 * checks wether a user may invoke a command or not
	 * (this method is called by ilAccessHandler::checkAccess)
	 *
	 * @param	string		$a_cmd		command (not permission!)
	 * @param	string		$a_permission	permission
	 * @param	int			$a_ref_id	reference id
	 * @param	int			$a_obj_id	object id
	 * @param	int			$a_user_id	user id (if not provided, current user is taken)
	 *
	 * @return	boolean		true, if everything is ok
	 */
	function _checkAccess($a_cmd, $a_permission, $a_ref_id, $a_obj_id, $a_user_id = "")
	{
		global $ilUser, $lng, $rbacsystem, $ilAccess;

		if ($a_user_id == "")
		{
			$a_user_id = $ilUser->getId();
		}

		// Deal with commands
		switch ($a_cmd)
		{
			case "view":
					$ilAccess->addInfoItem(IL_NO_OBJECT_ACCESS, $lng->txt("crs_status_blocked"));
					return false;
				break;

			case 'leave':
		}

		// Deal with permissions
		switch ($a_permission)
		{
			case 'visible':
					return $rbacsystem->checkAccessOfUser($a_user_id,'visible',$a_ref_id);
				break;

			case 'read':
				return $rbacsystem->checkAccessOfUser($a_user_id,'write',$a_ref_id);
				// Sample denial.
				if(!$active)
				{
					$ilAccess->addInfoItem(IL_NO_OBJECT_ACCESS, $lng->txt("offline"));
					return false;
				}
				break;
		}

		return true; // ORLY?
	}
}