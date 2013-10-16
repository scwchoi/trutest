<?php
require_once('../lib/recurly.php');
date_default_timezone_set('America/Los_Angeles');

// Required for the API
Recurly_Client::$subdomain = 'trujemi';
Recurly_Client::$apiKey = 'ace774b375534ada9baa1109a2adc921';

// Optional for Recurly.js:
Recurly_js::$privateKey = '9c0500b44fcc43ddaa47e5dd53bd56bf ';

$accounts = Recurly_AccountList::getActive();
foreach ($accounts as $account) {
print "Account: $account<br><br>";
}

?>