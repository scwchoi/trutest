<?php
$curl = curl_init();
curl_setopt ($curl, CURLOPT_URL, "https://ace774b375534ada9baa1109a2adc921@trujemi.recurly.com/v2/accounts/scott-hot/adjustments");
$result = curl_exec ($curl);
curl_close ($curl);
?>