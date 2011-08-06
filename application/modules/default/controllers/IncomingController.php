<?php
class IncomingController extends Zend_Controller_Action
{
	protected $essconf;
	
	public function init()
	{	
		$this->essconf = Zend_Registry::get('config')->essendex;
		
		$this->_helper->layout->disableLayout();
		
		$this->_helper->viewRenderer->setNoRender();
		
		if (!$this->_request->isPost()) {
			throw new Zend_Http_Exception('Bad Request', 400);
		}
	}
	
	public function indexAction()
	{
		$data = array();
		
		$reader = new XMLReader();
		$reader->XML($this->_request->getRawBody());
		
		while($reader->read()) {
			if ($reader->nodeType == XMLREADER::ELEMENT) {
				$nodeIndex = $reader->localName;
				$reader->read();
				$data[$nodeIndex] = $reader->value;
			}
		}
		
		$messageData = self::parseMessage($data['MessageText']);
		
		$wallet = Wallet::open($data['From'], $messageData['passphrase'][0]);
		
		$tag = trim(strtolower($messageData['tag'][0]));
		
		if (isset($wallet[$tag])) {
			
			$message = 'Your SMSafe information for tag "' .
					   $tag .'":  ' . $wallet[$tag];
		} else {
			$message = 'Sorry, no data found for tag "'.
					   $tag . '" on SMSafe';
		}
		
		$this->sendResponse($data['From'], $message);
	}
	
	private static function parseMessage($message)
	{
		$parsed = array();
		
		//@todo make this more selective and work. As it is only a temp solution
		preg_match_all('!(?P<passphrase>.+)\s(?P<tag>\w+)!', $message, $parsed);
		return $parsed;
	}
	
	private function sendResponse($number, $response)
	{
		$sendService = new Essendex_Sendservice(
			$this->essconf->username,
			$this->essconf->password,
			$this->essconf->accountref
		);
		
		$result = $sendService->SendMessageFull(
			$number,
			$response
		);
		
		if (!isset($result['Result']) || $result['Result'] != "OK") {
			throw new MessageNotSent('Message failed to send with response for ID: ' . $number);	
		}
	}
}