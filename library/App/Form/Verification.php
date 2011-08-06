<?
class App_Form_Verification extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('verification');
        
        $this->setAction('/signup/verification')
             ->setMethod('post');

        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('Verification number')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');

        $this->addElements(array($number, $submit));

    } 
}
