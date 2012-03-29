<?php
/**
 * This is core configuration file.
 *
 * @package       Plugin.Config
 */
/**
 * In this file you set up your database connection details.
 *
 * @package       Plugin.Config
 */
/**
 * Database configuration class for Twilio.
 */
class DATABASE_CONFIG {

    public $twilio = array(
            'datasource' => 'Twilio.Twilio', 
            'accountSid' => 'YourTwilioAccountSid', 
            'authToken' => 'YourTwilioAuthToken'
    );
}
