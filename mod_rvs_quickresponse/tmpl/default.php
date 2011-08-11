<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	QuickResponse
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl.html
*/

$size = (int) $params->get('size', 128);

?>

<div class="mod_rvs_quickresponse" style="text-align: center;">
	<img src="<?php echo $qrlink; ?>" alt="<?php echo $qrtitle; ?>" height="<?php echo $size; ?>" width="<?php echo $size; ?>" />
</div>