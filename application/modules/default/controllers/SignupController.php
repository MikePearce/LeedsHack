<?php
class SignupController extends Zend_Controller_Action
{

    
    public function init()
    {
    }
    
    public function indexAction()
    {
        $this->view->pageTitle = "Mooop!";
        $this->view->pageContent = 'moo';
    }
}