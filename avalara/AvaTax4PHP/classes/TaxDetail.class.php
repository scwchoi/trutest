<?php
/**
 * TaxDetail.class.php
 */

/**
 * Holds calculated tax information by jurisdiction.
 *
 * @see ArrayOfTaxDetail
 * @see TaxLine
 * @see GetTaxResult
 * 
 * @author    Avalara
 * @copyright � 2004 - 2011 Avalara, Inc.  All rights reserved.
 * @package   Tax
 */

class TaxDetail
{

	private $JurisType;     //JurisdictionType 	
	private $Taxable;     //decimal 
	private $Rate;		//decimal 	
	private $Tax;		//decimal 
	private $JurisName; 	//string
	private $TaxName;     //string 
	private $Country;	//string
	private $Region; 	//string

	
	
	    public function __construct(
	    		 $JurisType, 
				 $Taxable,   
				 $Rate,	
				 $Tax,
				 $JurisName,   
				 $TaxName,    
				 $Country,
				 $Region)
    {
				$this->JurisType = $JurisType; 	
				$this->Taxable = $Taxable;   
				$this->Rate = $Rate;	
				$this->Tax = $Tax;
				$this->JurisName = $JurisName;   
				$this->TaxName = $TaxName;    
				$this->Country = $Country;
				$this->Region = $Region; 	
    }
    
    
    //Helper function to decode result objects from Json responses to specific objects.    
    public function parseTaxDetails($jsonString)
    {
    	$object = json_decode($jsonString);
    	$detailArray = array();

    	foreach ($object->TaxDetails as $detail)
    	{

    		$detailArray[] =  new self(
				$detail->JurisType,      	
				$detail->Tax / $detail->Rate ,   
				$detail->Rate,	
				$detail->Tax,
				$detail->JurisName,   
				$detail->TaxName,    
				$detail->Country,
				$detail->Region 	
				);
    	}
    	

    	return $detailArray;
    }

	 public function getJurisType(){ return $this->JurisType; } 
	 public function getTaxable(){ return $this->Taxable; }   
	 public function getRate(){ return $this->Rate; }	
	 public function getTax(){ return $this->Tax; }
	 public function getJurisName(){ return $this->JurisName; }   
	 public function getTaxName(){ return $this->TaxName; }    
	 public function getCountry(){ return $this->Country; }
	 public function getRegion(){ return $this->Region; }

	    
}

	
	
	

?>