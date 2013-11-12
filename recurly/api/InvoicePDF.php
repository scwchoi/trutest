<?php
	header("Content-type:application/pdf");
	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, "https://ace774b375534ada9baa1109a2adc921@trujemi.recurly.com/v2/invoices/1019");
	$result = curl_exec ($curl);
	curl_close ($curl);
?>