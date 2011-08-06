<?
class App_Form_Signup extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('signup');

        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('Telephone Number')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Send Message');

        $this->addElements(array($number, $message, $submit));

    } 
}
