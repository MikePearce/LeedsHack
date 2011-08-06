<?php
class UserController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('/user/dashboard');
    }
    
    public function dashboardAction()
    {
        // TODO: load the user's real list of tags
        $tags = array(
            'pin' => 1234,
            'password' => 'secret'
        );

        $this->view->tags = $tags;
    }

    public function tagDataAction()
    {
        $tag = $this->_request->getParam('tag');
        if ($tag)
        $form = new App_Form_TagData(); 
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $form->populate($formData);
            if ($form->isValid($formData)) {
               //Code for valid goes here
            }            
        }

        $this->view->form =  $form;
    }
}

