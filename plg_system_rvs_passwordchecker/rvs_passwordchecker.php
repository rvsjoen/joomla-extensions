<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	PasswordChecker
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-2.0.html
*/

class plgSystemRvs_PasswordChecker extends JPlugin
{
	public function __construct(&$subject, $config = array()){
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onContentPrepareForm($form, $data){
		if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Only display on supported forms, otherwise just return
		if(!(($form->getName() == 'com_users.profile' && JRequest::getVar('layout') == 'edit')
			|| $form->getName() == 'com_users.registration')){
			return true;
		}

		JForm::addFormPath (dirname(__FILE__).'/forms');
		JForm::addFieldPath(dirname(__FILE__).'/fields');

		if($form->getName() == 'com_users.profile'){
//			$form->loadFile('rvs_passwordcheck_profile', true);
			$form->loadFile('rvs_passwordcheck');
		} else if ($form->getName() == 'com_users.registration') {
//			$form->loadFile('rvs_passwordcheck_registration', true);
			$form->loadFile('rvs_passwordcheck');
		}

		return true;
	}
}
