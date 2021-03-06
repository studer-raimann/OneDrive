<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once('./Modules/Cloud/classes/class.ilCloudPluginCreationGUI.php');
require_once('./Customizing/global/plugins/Modules/Cloud/CloudHook/OneDrive/classes/Client/class.exodPath.php');

/**
 * Class ilOneDriveCreationGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilOneDriveCreationGUI extends ilCloudPluginCreationGUI {

	const F_BASE_FOLDER = 'onedrive_base_folder';
	const F_DEFAULT_BASE_FOLDER = 'onedrive_default_base_folder';
	const F_CUSTOM_FOLDER_SELECTION = 'onedrive_custom_folder_selection';
	const F_CUSTOM_BASE_FOLDER_INPUT = 'onedrive_custom_base_folder_input';


	/**
	 * @param ilRadioOption $option
	 */
	public function initPluginCreationFormSection(ilRadioOption $option) {
	    if ($message = $this->getAdminConfigObject()->getValue(exodConfig::F_INFO_MESSAGE)) {
	        ilUtil::sendInfo($message);
        }
		//		$option->setInfo($this->txt('create_info1') . '</br>' . $this->txt('create_info2') . $this->getAdminConfigObject()->getAppName()
		//			. $this->txt('create_info3'));
		$sub_selection = new ilRadioGroupInputGUI($this->txt('base_folder'), self::F_BASE_FOLDER);
		$sub_selection->setRequired(true);

		$option_default = new ilRadioOption($this->txt('default_base_folder'), self::F_DEFAULT_BASE_FOLDER);
		$option_default->setInfo($this->txt('default_base_folder_info'));

		$option_custom = new ilRadioOption($this->txt('custom_base_folder'), self::F_CUSTOM_FOLDER_SELECTION);
        $option_custom->setInfo($this->txt('custom_base_folder_info'));

		$custom_base_folder_input = new ilTextInputGUI($this->txt('custom_base_folder_input'), self::F_CUSTOM_BASE_FOLDER_INPUT);
		$custom_base_folder_input->setRequired(true);
		$custom_base_folder_input->setInfo($this->txt('custom_base_folder_input_info'));

		$option_custom->addSubItem($custom_base_folder_input);
		$sub_selection->addOption($option_default);
		$sub_selection->addOption($option_custom);

		$sub_selection->setValue(self::F_DEFAULT_BASE_FOLDER);

		$option->addSubItem($sub_selection);
	}


	public function txt($var = "") {
		return parent::txt('add_new_' . $var);
	}


	/**
	 * @param ilPropertyFormGUI $form
	 * @param ilObjCloud        $obj
	 *
	 * @throws ilCloudException
	 */
	public function afterSavePluginCreation(ilObjCloud &$obj, ilPropertyFormGUI $form) {
		$adjustedTitle = exodPath::validateBasename($form->getInput("title"));
		$obj->setTitle($adjustedTitle);
		if ($form->getInput(self::F_BASE_FOLDER) == self::F_DEFAULT_BASE_FOLDER) {
			$root_folder = $obj->getTitle();
		} else {
			$root_folder = $form->getInput(self::F_CUSTOM_BASE_FOLDER_INPUT);
		}
		if ($this->getAdminConfigObject()->getValue(exodConfig::F_CLIENT_TYPE) == exodApp::TYPE_BUSINESS) {
			$root_folder = '/ILIASCloud/' . ltrim($root_folder, "/");
		}

		$obj->setRootFolder($root_folder);
	}

}
