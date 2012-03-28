<?php
require_once ('Services/Twilio.php');

/** 
 * @author Paul
 * 
 */
class Twilio extends DataSource {

    /**
     * The Name of the DataSource
     * @var unknown_type
     */
    public $description = 'Twilio Datasource';

    /**
     * The Account Sid to authorize
     * @var unknown_type
     */
    public $accountSid;

    /**
     * The Twilio Authorization Token
     * @var unknown_type
     */
    public $authToken;

    /**
     * The Twilio client instance
     */
    public $Client;

    /**
   * 
   * @param  array $config Array of configuration information for the datasource.
  
    */
    public function __construct($config) {

        parent::__construct($config);
        $this->accountSid = $config['accountSid'];
        $this->authToken = $config['authToken'];
        $this->Client = new Services_Twilio($this->accountSid, $this->authToken);
    }

    /**
     * (non-PHPdoc)
     * Create a new Twilio Object. Supports Sub Accounts, Calls, and SMS Messages.
     * @see DataSource::create()
     */
    public function create($model, $fields = null, $values = null) {

        if ($model != 'Account') {
            $model = Inflector::tableize($model->name);
        } else {
            $model = Inflector::pluralize(
                    Inflector::underscore(strtolower($model)));
        }
        debug($model);
        if (is_array($fields) && is_array($values)) {
            $data = array_combine($fields, $values);
        } else {
            $data = $values;
        }
        if ($model == 'sms_messages') {
            return $this->Client->account->$model->create($data['from'], 
                    $data['to'], $data['message']);
        }
        return $this->Client->$model->create($data);
    
    }

    /**
     * (non-PHPdoc)
     * Find Twilio records. Currently supports Available Phone Numbers. Others haven't been tested.
     * @see DataSource::read()
     */
    public function read($Model, $queryData) {
        //debug($Model);
        $methodMap = array(
                'AvailablePhoneNumber' => 'getList', 
                ''
        );
        if ($Model->name != 'Account') {
            $model = strtolower(
                    Inflector::pluralize(Inflector::underscore($Model->name)));
        }
        if (empty($queryData['conditions'])) {
            $queryData['conditions'] = array(
                    1 => 1
            );
            $calls = $this->Client->account->calls;
            //debug($calls);
        

        }
        if ($Model->name == 'AvailablePhoneNumber') {
            $countryCode = 'US';
            $type = 'Local';
            if (! empty($queryData['iso_country'])) {
                $countryCode = $queryData['iso_country'];
                unset($queryData['iso_country']);
            }
            
            if (! empty($queryData['number_type'])) {
                $numberType = $queryData['number_type'];
                unset($queryData['number_type']);
            }
            $params = $queryData['conditions'];
            return $this->Client->account->$model->getList($countryCode, $type, 
                    $params);
        
        }
        if ($Model->name != 'Account') {
            return $this->Client->account->$model->getIterator(
                    $queryData['offset'], $queryData['limit'], 
                    $queryData['conditions']);
        } else {
            return $this->Client->$model->getIterator($queryData['offset'], 
                    $queryData['limit'], $queryData['conditions']);
        }
    
    }

    /**
     * (non-PHPdoc)
     * Used for updating accounts. 
     * @see DataSource::update()
     */
    public function update($model, $fields = null, $values = null) {

        if ($model != 'Account') {
            $model = 'account->' . $model;
        } else {
            $model = strtolower($model);
        }
        
        $data = array_combine($fields, $values);
        return $this->Client->$model->update($data);
    
    }

    /**
     * (non-PHPdoc)
     * This hasn't been tested and many not have a direct usage.
     * @see DataSource::delete()
     */
    public function delete($model, $conditions) {

        if ($model != 'Account') {
            $model = 'account->' . Inflector::underscore(strtolower($model));
        } else {
            $model = Inflector::pluralize(
                    Inflector::underscore(strtolower($model)));
        }
        return $this->Client->$model->delete($conditions['id']);
    
    }

}
