<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	jQueryLoader
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-2.0.html
*/

jimport('joomla.filesystem.file');

class plgSystemRvs_jQueryLoader extends JPlugin
{
	private $_version = null;
	
	public function __construct(&$subject, $config = array()){
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	public function onBeforeRender(){
		
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		$this->_version = $this->params->get('version');
		
		if(!(($app->isSite() && $this->params->get('active_site')) || ($app->isAdmin() && $this->params->get('active_admin'))))
			return true;

		$this->updateLibrary($this->_version);
		$doc->addScript(JURI::root(true).'/media/plg_'.$this->_type.'_'.$this->_name.'/js/'.$this->getFilename());
		$doc->addScriptDeclaration('jQuery.noConflict()');
		
		return true;
	}
	
	private function updateLibrary($version){
		
		$config 	= JFactory::getConfig();
		$filename 	= $this->getFilename($version);
		$url 		= $this->getUrlFromFilename($filename);
		
		$target = JPATH_ROOT.DS.'media'.DS.'plg_'.$this->_type.'_'.$this->_name.DS.'js'.DS.$filename;
		if(JFile::exists($target))
			return true;
		
		$php_errormsg = 'Error Unknown';
		$track_errors = ini_get('track_errors');
		ini_set('track_errors', true);
		$inputHandle = @ fopen($url, "r");
		$error = strstr($php_errormsg,'failed to open stream:');
		if (!$inputHandle) {
			JError::raiseWarning(0, JText::sprintf('PLG_SYSTEM_RVS_JQUERYLOADER_CONNECT_ERROR', $error));
			return false;
		}

		$contents = null;
		while (!feof($inputHandle)){
			$contents .= fread($inputHandle, 4096);
			if (!$contents){
				JError::raiseWarning(0, JText::sprintf('PLG_SYSTEM_RVS_JQUERYLOADER_READ_ERROR', $php_errormsg));
				return false;
			}
		}

		JFile::write($target, $contents);
		fclose($inputHandle);
		ini_set('track_errors',$track_errors);
		return true;
	}
	
	private function getUrlFromFilename($filename){
		if (is_string($filename)){
			return 'http://code.jquery.com/'.$filename;
		}
		return false;
	} 

	private function getFilename(){
		if (is_string($this->_version)){
			return $this->params->get('compressed', 1) ? 'jquery-'.$this->_version.'.min.js' : 'jquery-'.$this->_version.'.js';
		}
		return false;
	} 
}
