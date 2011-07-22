<?php defined('_JEXEC') or die;
/**
 * @package		RVS
 * @subpackage	Countdown
 * @author		Rune V. Sjøen <rvsjoen@gmail.com>
 * @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

$doc = JFactory::getDocument();

//var_dump($params);

$doc->addScriptDeclaration("
		var RVS = {
			updateTimer: function(){
				var d = new Date();
				$('rvs_countdown').set('html', d.format('%m/%d/%y %H:%m:%S'));
			}
		}	
		window.addEvent('domready', function() {
			RVS.updateTimer()
			setInterval('RVS.updateTimer()', 1000);
		});
");

require JModuleHelper::getLayoutPath('mod_rvs_countdown', $params->get('layout', 'default'));
