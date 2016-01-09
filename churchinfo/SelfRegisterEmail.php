<?php 
require 'Include/PHPMailer-5.2.14/PHPMailerAutoload.php';

$CONFIRM_EMAIL_URL = "http://localhost/churchinfo-reminder/churchinfo/";
$CONFIRM_EMAIL_SUBJECT = "Unitarian Universalist Church of Nashua Registration Confirmation";
$RESET_EMAIL_SUBJECT = "Unitarian Universalist Church of Nashua Password Reset";

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

function SendConfirmPledgeMessage ($rpg_id)
{
	
}

function SendConfirmMessage ($reg_id)
{
	global $link, $CONFIRM_EMAIL_URL, $CONFIRM_EMAIL_SUBJECT;

	$query = "SELECT reg_email, reg_firstname, reg_lastname, reg_randomtag FROM register_reg WHERE reg_id=$reg_id";
	$result = $link->query($query) or die('Query failed: ' . $link->error);
	if ($result->num_rows == 0) {
		// Cannot get the email address
		return;
	} else {
		$line = $result->fetch_array(MYSQL_ASSOC);
		$to_email = $line["reg_email"];
		$to_name = $line["reg_firstname"] . " " . $line["reg_lastname"];
		$reg_randomtag = $line["reg_randomtag"];
	}
	$result->free();
		
	$validateURL = $CONFIRM_EMAIL_URL . "SelfRegisterConfirm.php?reg_randomtag=" . $reg_randomtag;
	$bodyContents = "<html>To confirm your registration click this link or copy and paste it into a brower.<br>".
	    "<a href=\"$validateURL\">$validateURL</a></html>";
	SendAMessage($reg_id, $bodyContents, $to_email, $to_name, $CONFIRM_EMAIL_SUBJECT);
}

function SendForgotMessage ($reg_id)
{
	global $link, $CONFIRM_EMAIL_URL, $RESET_EMAIL_SUBJECT;

	$query = "SELECT reg_email, reg_firstname, reg_lastname, reg_randomtag FROM register_reg WHERE reg_id=$reg_id";
	$result = $link->query($query) or die('Query failed: ' . $link->error);
	if ($result->num_rows == 0) {
		// Cannot get the email address
		return;
	} else {
		$line = $result->fetch_array(MYSQL_ASSOC);
		$to_email = $line["reg_email"];
		$to_name = $line["reg_firstname"] . " " . $line["reg_lastname"];
		$reg_randomtag = $line["reg_randomtag"];
	}
	$result->free();

	$resetURL = $CONFIRM_EMAIL_URL . "SelfRegisterReset.php?reg_randomtag=" . $reg_randomtag;
	$bodyContents = "<html>To reset your password click this link or copy and paste it into a brower.<br>".
	    "<a href=\"$resetURL\">$resetURL</a></html>";
	SendAMessage($reg_id, $bodyContents, $to_email, $to_name, $RESET_EMAIL_SUBJECT);
}
	
function SendAMessage ($reg_id, $bodyContents, $to_email, $to_name, $email_subject)
{	
	$SMTP_HOST = "";
	$SMTP_PORT = 25;
	$SMTP_USER = "";
	$SMTP_PASS = "";
	$SMTP_FROM = "";
	$SMTP_FROM_NAME = "";
	$SMTP_REPLYTO = "";
	$SMTP_REPLYTO_NAME = "";
	
	$mail = new PHPMailer;
	$mail->isSMTP();

	//Enable SMTP debugging	// 0 = off (for production use)	// 1 = client messages	// 2 = client and server messages
	$mail->SMTPDebug = 0; // 2
	$mail->Debugoutput = 'html';
	$mail->Host = $SMTP_HOST;
	$mail->Port = $SMTP_PORT;
	$mail->SMTPAuth = true;
	$mail->SMTPAutoTLS = false;
	$mail->Username = $SMTP_USER;
	$mail->Password = $SMTP_PASS;
	$mail->setFrom($SMTP_FROM, $SMTP_FROM_NAME);
	$mail->addReplyTo($SMTP_REPLYTO, $SMTP_REPLYTO_NAME);
	
	$mail->addAddress($to_email, $to_name);
	$mail->Subject = $email_subject; 
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	//Replace the plain text body with one created manually
	
	$mail->Body = $bodyContents; 
	
	$mail->isHTML(true);
	
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if (!$mail->send()) {
//	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
//	    echo "Message sent!";
	}
}

?>
