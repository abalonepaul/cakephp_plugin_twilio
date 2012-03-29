<?php
/**
 * Twilio Controller
 *
 * Component for communicating with Twilio
 *
 * PHP versions 5
 *
 * Protelligence(tm) : Protelligence (http://www.protelligence.com
 * 
 * @package       Plugin.Twilio.Controller.Component
 */
App::uses('Model', 'Model');
App::uses('ConnectionManager', 'Model');

/** 
 * @author Paul
 * 
 */
class TwilioController extends TwilioAppController {

    public $components = array(
            'Twilio.Twilio'
    );

    public $uses = array(
            'Twilio.Account', 
            'Twilio.Call', 
            'Twilio.SmsMessage'
    );

    /**
     * (non-PHPdoc)
     * @see Controller::beforeFilter()
     */
    public function beforeFilter() {
        //This can be hard coded or loaded by user
        //$this->Twilio->accountSid = 'YourAccountSid';
        //$this->Twilio->authToken = 'YourAccountSid';
        $this->Twilio->accountSid = $this->Auth->user('account_sid');
        $this->Twilio->authToken = $this->Auth->user('auth_token');
    }

    /**
     * Twilio Application callback for handling incoming voice calls
     */
    public function incoming_call() {

        $this->layout = 'xml';
    
    }

    /**
     * Twilio Application callback for handling incoming sms messages.
     */
    public function sms() {

        $this->layout = 'xml';
    
    }

    /**
     * Load the SMS Message form and send the message. Takes the number the message should be sent from, the number the message should
     * be sent to, and the message that should be sent.
     */
    public function send_sms() {

        if ($this->request->is('post')) {
            if (empty($this->request->data['SmsMessage']['from'])) {
                $this->request->data['SmsMessage']['from'] = $this->Auth->user(
                        'caller_id_number');
            }
            //Send using Component
            //if ($this->Twilio->sendSMS($this->request->data['SmsMessage']['from'],$this->request->data['SmsMessage']['to'],$this->request->data['SmsMessage']['message'])) {
            //Send using Datasource
            if ($this->SmsMessage->save($this->request->data)) {
                $message = __('Your SMS Message has been sent.');
            } else {
                $message = __('There was an error sending your SMS Message.');
            }
            $this->layout = 'ajax';
            $this->set('value', $message);
            $this->render('/Elements/ajax_return');
        
        }
        
        $this->autoRender = false;
    }

    /**
     * Twilio Application callback to executue when a call to a number started.
     * @param unknown_type $number
     */
    public function dial_number($number) {

        $number = htmlspecialchars($number);
        $this->layout = 'xml';
        $this->set(compact('number'));
    }

    /**
     * Twilio Application callback to execute when a call to a Twilio client is started.
     * @param unknown_type $client
     */
    public function dial_client($client) {

        $client = htmlspecialchars($client);
        $this->layout = 'xml';
        $this->set(compact('client'));
    
    }

    /**
     * Update a user account
     */
    public function updateAccount() {

    }

    /**
     * Record a voice message.
     */
    public function recordMessage() {

    }

    /**
     * Render the Twilio Soft Phone Client.
     */
    public function soft_phone() {

        $token = $this->Twilio->getCapabilityToken();
        $this->set(compact('token'));
    }

}
