<?
class App_Form_TagData extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('tag-data');

        $number = new Zend_Form_Element_Text('tag');
        $number->setLabel('Tag')
            ->setRequired(true)
            ->addValidator('NotEmpty', true);

        $message = new Zend_Form_Element_Textarea('content');
        $message->setLabel('Content')
            ->setRequired(true)
            ->addValidator('NotEmpty');
            ->addValidator(new Zend_Validate_StringLength())l

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');

        $this->addElements(array($number, $submit));

    } 
}
