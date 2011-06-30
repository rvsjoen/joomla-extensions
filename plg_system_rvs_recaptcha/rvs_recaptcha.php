<?php defined('_JEXEC') or die;
/**
* @version		$Id: rvs_recaptcha.php 86 2011-03-31 01:35:11Z rvsjoen $
* @package		RVS reCaptcha
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com Rune V. Sjøen}
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

class plgSystemRvs_Recaptcha extends JPlugin
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
		
		if(!(
			($form->getName() == 'com_users.registration' 	&& $this->params->get('form_userreg', 1))
			||
			($form->getName() == 'com_contact.contact' 		&& $this->params->get('form_contact', 1))
		)){
			return true;
		}

		JForm::addFormPath (dirname(__FILE__).'/forms');
		JForm::addFieldPath(dirname(__FILE__).'/fields');
		$form->loadFile('rvs_recaptcha', false);
		return true;
	}
}