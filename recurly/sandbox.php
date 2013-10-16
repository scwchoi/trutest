<?php
require_once('lib/recurly.php');
Recurly_js::$privateKey = '9c0500b44fcc43ddaa47e5dd53bd56bf';
 
$signature = Recurly_js::sign(array(
  'account'=>array(
    'account_code'=>'my-account-code-b'
  ),
  'subscription'=>array(
    'plan_code' => 'plan-a'
  ))
); 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="examples.css" type="text/css" />
	<link rel="stylesheet" href="themes/default/recurly.css" type="text/css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="build/recurly.js"></script>
    <script>
    Recurly.config({
      subdomain: 'trujemi',
      currency: 'EUR'
    });

    Recurly.buildSubscriptionForm({
      target: '#recurly-subscribe',
      signature: '<?php echo $signature;?>',
      successURL: 'congrats.php',
      planCode: 'plan-a',
      distinguishContactFromBillingInfo: true,
      collectCompany: true,
      collectContact: false,
      acceptPaypal: true,
      acceptedCards: ['mastercard',
                      'discover',
                      'american_express', 
                      'visa'],
      acceptPaypal: true,
      account: {
        firstName: 'Joe',
        lastName: 'User',
        email: 'test@example.net',
        phone: '555-555-5555',
        companyName: 'Acme'
      },
      billingInfo: {
        firstName: 'John',
        lastName: 'Doe',
        address1: '1 Main Street',
        city: 'San Francisco',
        zip: '94107',
        state: 'CA',
        country: 'US',
        cardNumber: '4111-1111-1111-1111',
        CVV: '123'
      }
    });
    </script>
    <script type="text/javascript">
      window.heap=window.heap||[];heap.load=function(a){window._heapid=a;var b=document.createElement("script");b.type="text/javascript",b.async=!0,b.src=("https:"===document.location.protocol?"https:":"http:")+"//cdn.heapanalytics.com/js/heap.js";var c=document.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c);var d=function(a){return function(){heap.push([a].concat(Array.prototype.slice.call(arguments,0)))}},e=["identify","track"];for(var f=0;f<e.length;f++)heap[e[f]]=d(e[f])};
      heap.load("3745193675");
    </script>	
  </head>
  
  <body>
    <h1>Checkout</h1>
    <div id="recurly-subscribe"></div>
  </body>
</html>