<?php
class IncomingController extends Zend_Controller_Action
{
	protected $account;
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
		
		//@todo add model account call when supplied.
		$this->account = true;	
		if($this->account) {
			$messagetData = self::parseMessage($data['MessageText']);
			
			//@todo fetch tag data
			//@todo checks if tag exists, if not send list of their tags?
			//@todo send response.
		} else {
			//Account not recognised.
			//@todo speak to team about how many failure messages we send?
		}
	}
	
	private static function parseMessage($message)
	{
		$parsed = array();
		
		//@todo make this more selective and work. As it is only a temp solution
		preg_match_all('(?P<passphrase>.+)\s(?P<tag>\w+)', $message, $parsed);
		
		return $parsed;
	}
	
	private function sendResponse($response)
	{
		$sendService = new Essendex_Sendservice(
			$this->essconf->username,
			$this->essconf->password,
			$this->essconf->accountref
		);
		
		$result = $sendService->SendMessageFull(
			$formData['number'],
			$formData['message']
		);  
	}
}