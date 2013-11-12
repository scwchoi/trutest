<?php

require('AvaTax4PHP/AvaTax.php');     // location of the AvaTax.PHP Classes - Required

$client = new TaxServiceRest(
	"https://development.avalara.net", // TODO: Enter service URL
	"1100027539", //TODO: Enter Username or Account Number
	"C7159028B51B81E0"); //TODO: Enter Password or License Key
	
$request = new GetTaxRequest();

//Document Level Setup  
//     R: indicates Required Element
//     O: Indicates Optional Element
//
    $dateTime = new DateTime();                                  // R: Sets dateTime format 
    $request->setCompanyCode("APITrialCompany");                    // R: Company Code from the accounts Admin Console
	$request->setCustomerCode("Main");
    $request->setDocType(DocumentType::$SalesOrder);                           // R: Typically SalesOrder,SalesInvoice, ReturnInvoice
    //$request->setDocCode("INV123125");                          // R: Invoice or document tracking number - Must be unique
    $request->setDocDate(date_format($dateTime, "Y-m-d"));  // R: Date the document is processed and Taxed - See TaxDate
    //$request->setDocCode("CUST123125");             // R: String - Customer Tracking number or Exemption Customer Code
    //$request->setCustomerUsageType("");      // O: String   Entity Usage
    //$request->setDiscount(0);                   // O: Decimal - amount of total document discount
    //$request->setPurchaseOrderNo("");    // O: String 
    //$request->setExemptionNo("");           // O: String   if not using ECMS which keys on customer code
    $request->setDetailLevel(DetailLevel::$Tax);     // R: Chose $Summary, $Document, $Line or $Tax - varying levels of results detail 
    $request->setCommit(TRUE);                    // O: Default is FALSE - Set to TRUE to commit the Document

	//$taxOverride = new TaxOverride();
	//$taxOverride->setTaxOverrideType('TaxDate');
	//$taxOverride->setReason('override testing');
	//$taxOverride->setTaxDate("2013-07-01");
	//$request->setTaxOverride($taxOverride);

	$addresses = array();
	
//Add Origin Address
    //$origin = new Address();                    // R: New instance of an address, we will use this for the origin
    //$origin->setLine1("376 W Broadway");            // O: It is not required to pass a valid street address, but it will provide for a better tax calculation.
    //$origin->setCity("New York");                // R: City
    //$origin->setRegion("NY");              		// R: State or Province
    //$origin->setPostalCode("10012");      		// R: String (Expects to be NNNNN or NNNNN-NNNN or LLN-LLN)
    //$origin->setAddressCode("01");            	// R: Allows us to use the address on our Lines
	//$addresses[] = $origin;						// 		Adds the address to our array of addresses on the request.

// Add Destination Address
    $destination = new Address();                 // R: New instance of an address, we will use this as the destination
    //$destination->setLine1("75 Washington Pl");   
	//$destination->setCity("New York");      
    //$destination->setRegion("NY");         
    $destination->setPostalCode("35022");
    $destination->setAddressCode("02");       
	$addresses[] = $destination;				// 		Adds the address to our array of addresses on the request.
	
	$request->setAddresses($addresses);
//
// Line level processing
    

    $line1 = new Line();                                // New instance of a line  
    $line1->setLineNo("01");                            // R: string - line Number of invoice - must be unique.
    $line1->setItemCode("SKU123");                   	// R: string - SKU or short name of Item
    //$line1->setDescription("Blue widget");              // O: string - Longer description of Item - R: for SST
    //$line1->setTaxCode("PC040100");               		// O: string - Tax Code associated with Item
    //$line1->setQty(3);                          		// R: decimal - The number of items 
    $line1->setAmount(500);                   			// R: decimal - the "NET" amount of items  (extended amount)
    //$line1->setDiscounted(false);                		// O: boolean - Set to true if line item is to discounted - see Discount
	//$line1->setOriginCode("01");						// R: AddressCode set on the desired origin address above
	$line1->setDestinationCode("02");					// R: AddressCode set on the desired destination address above 

    $line2 = new Line();                                // New instance of a line  
    $line2->setLineNo("02");                            // R: string - line Number of invoice - must be unique.
    $line2->setItemCode("SKUabc");                   	// R: string - SKU or short name of Item
    //$line2->setDescription("Green widget");              // O: string - Longer description of Item - R: for SST
    //$line2->setTaxCode("PC040100");               		// O: string - Tax Code associated with Item
    //$line2->setQty(1);                          		// R: decimal - The number of items 
    $line2->setAmount(100);                   			// R: decimal - the "NET" amount of items  (extended amount)
    //$line2->setDiscounted(false);                		// O: boolean - Set to true if line item is to discounted - see Discount
	//$line2->setOriginCode("01");						// R: AddressCode set on the desired origin address above
	$line2->setDestinationCode("02");					// R: AddressCode set on the desired destination address above

    $request->setLines(array($line1, $line2));             // sets line items to $lineX array    


// GetTaxRequest and Results
    
    try {
        $getTaxResult = $client->getTax($request);
        echo 'GetTax is: ' . $getTaxResult->getResultCode() . "<br>\n" . "<br>\n";

// Error Trapping

        if ($getTaxResult->getResultCode() == SeverityLevel::$Success) {

// Success - Display GetTaxResults to console
            
            //Document Level Results

			$TotalAmount = $getTaxResult->getTotalAmount();
			$TotalTax = $getTaxResult->getTotalTax();

            echo "DocCode: " . $request->getDocCode() . "<br>\n";
            echo "TotalAmount: " . $TotalAmount . "<br>\n";
            echo "TotalTax: " . $TotalTax . "<br>\n";

			$TotalDue = array("$TotalAmount", "$TotalTax");
			echo "TotalDue: " . array_sum($TotalDue) . "<br>\n" . "<br>\n";
            
            //Line Level Results (from a TaxLines array class)
            //Displayed in a readable format
            
            foreach ($getTaxResult->getTaxLines() as $ctl) {
               echo "Line: " . $ctl->getLineNo() . " | LineAmount: " . $ctl->getTaxable() . " | Tax: " . $ctl->getTax() . " | TaxRate: " . $ctl->getRate() . "<br>\n";
 
            //Line Level Results (from a TaxDetails array class)
            //Displayed in a readable format
                foreach ($ctl->getTaxDetails() as $ctd) {
					echo "JurisType: " . $ctd->getJurisType() . " | TaxName: " . $ctd->getTaxName() . " (JurisName: " . $ctd->getJurisName() . ")" . " | Rate: " . $ctd->getRate() . " | Amt: " . $ctd->getTax() . "<br>\n";
                }
                echo "<br>\n";
            }
            
// If NOT success - display error messages to console
// it is important to itterate through the entire message class        
                      
            } else {
            foreach ($getTaxResult->getMessages() as $msg) {
                echo $msg->getSummary() . "<br>\n";
            }
        }
    }
	
catch(Exception $exception)
{
	echo $msg = "Exception: " . $exception->getMessage()."<br>\n";
}
?>
