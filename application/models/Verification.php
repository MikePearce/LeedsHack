<?php

class App_Model_Verification {
    
    private $token;
    private $essconf;
    public $number;
    
    function __construct($number)
    {
        $this->number = $number;
        
        // Create the token
        $this->token = strtotime('now +'. rand(0, 1000) .' minutes');
        
        // Get the config for the essendex stuff
        $this->essconf = Zend_Registry::get('config')->essendex;
    }
    
    public function createVerification()
    {
        $kvs = get::Kvs('token');
        $kvs->save($this->number, $this->token);
        // Generate random string
        
        return $string;
    }
    
    public function sendVerification()
    {
       // Grab the send service
	    $sendService = new Essendex_Sendservice( 
            $this->essconf->username,
            $this->essconf->password,
            $this->essconf->accountref
        );

        // Send a message with a specified originator and validity period.
        $result = $sendService->SendMessageFull( 
            $this->number,
            $this->token
        );        
        
        var_dump($result);
    }
    
    
}