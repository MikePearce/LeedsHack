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

    }

	public function inboxAction()
	{
        
        // Essendex
        $inBox = new Essendex_Inboxservice(
            $this->essconf->username,
            $this->essconf->password,
            $this->essconf->accountref
        );
        
    // Get the results
       $result = $inBox->GetMessages();

       // The messages
       $messages = $result['Messages'];

       // Print them
       if ( !is_null( $messages ) )
       {
       	print "<br /><br />";
       	foreach ( $messages as $message )
       	{
       		foreach ( $message as $key => $value )
       		{
       			print "<b>$key</b>: $value<br />";
       		}

       		print "<br />";
       	}
       }
	}
	
	public function sendAction()
	{
        $this->view->pageTitle = "Send a message";
      
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
                    '07847440820,07847440820,07957606901',
                    'This is a test from the test app - FUCK YEAH'
                );            
            }
            else {
                $form->populate($formData);
            }	        
    	    
	    }
	    
	    // Show the form
	    $form = new Form_SendSms();
       $this->view->form = $form;
        

        
        // View the results
        print_r( $result );

        // Split the message IDs into an array.
        $messageIDs = split( ",", $result['MessageIDs'] );

        if ( !is_null( $messageIDs ) && sizeof( $messageIDs ) > 0 )
        {
        	print( "<br /><br />" );

        	foreach ( $messageIDs as $messageID )
        	{
        		print( "<b>Message ID</b>: $messageID<br />" );
        	}

        	print( "<br /><hr /><br />" );

        	// Get the status of the sent message(s).
        	print( "<b>GetMessageStatus</b><br />" );
        	foreach ( $messageIDs as $messageID )
        	{
        		$messageStatus = $sendService->GetMessageStatus( $messageID );

        		print_r( $messageStatus );

        		print( "<br /><br />" );

        		print( "<b>$messageID</b>: ".$messageStatus['MessageStatus']."<br /><br />" );
        	}
        }
	}

}

