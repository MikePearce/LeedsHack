<?php

class Verification {
    
    private $token;
    private $essconf;
    public $number;
    
    function __construct($number)
    {
        $this->number = $number;
    }
    
    public function createToken()
    {
        // Create the token
        $this->token = strtotime('now +'. rand(0, 1000) .' minutes');
        
        $kvs = Kvs::get('token');
        $kvs->save($this->number, $this->token);
        
        return $this;
    }
    
    public function sendToken()
    {
        // Get the config for the essendex stuff
        $this->essconf = Zend_Registry::get('config')->essendex;
        
       // Grab the send service
	    $sendService = new Essendex_Sendservice( 
            $this->essconf->username,
            $this->essconf->password,
            $this->essconf->accountref
        );

        // Send a message with a specified originator and validity period.
        $result = $sendService->SendMessageFull( 
            $this->number,
            "Your verification code: ". $this->token .". Love, SMSafe"
        );        
        
        var_dump($result);
    }
    
    
}