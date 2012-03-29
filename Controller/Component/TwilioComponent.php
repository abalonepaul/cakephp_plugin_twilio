<?php
/**
 * Twilio Component
 *
 * Component for communicating with Twilio
 *
 * PHP versions 5
 *
 * Protelligence(tm) : Protelligence (http://www.protelligence.com
 * 
 * @package       Plugin.Twilio.Controller.Component
 */

require_once ('Services/Twilio.php');
require_once ('Services/Twilio/Capability.php');

/** 
 * Twilio Component Class
 * @author Paul Marshall
 * 
 */
class TwilioComponent extends Component {    
    
    /**
     * The User's Twilio Account sid.
     * @var unknown_type
     */
    public $accountSid; 
    
    /**
     * The User's Twilio Account Authorization Token.
     * @var unknown_type
     */
    public $authToken;
    
    /**
	 * The Twilio Client instance
     */
    public $Client;

    /**
   * 
   * @param  ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
 
   * @param  array $settings Array of configuration settings.
  
    */
    public function __construct(ComponentCollection $collection, $settings) {

     	parent::__construct($collection, $settings);
        $this->getClient();
    }
    
    /**
     * Gets the Twilio Client and sets the local instance
     * @param unknown_type $accountSid
     * @param unknown_type $authToken
     * @throws InvalidArgumentException
     */
    public function getClient($accountSid = null, $authToken = null) {
        if (empty($this->accountSid) || empty($this->authToken)) {
	        if (($accountSid == null) || ($accountSid == null)) {
	            throw new InvalidArgumentException(__('The username and password are required.'));
	        }
	        $this->accountSid = $accountSid;
	        $this->authToken = $authToken;
        }
        $this->Client = new Services_Twilio($this->accountSid,$this->authToken);
        //debug($this->Client->account->calls);
        //debug($this->Client->account->sms_messages);
    }
    
    /**
     * Send an SMS message. Optionally takes the accountSid and authToken to create the Client if it does not exist.
     * @param unknown_type $fromNumber
     * @param unknown_type $toNumber
     * @param unknown_type $message
     * @param unknown_type $accountSid (Optional)
     * @param unknown_type $authToken (Optional)
     */
    public function sendSms($fromNumber = null, $toNumber = null, $message = null,$accountSid = null, $authToken = null) {
        if (!$this->Client) {
            $this->getClient($accountSid = null, $authToken = null);
        }
        
        $message = $this->Client->account->sms_messages->create($fromNumber,$toNumber,$message);
        
        return $message->sid;
    }
    
    /**
     * Make a call. Optionally takes the accountSid and authToken to create the Client if it does not exist.
     * @param unknown_type $fromNumber
     * @param unknown_type $toNumber
     * @param unknown_type $twimlUrl The URL to load that contains TwiML to consume
     * @param unknown_type $accountSid
     * @param unknown_type $authToken
     */
    public function makeCall($fromNumber = null, $toNumber = null, $twimlUrl = null,$accountSid = null, $authToken = null) {
        if (!$this->Client) {
            $this->getClient($accountSid = null, $authToken = null);
        }
    
        $call = $this->Client->account->calls->create($fromNumber,$toNumber,$twimlUrl);
    
        return $call->sid;
    }
    
    /**
     * Get calls for the current or given account. Optionally take the $start and $limit parameters for paging.
     * Optionally takes the $conditions parameter to support filtering the results. If these parameters are empty,
     * the method returns all of the calls for the given account.
     * @param unknown_type $start
     * @param unknown_type $limit
     * @param unknown_type $conditions
     * @param unknown_type $accountSid
     * @param unknown_type $authToken
     */
    public function getCalls($start = 0, $limit = 10, $conditions = array(),$accountSid = null, $authToken = null) {
        $calls = false;
        if (!$this->Client) {
            $this->getClient($accountSid = null, $authToken = null);
        }
        //Check to see if we are filtering the results
        if (($limit > 0) || (!empty($conditions))) {
            $calls = $this->Client->account->calls->getIterator($start,$limit, $conditions); 
        } else {
            $calls = $this->Client->account->calls;
        }
        
        return $calls;
    }
    
    /**
     * Get the call record for a given callId.
     * @param unknown_type $callId
     * @throws InvalidArgumentException
     */
    public function getCall($callId = null) {
        if ($callId == null) {
            throw new InvalidArgumentException('Invalid call id');
        }
        return $this->Client->account->calls->get($callId);
    }
    
    /**
     * Get a Capability Token for Client communications
     * @param unknown_type $outgoing The Application Sid
     * @param unknown_type $incoming The name of the client
     */
    public function getCapabilityToken($outgoing = null, $incoming) {
        $Capability = new Services_Twilio_Capability($this->accountSid, $this->authToken);
        $Capability->allowClientOutgoing($outgoing);
		$Capability->allowClientIncoming($incoming);
        return $Capability->generateToken();
    }
    
    public function getAvailableNumbers($conditions) {
        
        $this->AvailablePhoneNumber = $this->_getModel('AvailablePhoneNumber');
        $this->AvailablePhoneNumber->find('all',$conditions);
        
    }
    
    private function _setDatasource() {
        ConnectionManager::create('twilio', array(
	        'datasource' => 'Twilio.Twilio',
	        'accountSid' => $this->accountSid,
	        'authToken' => $this->authToken,
	        'database' => 'twilio'
        ));
    }

    private function _getModel($model) {
        $this->_setDatasource();
        return new Model(array('name' => 'AvailablePhoneNumber','ds' =>'twilio'));
    }
    
}

