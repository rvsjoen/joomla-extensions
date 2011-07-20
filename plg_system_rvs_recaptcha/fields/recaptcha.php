<?php defined('_JEXEC') or die;
/**
* @version		$Id$
* @package		RVS reCaptcha
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com Rune V. Sjøen}
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

jimport('joomla.form.formfield');
jimport('joomla.html.parameter');

class JFormFieldReCaptcha extends JFormField
{
	protected $type = 'ReCaptcha';

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
  		return recaptcha_get_html($publickey).'<input type="hidden" name="jform[recaptcha]" value="1"/>';
	}
}