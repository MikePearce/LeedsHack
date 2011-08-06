<?php
class IncomingController extends Zend_Controller_Action
{
	
	public function init()
	{	
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
	}
	
}