<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	PasswordChecker
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-2.0.html
*/

jimport('joomla.form.formfield');
JFormHelper::loadFieldType('password');

class JFormFieldPasswordCheck extends JFormFieldPassword
{
	protected $type = 'PasswordCheck';

	public function __construct($form = null){
		parent::__construct($form);
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
	}
	
	protected function getInput(){
		return parent::getInput()."
			<div id='rvs_passwordchecker_scorebar' style='background-position: 0pt 50%;'>
				<div id='rvs_passwordchecker_score'>0%</div>
			</div>
  			<div id='rvs_passwordchecker_complexity'></div>
			";
	}
}
