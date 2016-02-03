<?php

include "Include/Config.php";
require "Include/UtilityFunctions.php";
require "Include/VancoConfig.php";


function sendVancoXML ($xmlstr)
{
	//--- Open Connection ---
	$socket = fsockopen("ssl://myvanco.vancopayments.com",
	                 443, $errno, $errstr, 15);
	
	if (!$socket) {
	
	    echo 'Fail<br>';
	    $Result['errno']=$errno;
	    $Result['errstr']=$errstr;
	    
	    printf ("Failed to open socket connection to Vanco, Error number $errno, Error description $errstr<br>");
	    
	    exit ();
	}
		
    //--- Create Header ---
    $ReqHeader  = "POST /cgi-bin/ws2.vps HTTP/1.1\n";
    $ReqHeader .= "Host: " . "myvanco.vancopayments.com" . "\n";
    $ReqHeader .= "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    $ReqHeader .= "Content-Type: application/x-www-form-urlencoded\n";
    $ReqHeader .= "Content-length: " . strlen($xmlstr) . "\n";
    $ReqHeader .= "Connection: close\n\n";
    $ReqHeader .= $xmlstr . "\n\n";

    // --- Send XML ---
    fwrite($socket, $ReqHeader);

    // --- Retrieve XML ---
    while (!feof($socket)) {
        $_return .= fgets($socket, 4096);
    }

    fclose($socket);

	$pos = strpos($_return, "<?xml");
	$xmlPart = substr ($_return, $pos, strlen ($_return)-$pos);
    
	$xml=simplexml_load_string($xmlPart) or die("Error: Cannot create object");
	return $xml;
}

$requestTime = date ("Y-m-d h:m:s");
//2008-11-24 12:27:52
$ReqBody=
"<VancoWS>
   <Auth>
     <RequestType>Login</RequestType>
     <RequestID>Test</RequestID>
     <RequestTime>$requestTime</RequestTime>
     <Version>2</Version>
   </Auth>
   <Request>
     <RequestVars>
       <UserID>$VancoUserid</UserID>
       <Password>$VancoPassword</Password>
     </RequestVars>
   </Request>
 </VancoWS>";

$regxml = sendVancoXML ($ReqBody);
$sessionID = (string) $regxml->Response->SessionID;
$requestTime = date ("Y-m-d h:m:s");

$ReqBody="
<VancoWS>
	<Auth>
		<RequestType>EFTTransactionFundHistory</RequestType>
		<RequestID>12345</RequestID>
		<RequestTime>$requestTime</RequestTime>
		<SessionID>$sessionID</SessionID>
		<Version>2</Version>
	</Auth>
	<Request>
		<RequestVars>
			<ClientID>$VancoClientid</ClientID>
			<FromDate>2016-01-01</FromDate>
			<ToDate>2016-01-31</ToDate>
		</RequestVars>
	</Request>
</VancoWS>";

$transactionsxml = sendVancoXML ($ReqBody);

printf ("<table>");
printf ("<tr>");
printf ("<th>TransactionRef</th>");
printf ("<th>ProcessDate</th>");
printf ("<th>DepositDate</th>");
printf ("<th>Amount</th>");
printf ("<th>TransactionFee</th>");
printf ("</tr>");

$cnt = (int) $transactionsxml->Response->TransactionCount;
$translist = $transactionsxml->Response->Transactions->children();
foreach ($translist as $onetrans) {
	printf ("<tr>");
	printf ("<td>%s</td>", (string) $onetrans->TransactionRef);
	printf ("<td>%s</td>", (string) $onetrans->ProcessDate);
	printf ("<td>%s</td>", (string) $onetrans->DepositDate);
	printf ("<td>%s</td>", (string) $onetrans->Amount);
	printf ("<td>%s</td>", (string) $onetrans->TransactionFee);
	printf ("</tr>");
}
printf ("</table>");

?>

