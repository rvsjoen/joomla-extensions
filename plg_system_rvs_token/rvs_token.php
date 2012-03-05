<?php defined('_JEXEC') or die;
/**
* @package		RVS
* @subpackage	Token
* @author		Rune V. Sjøen <rvsjoen@gmail.com>
* @copyright	Copyright (C) 2011 {@link http://www.twilight-zone.com}
* @license		http://www.gnu.org/licenses/gpl-2.0.html
*/

class plgSystemRvs_Token extends JPlugin
{
	private $token = null;
		
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	public function onAfterInitialise()
	{
		$jinput = JFactory::getApplication()->input;

		// If we're not requesting JSON data, we don't care, just return
		if($jinput->get('format') != 'json'){
			return;
		}
		
		// Then we need to get the api command and execute it if it exists, and return
		if($cmd = $jinput->get('api', null)) {
			$this->api($cmd);
		}
	}
	
	private function authenticate($username, $password = null, $valid = 86400)
	{
		// Get a database object
		$db	= JFactory::getDbo();
		
		// Look for any tokens for this user
		$db->setQuery($db->getQuery(true)
			->select('*')
			->from('#__rvs_user_tokens')
			->where('uid='.$db->q($result->id)));
		$obj = $db->loadObject();
		
		// If there is already a valid token, just return that, otherwise try to create one
		if($obj->valid > JDate::getInstance()->toUnix()) {
			return $obj->token;
		} else {
			$db->setQuery($db->getQuery(true)
				->select('id, password')
				->from('#__users')
				->where('username='.$db->q($username)));
			$result = $db->loadObject();
			
			$parts	= explode(':', $result->password);
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword($password, $salt);
			if ($crypt == $testcrypt) {
				// Authentication successful, create a token and populate the table
				$obj = new stdClass;
				$obj->uid	= $result->id;
				$obj->token = md5(rand().$salt);
				$obj->valid = JDate::getInstance()->toUnix() + $valid;
				$db->setQuery($db->getQuery(true)
					->select('uid')
					->from('#__rvs_user_tokens')
					->where('uid='.$db->q($obj->uid)));
				if ($db->loadResult()) {
					$db->updateObject('#__rvs_user_tokens', $obj, 'uid');
				} else {
					$db->insertObject('#__rvs_user_tokens', $obj, 'uid');
				}
				return $obj->token;
			}
		}
		return null;
	}
	
	private function api($api) 
	{
		if($api){
			$cmd = json_decode(base64_decode($api));
			// If the command included a token, that means we should be logged in
			// so we have to handle that, also store the token in the instance so
			// we can use it in other functions if needed
			if($cmd->token){
				$this->doLogin($cmd->token);
				$this->token = $cmd->token;
			}
			// Now execute the function if this is a call to the token api
			if(isset($cmd->function)){
				// The first element is always the function name
				$fn = array_shift($cmd->function);
				// The rest are parameters
				$ret = call_user_func_array(array($this, $fn), $cmd->function);
				if($ret){
					echo json_encode($ret);
					jexit();
				}
			}
		}
	}
	
	private function doLogin($token)
	{
		$instance = $this->_getUserByToken($token);
		// Mark the user as logged in
		$instance->set('guest', 0);
		// Register the needed session variables
		$session = JFactory::getSession();
		$session->set('user', $instance);
		return true;
	}
	
	private function getUser()
	{
		$instance 		= $this->_getUserByToken($this->token);
		$user 			= new stdClass;
		$user->id 		= $instance->id;
		$user->username = $instance->username;
		$user->name 	= $instance->name;
		$user->email 	= $instance->email;
		return $user;
	}
	
	private function _getUserByToken($token)
	{
		$instance = null;
		if($token){
			$instance = JUser::getInstance();
			// Check for a token
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query
				->select('uid')
				->from('#__rvs_user_tokens')
				->where('token = '.$db->q($token));
			$db->setQuery($query);
			$id = intval($db->loadResult());
			if($id) {
				$instance->load($id);
			}
		}
		return $instance;
	}
}