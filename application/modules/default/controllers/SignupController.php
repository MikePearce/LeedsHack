<?php
class SignupController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->headTitle('Signup');
    }
    
    public function indexAction()
    {
        // Show the form
        $form = new App_Form_Signup();

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            
            if ($form->isValid($formData)) {
                
                // OK, valid form data, see if there's a matching JSON
                $kvs = new App_Model_Kvs();
                if ($kvs->exists($formData['number']))
                {
                    // Uh oh, you already have this shizzle
                    
                }
                else {
                    
                    // Send verification and show page
                    $this->view->form = new App_Form_Verification();
                    $this->render('verification');
                }
            }
            else {
                $form->populate($formData);
                $this->view->form = $form;     
            }
        }
        else {
            $this->view->form = $form;     
        }

    }
    
    public function verificationAction()
    {

    }
}