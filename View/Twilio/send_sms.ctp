<?php 
echo $this->Form->create('SmsMessage');
echo $this->Form->input('to');
echo $this->Form->input('message',array('type' => 'textarea'));
echo $this->Form->end();