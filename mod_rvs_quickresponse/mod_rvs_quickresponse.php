<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	QuickResponse
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-3.0.html
*/

if(!class_exists('qrstr')){
	require_once(dirname(__FILE__).'/include/phpqrcode/qrlib.php');
}

$url = JURI::getInstance()->__toString();
$qrhash = md5($url);
$qrfile = JPATH_SITE.'/tmp/qr_'.$qrhash.'.png';

var_dump(JURI::getInstance()->__toString());
if(!file_exists($qrfile)){
	QRcode::png($url, $qrfile, 'L', 4, 2);
}

$qrlink = JURI::base().'/tmp/qr_'.$qrhash.'.png';

$doc = JFactory::getDocument();
$qrtitle = $doc->getTitle();

require JModuleHelper::getLayoutPath('mod_rvs_quickresponse', $params->get('layout', 'default'));