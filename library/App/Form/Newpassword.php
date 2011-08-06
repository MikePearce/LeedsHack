<?
class App_Form_Newpassword extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('newPassword');
        
        $this->setAction('/signup/password')
             ->setMethod('post');

        $pass = new Zend_Form_Element_Password('password');
        $pass->setLabel('Your new password:')
                ->setRequired(true)
                ->addValidator('StringLength', false, array(4,15))
                ->addErrorMessage('Please choose a password between 6-12 characters')
                ->addValidator('NotEmpty', true);
                
        $passChk = new Zend_Form_Element_Password('passwordCheck');
        $passChk->setLabel('... and again:')
                ->setRequired(true)
                ->addValidator('Identical', false, array('token' => 'password'))
                ->addValidator('NotEmpty', true);
                
        

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');

        $this->addElements(array($pass, $passChk, $submit));

    } 
}
