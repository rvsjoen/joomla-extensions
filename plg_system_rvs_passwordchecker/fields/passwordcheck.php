<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	PasswordChecker
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-2.0.html
*/

jimport('joomla.form.formfield');
jimport('joomla.html.parameter');

class JFormFieldPasswordCheck extends JFormField
{
	protected $type = 'PasswordCheck';

	public function __construct($form = null){
		require_once(dirname(__FILE__).DS.'..'.DS.'include'.DS.'recaptchalib.php');
		$this->params = new JParameter(JPluginHelper::getPlugin('system', 'rvs_recaptcha')->params);
		$doc = JFactory::getDocument();
		
		$theme 		= $this->params->get('theme', 'clean');
		$lang 		= $this->params->get('lang', 'en');
		$tabindex 	= $this->params->get('tabindex', 0);
		
		$doc->addScriptDeclaration("
			var RecaptchaOptions = {
			   theme : '${theme}',
			   tabindex : ${tabindex},
			   lang : '${lang}'
			};
		");

		parent::__construct($form);
	}
	
	protected function getInput(){
		$publickey = $this->params->get('public_key');
  		return recaptcha_get_html($publickey);
	}
}