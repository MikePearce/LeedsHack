<?
class App_Form_Signup extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('signup');
        
        $this->setAction('/signup/')
             ->setMethod('post');

        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('Your mobile number, dude...')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Signup!');

        $this->addElements(array($number, $submit));

    } 
}
