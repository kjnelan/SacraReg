<?php
require "Include/Config.php";
require "Include/Functions.php";
include "Include/vancowebservices.php";
include "Include/VancoConfig.php";

$customerid = FilterInput ($_GET["autid"], "int");
$iAutID = $customerid;

class VancoToolsXML
{
	private $userid, $password, $clientid, $enckey, $test;
	
	function __construct($setUserid, $setPassword, $setClientid, $setEncKey, $setTest) {
		$this->userid = $setUserid;
		$this->password = $setPassword;
		$this->clientid = $setClientid;
		$this->enckey = $setEncKey;
		$this->test = $setTest;

		echo "Inside VancoToolsXML __construct $this->userid password $this->password clientid $this->clientid enckey $this->enckey test $this->test <br>";
	}

	function PostXML($xmlstr)
	{
		echo "Inside VancoToolsXML PostXML userid $this->userid password $this->password clientid $this->clientid enckey $this->enckey test $this->test <br>";

		$ReqHeaderBase = "";
		if ($this->test)
			$ReqHeaderBase  .= "POST /cgi-bin/wstest2.vps HTTP/1.1\n";
		else
			$ReqHeaderBase  .= "POST /cgi-bin/ws2.vps HTTP/1.1\n"; 
//			$ReqHeaderBase  .= "POST /cgi-bin/wsnvp.vps HTTP/1.1\n"; 
		$ReqHeaderBase .= "Host: " . $_SERVER['HTTP_HOST'] . "\n"; 
		$ReqHeaderBase .= "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n"; 
		$ReqHeaderBase .= "Content-Type: application/x-www-form-urlencoded\n"; 
		
		$ReqHeader = $ReqHeaderBase . "Content-length: " . strlen($xmlstr) . "\nConnection: close\n\n"; 
		$Req = $ReqHeader . $xmlstr . "\n\n";
		
		echo "Sending request: '" . $Req;
		
		//--- Open Connection --- 
		$vancoURL = "";
		if ($this->test)
			$vancoURL = "ssl://www.vancodev.com";
		else
			$vancoURL = "ssl://www.vancoservices.com";
		
		echo "Opening connection to '$vancoURL'<br>";
		
		$socket = fsockopen($vancoURL, 443, $errno, $errstr, 15); 

		if (!$socket) {
		        echo "Failed to open socket connection to Vanco<br>"; 
		        echo "errno $errno<br>"; 
		        echo "errstr $errstr<br>"; 
		        $Result['errno']=$errno; 
		        $Result['errstr']=$errstr; 
		        return $Result; 
		} else { 

	    	// --- Send XML --- 
    		fwrite($socket, $Req);
    
		    $rets = "";
		
		    // --- Retrieve XML --- 
		    while (!feof($socket)) { 
		        $rets .= fgets($socket, 4096); 
		    }
		    fclose($socket); 
    
		    $rets = substr($rets, strpos($rets, '?'.'>') + 2); // Skip over the header and the xml tag
    
		    printf ("Got string '%s'", $rets);
    
		    $xml=simplexml_load_string($rets);
    		print_r($xml);
    		return ($xml);
		}
	}
}

$VancoObj = new VancoToolsXML ($VancoUserid, $VancoPassword, $VancoClientid, $VancoEnc_key, $VancoTest);

$datestr = date ("Y-m-d H:i:s");

$LoginXML= 
"<VancoWS>" .
	"<Auth>" .
		"<RequestType>Login</RequestType>".
        "<RequestID>111111111</RequestID>" .
        "<RequestTime>$datestr</RequestTime>" .
        "<Version>2</Version>".
    "</Auth>".
    "<Request>".
        "<RequestVars>".
            "<UserID>$VancoUserid</UserID>".
            "<Password>$VancoPassword</Password>".
        "</RequestVars>".
    "</Request>".
"</VancoWS>"; 

$LoginRespXML = $VancoObj->PostXML ($LoginXML);
$sessionid = $LoginRespXML->Response->SessionID;

printf ("Got session id %s", $sessionid);
		
$addCustomerXML = 
	"<VancoWS>".
		"<Auth>".
			"<RequestType>EFTAddEditCustomer</RequestType>".
			"<RequestID>22222222</RequestID>".
			"<RequestTime>$datestr</RequestTime>".
			"<SessionID>$sessionid</SessionID>".
			"<Version>2</Version>".
		"</Auth>".
		"<Request>".
			"<RequestVars>".
				"<ClientID>$VancoClientid</ClientID>".
				"<CustomerID>$customerid</CustomerID>".
				"<CustomerName>Wilt, Michael</CustomerName>".
	      		"<CustomerAddress1>136 Castle Hill Rd</CustomerAddress1>".
	     		"<CustomerAddress2></CustomerAddress2>".
	      		"<CustomerCity>Windham</CustomerCity>".
	      		"<CustomerState>NH</CustomerState>".
	      		"<CustomerZip>03087</CustomerZip>".
	      		"<CustomerPhone>6038868821</CustomerPhone>".
			"</RequestVars>".
		"</Request>".
	"</VancoWS>";

$addCustomerXmlResp = $VancoObj->PostXML ($addCustomerXML);

$addCCXML =
	"<VancoWS>".
		"<Auth>".
			"<RequestType>EFTAddEditPaymentMethod</RequestType>".
			"<RequestID>33333333</RequestID>".
			"<RequestTime>$datestr</RequestTime>".
			"<SessionID>$sessionid</SessionID>".
			"<Version>2</Version>".
		"</Auth>".
		"<Request>".
			"<RequestVars>".
				"<ClientID>$VancoClientid</ClientID>".
				"<CustomerID>$customerid</CustomerID>".
				"<AccountType>CC</AccountType>".
				"<AccountNumber>5490339011458443</AccountNumber>".
				"<CardBillingName>Wilt, Michael</CardBillingName>".
				"<CardExpMonth>05</CardExpMonth>".
				"<CardExpYear>2016</CardExpYear>".
				"<SameCCBillingAddrAsCust>NO</SameCCBillingAddrAsCust>".
				"<CardBillingAddr1>136 Castle Hill Rd</CardBillingAddr1>".
				"<CardBillingAddr2></CardBillingAddr2>".
				"<CardBillingCity>Windham</CardBillingCity>".
				"<CardBillingState>NH</CardBillingState>".
				"<CardBillingZip>03087</CardBillingZip>".
			"</RequestVars>".
		"</Request>".
	"</VancoWS>";

$addCreditCardXmlResp = $VancoObj->PostXML ($addCCXML);
?>
