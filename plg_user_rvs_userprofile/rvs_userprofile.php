<?php defined('_JEXEC') or die;
/**
 * @package		RVS
 * @subpackage	UserProfile
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

jimport('joomla.event.plugin');

class plgUserRVS_UserProfile extends JPlugin {

	public function __construct(& $subject, $config){
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
}
