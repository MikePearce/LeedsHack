<?php
class UserController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $this->_redirect('/user/dashboard');
    }
    
    public function dashboardAction()
    {
        
    }

    public function dataAction()
    {
       
    }
}

