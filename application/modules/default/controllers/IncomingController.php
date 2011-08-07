<?php
class IncomingController extends Zend_Controller_Action
{
	protected $essconf;
	
    protected $allowedActions = array(
        'update',
        'get',
        'help',
        'delete',
        'add',
        'listtags'
    );
    
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
        
		$messageData = $this->parseMessage($data['MessageText']);
        $wallet = Wallet::open($data['From'], $messageData['passphrase']);
        
        $this->{$messageData['action']}($data['From'] , $wallet, $messageData);
	}
	
	protected function parseMessage($message)
	{
		$parsed = array();
		
        $message = preg_replace('/\s\s+/', ' ', $message);
        
        $parts = explode(' ', $message);
        
        if (!count($parts) > 1) {
            return array();
        }
        
        $parsed['passphrase'] = $parts[0];
        if (in_array(strtolower($parts[1]), $this->allowedActions)) {
            $parsed['action'] = strtolower($parts[1]);
        } else {
            $parsed['action'] = 'get';
            $parsed['tag'] = strtolower($parts[1]);
        }
        
        if (count($parts) > 2 && $parts[1] == $parsed['action']) {
            $parsed['tag'] = strtolower($parts[2]);
        }
        
        if (count($parts) > 3 && $parts[1] == $parsed['action']) {
            $parsed['content'] = implode(' ', array_slice($parts, 3));
        }
        
		return $parsed;
	}
	
    protected function update($number, $wallet, $messageData) 
    {
        $tag = $messageData['tag'];
        $wallet[$tag] = $messageData['content'] ?: '';
        $wallet->save($messageData['passphrase']);
       
        $message = 'Information for tag "' .
           $tag . ' stored successfully!';
		
		$this->sendResponse($number, $message);
		
		ActivityStream::create($number, 'Updated content for tag "' . $tag . '" via SMS');
    }
    
    protected function add($number, $wallet, $messageData)
    {
        $this->update($number, $wallet, $messageData);
    }
    
    
    protected function delete($number, $wallet, $messageData)
    {
        $tag = $messageData['tag'];
        
        if (isset($wallet[$tag])) {
            unset($wallet[$tag]);
            $wallet->save($messageData['passphrase']);
			$message = 'Information for tag "' .
               $tag . ' deleted successfully!';
		}
		
		$this->sendResponse($number, $message);
		
		ActivityStream::create($number, 'Deleted content for tag "' . $tag . '" via SMS');
    }
    
    protected function get($number, $wallet, $messageData) 
    {
        $tag = $messageData['tag'];
        if (isset($wallet[$tag])) {
			$message = 'Your SMSafe information for tag "' .
					   $tag .'":  ' . $wallet[$tag];
		} else {
			$message = 'Sorry, no data found for tag "'.
					   $tag . '" on SMSafe';
		}
		
		$this->sendResponse($number, $message);
		
		ActivityStream::create($number, 'Requested data for tag "' . $tag . '" via SMS');
    }
    
    protected function help($number, $wallet, $messageData)
    {
        $message = 'Possible actions are: get, add, update, delete, listTags. You need to send your password an action and'
            . ' a tag for all actions. Eg. pass1234 add bankdetails Account Number 123456. Get can be done just by'
            . ' password and tag.';

		$this->sendResponse($number, $message);
    }
    
    protected function listtags($number, $wallet, $messageData)
    {
        $tags = array();
        foreach ($wallet as $name => $value) {
            $tags[] = $name;
        }
        
        $message = "Your stored tags: " . implode(', ', $tags);
        
        $this->sendResponse($number, $message);
    }
    
	protected function sendResponse($number, $response)
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
		
		if ((!isset($result['Result']) || $result['Result'] != "OK") && $this->essconf->testmode == 0) {
			throw new MessageNotSent('Message failed to send with response for ID: ' . $number);	
		}
	}
}