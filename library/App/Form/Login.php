<?
class App_Form_Login extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('login');
        
        $this->setAction('/login/')
             ->setMethod('post');

        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('Your mobile number')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);

        $password = new Zend_Form_Element_Password('password');                
        $password->setLabel('Your password')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Login!');

        $this->addElements(array($number, $password, $submit));

    } 
}
