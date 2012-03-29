<?php

/** 
 * @author Paul
 * 
 */
class SmsMessage extends TwilioAppModel {

    public $useDbConfig = 'twilio';

    public $useTable = false;

    public $whitelist = array(
            'from', 
            'to', 
            'message'
    );

    protected $_schema = array(
            'from' => array(
                    'type' => 'integer', 
                    'null' => true, 
                    'key' => 'primary', 
                    'length' => 10
            ), 
            'to' => array(
                    'type' => 'integer', 
                    'null' => true, 
                    'key' => 'primary', 
                    'length' => 10
            ), 
            'message' => array(
                    'type' => 'string', 
                    'null' => true, 
                    'key' => 'primary', 
                    'length' => 160
            )
    );

}
