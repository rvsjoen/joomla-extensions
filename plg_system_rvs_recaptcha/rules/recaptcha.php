<?php defined('_JEXEC') or die;
/**
* @version		$Id: recaptcha.php 70 2011-01-25 11:07:36Z rvsjoen $
* @package		RVS reCaptcha
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com Rune V. Sjøen}
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

jimport('joomla.form.formrule');
jimport('joomla.html.parameter');

class JFormRuleReCaptcha extends JFormRule
{
	public function __construct(){
		require_once(dirname(__FILE__).DS.'..'.DS.'include'.DS.'recaptchalib.php');
	}
	
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		$params 	= new JParameter(JPluginHelper::getPlugin('system', 'rvs_recaptcha')->params);
		$privatekey = $params->get('private_key');
		$addr		= JRequest::getVar('REMOTE_ADDR', null, 'server');
		$challenge	= JRequest::getVar('recaptcha_challenge_field');
		$response	= JRequest::getVar('recaptcha_response_field');
		$result 	= recaptcha_check_answer ($privatekey, $addr, $challenge, $response);
		return $result->is_valid;
	}
}