<?
class App_Form_Signup extends Zend_Form 
{ 
    public function __construct($options = null, $action = '/signup/') 
    { 
        parent::__construct($options);
        $this->setName('signup');
        
        $this->setAction($action)
             ->setMethod('post');

        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('Your mobile number, dude...')
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter(new App_Filter_MobileNumber());

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');

        $this->addElements(array($number, $submit));

    } 
}
