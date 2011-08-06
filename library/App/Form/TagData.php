<?php
/**
 * Form for the user data input page
 */
class App_Form_TagData extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        $this->setName('tag-data');

        $tag = new Zend_Form_Element_Text('tag');
        $tag->setLabel('Tag')
            ->setRequired(true)
            ->addValidator('NotEmpty', true);

        $content = new Zend_Form_Element_Textarea('tag_content');
        $content->setLabel('Content')
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_StringLength(array('max' => '140')));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');

        $this->addElements(array($tag, $content, $submit));
    } 
}
