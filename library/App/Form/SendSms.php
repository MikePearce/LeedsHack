<?
class App_Form_SendSms extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('send_sms');

        $number = new Zend_Form_Element_Text('number');
          $number->setLabel('number')
                    ->setRequired(true)
                    ->addValidator('NotEmpty', true);

            $message = new Zend_Form_Element_Textarea('message');
            $message->setLabel('Message')
                      ->setRequired(true)
                      ->addValidator('NotEmpty');

            $submit = new Zend_Form_Element_Submit('submit');
            $submit->setLabel('Send Message');

            $this->addElements(array($number, $message, $submit));

    } 
}
