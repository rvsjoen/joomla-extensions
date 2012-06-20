<?php defined('_JEXEC') or die ;
/**
 * @package		RVS
 * @subpackage	Debug
 * @author		Rune V. Sjøen <rvsjoen@gmail.com>
 * @copyright	Copyright (C) 2012 {@link http://www.twilight-zone.com}
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 */

class plgSystemRVS_Debug extends JPlugin
{
    protected $result = null;

    public function __construct(&$subject, $config)
    {
        parent::__construct(&$subject, $config);
        $this->loadLanguage();
    }

    public function onBeforeRender()
    {
        if (!JDEBUG) {
            return;
        }
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root(true).'/media/plg_system_rvs_debug/css/styles.css');
    }

    public function onAfterRender()
    {
        if (!JDEBUG) {
            return;
        }

        $doc = JFactory::getDocument();
        $html = $doc->render();
        $docurl = curl_init();
        curl_setopt($docurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($docurl, CURLOPT_URL, 'http://validator.w3.org/check');
        curl_setopt($docurl, CURLOPT_VERBOSE, false);
        curl_setopt($docurl, CURLOPT_HEADER, false);
        curl_setopt($docurl, CURLOPT_POST, true);
        curl_setopt($docurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($docurl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($docurl, CURLOPT_POSTFIELDS, array("fragment" => $html, "output" => "soap12"));
        $webpage = curl_exec($docurl);
        if($webpage){
            $this->result = $this->parseSOAP12Response($webpage);
        }
        curl_close($docurl);
    }

    public function __destruct()
    {
        if (!JDEBUG || $this->result == null) {
            return;
        }

        $html = array();
        $html[] = '<div id="rvs-debug">';
        if (!isset($this->result->valid)) {
            $html[] = '<h1>'.JText::_('PLG_SYSTEM_RVS_DEBUG_VALIDATION_FAILED').'</h1>';
        } else {
            $status = $this->result->valid ? 'valid' : 'invalid';
            $html[] = JText::sprintf('PLG_SYSTEM_RVS_DEBUG_VALIDATION_RESULTS', $status, 
                JText::_($this->result->valid ? 'PLG_SYSTEM_RVS_DEBUG_VALID' : 'PLG_SYSTEM_RVS_DEBUG_INVALID'));
            $html[] = '<ul>';

            foreach ((array) $this->result->errors as $item) {
                $html[] = '<li class="validate-error">';
                $html[] = JText::sprintf('PLG_SYSTEM_RVS_MESSAGE_HEADER', 
                    $item->line, $item->col, $item->msg, $item->id);
                $html[] = $item->explanation;
                $html[] = '<pre>'.$item->source.'</pre>';
                $html[] = '</li>';
            }

            foreach ((array)$this->result->warnings as $item) {
                $html[] = '<li class="validate-warning">'.$item->id.' - '.$item->msg.'</li>';
            }

            $html[] = '</ul>';

        }
        $html[] = '</div>';
        echo implode("\n", $html);
    }

    private function parseSOAP12Message($message)
    {
        $obj = new stdClass;
        $obj->id = $message->getElementsByTagName('messageid')->item(0)->nodeValue;
        $obj->msg = $message->getElementsByTagName('message')->item(0)->nodeValue;
        $obj->explanation = trim($message->getElementsByTagName('explanation')->item(0)->nodeValue);
        $obj->line = $message->getElementsByTagName('line')->item(0)->nodeValue;
        $obj->col = $message->getElementsByTagName('col')->item(0)->nodeValue;
        $obj->source = trim($message->getElementsByTagName('source')->item(0)->nodeValue);
        return $obj;
    }

    private function parseSOAP12Response($xml)
    {
        $response = new stdClass;
        $response->warnings = array();
        $response->errors = array();
        $doc = new DOMDocument();
        if ($doc->loadXML($xml)) {
            $warnings = $doc->getElementsByTagName('warning');
            $errors = $doc->getElementsByTagName('error');
            $valid = $doc->getElementsByTagName('validity');
            $response->valid = ($valid->length && $valid->item(0)->nodeValue == 'true');
            // Grab the warnings
            foreach ($warnings as $message) {
                if (is_object($message)) {
                    $response->warnings[] = $this->parseSOAP12Message($message);
                }
            }
            // Grab the errors
            foreach ($errors as $message) {
                if (is_object($message)) {
                    $response->errors[] = $this->parseSOAP12Message($message);
                }
            }
            return $response;
        } else {
            return null;
        }
    }

}
