<?php
/*
Name:			EsendexSendService.php
Description:	Esendex SendService Web Service PHP Wrapper
Documentation: 	http://www.esendex.com/isSecure/messenger/formpost/SendServiceNoHeader.asmx
				http://www.esendex.com/isSecure/messenger/formpost/QueryStatus.aspx

Copyright (c) 2007 Esendex®

If you have any questions or comments, please contact:

support@esendex.com
http://www.esendex.com/support
*/

class Essendex_Sendservice extends Essendex_Formpostutilities
{
	var $username;
	var $password;
	var $accountReference;
	
	/**
	 * The amount of time in hours until the message expires if it cannot be delivered
	 * 0 = never expire
	 */
	var $validityPeriod = 0;
	
	/**
	 * The type of message
	 * Text, SmartMessage, Binary or Unicode
	 */
	var $type = 'Text';
	
	/**
	 * Who did the message come from?
	 * 11 alphanumeric chars
	 */
	var $originator = 'LeedsHackApp';
	

	function __construct( $username, $password, $accountReference, $isSecure = false, $certificate = "" )
	{
		parent::__construct( $isSecure, $certificate );
		
		$this->username = $username;
		$this->password = $password;
		$this->accountReference = $accountReference;

		if ( $isSecure )
		{
			define( "SEND_SMS_URL", "https://www.esendex.com/secure/messenger/formpost/SendSMS.aspx" );
			define( "SMS_STATUS_URL", "https://www.esendex.com/secure/messenger/formpost/QueryStatus.aspx" );
		}
		
		else
		{
			define( "SEND_SMS_URL", "http://www.esendex.com/secure/messenger/formpost/SendSMS.aspx" );
			define( "SMS_STATUS_URL", "http://www.esendex.com/secure/messenger/formpost/QueryStatus.aspx" );
		}
	}

	function SendMessage( $recipient, $body )
	{

		$parameters['username'] = $this->username;
		$parameters['password'] = $this->password;
		$parameters['account'] = $this->accountReference;
		$parameters['recipient'] = $recipient;
		$parameters['body'] = $body;
		$parameters['type'] = $this->type;
		
		$parameters['plainText'] = "1";

		return $this->FormPost( $parameters, SEND_SMS_URL );
	}

	function SendMessageFull( $recipient, $body )
	{
		$parameters['username'] = $this->username;
		$parameters['password'] = $this->password;
		$parameters['account'] = $this->accountReference;
		$parameters['originator'] = $this->originator;
		$parameters['recipient'] = $recipient;
		$parameters['body'] = $body;
		$parameters['type'] = $this->type;
		$parameters['validityPeriod'] = $this->validityPeriod;
		
		$parameters['plainText'] = "1";

		return $this->FormPost( $parameters, SEND_SMS_URL );
	}

	function GetMessageStatus($messageID)
	{
		$parameters['username'] = $this->username;
		$parameters['password'] = $this->password;
		$parameters['account'] = $this->accountReference;
		$parameters['messageID'] = $messageID;
		
		$parameters['plainText'] = "1";
		
		return $this->FormPost( $parameters, SMS_STATUS_URL );
	}
}
?>
