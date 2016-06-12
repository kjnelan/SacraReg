<?php

include "Include/Config.php";
require "Include/Functions.php";
require "Include/VancoConfig.php";


function sendVancoXML ($xmlstr)
{
	//--- Open Connection ---
	$socket = fsockopen("ssl://myvanco.vancopayments.com",
	                 443, $errno, $errstr, 15);
//print ("Connected to ssl://myvanco.vancopayments.com on port 443, got socket $socket\n");

	if (!$socket) {
	
	    echo 'Fail<br>';
	    $Result['errno']=$errno;
	    $Result['errstr']=$errstr;
	    
	    printf ("Failed to open socket connection to Vanco, Error number $errno, Error description $errstr<br>");
	    
	    exit ();
	}
		
    //--- Create Header ---
    $ReqHeader  = "POST /cgi-bin/ws2.vps HTTP/1.1\r\n";
    $ReqHeader .= "Host: " . "myvanco.vancopayments.com" . "\r\n";
    $ReqHeader .= "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
    $ReqHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $ReqHeader .= "Connection: close\r\n";
    $ReqHeader .= "Content-length: " . strlen($xmlstr) . "\r\n";
    $ReqHeader .= $xmlstr . "\r\n\r\n";

//print ("---------------- Sending this mesaage -------------\n");
//print ($ReqHeader);
//print ("---------------- End of the message ---------------\n");

    // --- Send XML ---
    fwrite($socket, $ReqHeader);

//print ("After calling fwrite to send the XML\n");
//sleep (1);
    // --- Retrieve XML ---
    while (!feof($socket)) {
        $_return .= fgets($socket, 4096);
    }

    fclose($socket);
    
//print ("---------------- Got this response -------------\n");
//    print ($_return);
//print ("---------------- End of response ---------------\n");
    
	$pos = strpos($_return, "<?xml");
	$xmlPart = substr ($_return, $pos, strlen ($_return)-$pos);

//print ("---------------- Extracted XML -------------\n");
//print ($xmlPart);
//print ("---------------- End of response ---------------\n");
	
	$xml=simplexml_load_string($xmlPart);
	return $xml;
}

$requestTime = date ("Y-m-d h:m:s");
//2008-11-24 12:27:52
$ReqBody="
<VancoWS>
	<Auth>
		<RequestType>Login</RequestType>
		<RequestID>123456</RequestID>
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
			<ToDate>2016-04-24</ToDate>
		</RequestVars>
	</Request>
</VancoWS>";

$transactionsxml = sendVancoXML ($ReqBody);

printf ("<table>");
printf ("<tr>");
printf ("<th>TransactionRef</th>");
printf ("<th>AccountType</th>");
printf ("<th>CCAuthDesc</th>");
printf ("<th>CustomerID</th>");
printf ("<th>Family</th>");
printf ("<th>plg_Date</th>");
printf ("<th>plg_aut_Cleared</th>");
printf ("<th>ProcessDate</th>");
printf ("<th>DepositDate</th>");
printf ("<th>SettlementDate</th>");
printf ("<th>Amount</th>");
printf ("<th>TransactionFee</th>");
printf ("</tr>");

$cnt = (int) $transactionsxml->Response->TransactionCount;
$translist = $transactionsxml->Response->Transactions->children();
foreach ($translist as $onetrans) {
	$sSQL = "SELECT * FROM pledge_plg JOIN family_fam ON plg_FamID=fam_id WHERE DATE_ADD(plg_date, INTERVAL 2 DAY)>=\"".$onetrans->ProcessDate."\" AND plg_date<=\"".$onetrans->ProcessDate."\" AND plg_PledgeOrPayment=\"Payment\" AND plg_aut_Cleared=\"1\" AND plg_aut_ID=". $onetrans->CustomerID;
	$rsDBInfo = RunQuery($sSQL);
	extract(mysql_fetch_array($rsDBInfo));

	printf ("<tr>");
	printf ("<td>%s</td>", (string) $onetrans->TransactionRef);
	printf ("<td>%s</td>", (string) $onetrans->AccountType);
	printf ("<td>%s</td>", (string) $onetrans->CCAuthDesc);
	printf ("<td>%s</td>", (string) $onetrans->CustomerID);
	printf ("<td>%s</td>", (string) $fam_Name);
	printf ("<td>%s</td>", (string) $plg_date);
	printf ("<td>%s</td>", (string) $plg_aut_Cleared);
	printf ("<td>%s</td>", (string) $onetrans->ProcessDate);
	printf ("<td>%s</td>", (string) $onetrans->DepositDate);
	printf ("<td>%s</td>", (string) $onetrans->SettlementDate);
	printf ("<td>%s</td>", (string) $onetrans->Amount);
	printf ("<td>%s</td>", (string) $onetrans->TransactionFee);
	printf ("</tr>");
}
printf ("</table>");

?>

