<?
class App_Form_Verification extends Zend_Form 
{ 
    public function __construct($options = null, $phoneNumber) 
    { 
        parent::__construct($options);
        $this->setName('verification');
        
        $this->setAction('/signup/verification')
             ->setMethod('post');

        $veriCode = new Zend_Form_Element_Text('veriCode');
        $veriCode->setLabel('Verification number')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);
        
        $number = new Zend_Form_Element_Hidden('number');
        $number->setValue($phoneNumber);
        

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');

        $this->addElements(array($veriCode, $number, $submit));

    } 
}
