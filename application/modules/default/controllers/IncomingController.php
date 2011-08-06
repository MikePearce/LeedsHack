<?php
class IncomingController extends Zend_Controller_Action
{
	
	public function init()
	{	
		if (!$this->_request->isPost()) {
			throw new Zend_Http_Exception('Bad Request', 400);
		}
	}
	
	public function indexAction()
	{	
		$reader = new XMLReader();
		$reader->XML($this->_request->getPost())
			   ->setParserProperty(XMLReader::VALIDATE, true);
		
		if ($reader->isValid()) {
			while ($reader->read()) {
				
			}
		} else {
			throw new Zend_Http_Exception('Invalid ', 400);
		}
		
		
	}
	
}