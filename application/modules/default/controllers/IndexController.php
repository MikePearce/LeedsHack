<?php
class IndexController extends Zend_Controller_Action
{
    /**
     * Contains the Essendex config details
     */
    private $essconf;
    
    public function init()
    {
        // Get the config for the essendex stuff
        $this->essconf = Zend_Registry::get('config')->essendex;
    }
    
    public function indexAction()
    {
        $this->view->pageTitle = "Mooop!";
        
        // Show the form
 	   $this->view->form = new App_Form_Signup();  
 	   
 	   // Get the login form
 	   $this->view->loginForm = new App_Form_Login();

    }
    
    public function aboutAction()
    {
    	$this->view->pageTitle = "Abooooot";
    }
	
	public function sendAction()
	{
        $this->view->pageTitle = "Send a message";
        
        // Show the form
 	   $form = new App_Form_SendSms();
 	
      
	    if ($this->_request->isPost()) {
	        
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                // Grab the send service
        	    $sendService = new Essendex_Sendservice( 
                    $this->essconf->username,
                    $this->essconf->password,
                    $this->essconf->accountref
                );

                // Send a message with a specified originator and validity period.
                $result = $sendService->SendMessageFull( 
                    $formData['number'],
                    $formData['message']
                );            
            }
            else {
                $form->populate($formData);
            }	        
    	    
	    }
	    
       $this->view->form = $form;
        
	}

}

