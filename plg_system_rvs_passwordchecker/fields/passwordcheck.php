<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	PasswordChecker
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-2.0.html
*/

jimport('joomla.form.formfield');

class JFormFieldPasswordCheck extends JFormField
{
	protected $type = 'PasswordCheck';

	public function __construct($form = null){
		JHtml::_('behavior.mootools');
		$doc = JFactory::getDocument();	
		$doc->addStyleSheet(JURI::root(true).'/media/plg_system_rvs_passwordchecker/css/default.css');
		$doc->addScript(JURI::root(true).'/media/plg_system_rvs_passwordchecker/js/pwd_meter.js');
		$doc->addScriptDeclaration("
		window.addEvent('domready', function() {
			$('jform_password1').addEvent('keyup', function() {
				chkPass(this.value);				
			});
		});
		");
		parent::__construct($form);
	}
	
	protected function getInput(){
  		return "
  			<div id='score'>0%</div>
  			<div id='scorebar' style='background-position: 0pt 50%;'>&nbsp;</div>
  			<div id='complexity'></div>
  		";
	}
}