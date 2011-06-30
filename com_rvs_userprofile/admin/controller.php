<?php defined('_JEXEC') or die;
/**
 * @package		RVS
 * @subpackage	UserProfile
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

jimport('joomla.application.component.controller');

class RVS_UserProfileController extends JController 
{	
	function display(){
		echo "Hello Admins!";
	}
}