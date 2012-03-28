<?php
echo $this->Html->script('http://static.twilio.com/libs/twiliojs/1.0/twilio.min.js',array('inline' => false));
echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js',array('inline' => false));
echo $this->Html->scriptBlock('
$(document).ready(function(){
      numInput = $(\'#tocall\');
        
Twilio.Device.setup("' . $token . '");

    $(\'#call\').click(function() {  
        params = { "tocall" : numInput.val()};
        connection = Twilio.Device.connect(params);
    });
    $(\'#hangup\').click(function() {  
        Twilio.Device.disconnectAll();
    });
 
    Twilio.Device.ready(function (device) {
        $(\'#status\').text(\'Ready to start call\');
    });
 
    Twilio.Device.incoming(function (conn) {
        if (confirm(\'Accept incoming call from \' + conn.parameters.From + \'?\')){
            connection=conn;
            conn.accept();
        }
    });
 
    Twilio.Device.offline(function (device) {
        $(\'#status\').text(\'Offline\');
    });
 
    Twilio.Device.error(function (error) {
        $(\'#status\').text(error);
    });
 
    Twilio.Device.connect(function (conn) {
        $(\'#status\').text("Successfully established call");
        toggleCallStatus();
    });
 
    Twilio.Device.disconnect(function (conn) {
        $(\'#status\').text("Call ended");
        toggleCallStatus();
    });
     
    function toggleCallStatus(){
        $(\'#call\').toggle();
        $(\'#hangup\').toggle();
        //$(\'#dialpad\').toggle();
    }
 
    $.each([\'0\',\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'8\',\'9\',\'star\',\'pound\'], function(index, value) { 
        $(\'#button\' + value).click(function(){ 
                if (value==\'star\') {
        			numInput.val(numInput.val() + \'*\')
                } else if (value==\'pound\') {
        			numInput.val(numInput.val() + \'#\')
                } else {
        			numInput.val(numInput.val() + value)
        		}
        if(connection) {
                if (value==\'star\') {
        			numInput.val(numInput.val() + \'*\')
        			connection.sendDigits(\'*\')
                } else if (value==\'pound\') {
        			numInput.val(numInput.val() + \'#\')
        			connection.sendDigits(\'#\')
                } else {
        			numInput.val(numInput.val() + value)
        			connection.sendDigits(value)        
        		}
            } else { 
        			}
                return false;
        
            });
    });
});
        
        
',array('inline' => false));
?>
<div class="softPhone" style="width:200px">
Number to call: <input type="text" id="tocall" value=""/>
<input type="button" id="call" value="Start Call"/>
<input type="button" id="hangup" value="Hangup Call" style="display:none;"/>
<div id="status">
    Offline
</div>
<div id="dialpad">
    <table>
    <tr>
    <td><input type="button" value="1" id="button1"></td>
    <td><input type="button" value="2" id="button2"></td>
    <td><input type="button" value="3" id="button3"></td>
    </tr>
    <tr>
    <td><input type="button" value="4" id="button4"></td>
    <td><input type="button" value="5" id="button5"></td>
    <td><input type="button" value="6" id="button6"></td>
    </tr>
    <tr>
    <td><input type="button" value="7" id="button7"></td>
    <td><input type="button" value="8" id="button8"></td>
    <td><input type="button" value="9" id="button9"></td>
    </tr>
    <tr>
    <td><input type="button" value="*" id="buttonstar"></td>
    <td><input type="button" value="0" id="button0"></td>
    <td><input type="button" value="#" id="buttonpound"></td>
    </tr>
    </table>
</div>
</div>