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
                $kvs = Kvs::get('wallet');
                if ($kvs->exists($formData['number']))
                {
                    // Uh oh, you already have this shizzle
                    $this->view->loginForm = new App_Form_Login();
                    $this->render('exists');
                }
                else {
                    // Create verification
                    $verification = new Verification($formData['number']);
                    $verification->createToken()->sendToken();
                    
                    // Then show the page
                    $this->view->form = new App_Form_Verification(null, $formData['number']);
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
    
    /**
     * 1. Get form data
     * 2. Check is valid
     * 3. Open number file
     * 4. Compare verification codes
     **/
    public function verificationAction()
    {
       if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            $form = new App_Form_Verification(null, $formData['number']);

            if ($form->isValid($formData)) {
                
                // Get an instance
                $kvs = Kvs::get('token');
                
                // Get the code
                $veriCode = $kvs->load($formData['number']);
                
                // Check it it matches
                if ($veriCode == $formData['veriCode']) {
                    
                    // Create session
                    $userSession = new Zend_Session_Namespace('userSession');
                    $userSession->number = $formData['number'];
                    
                    // Create wallet
                    $wallet = Wallet::create($formData['number']);
                    
                    // Kill the verification
                    $kvs->delete($formData['number']);
                    
                    // It does, huzzah!
                    $this->_redirect('/user');
                }
                else {
                    // Uh oh...
                    $this->view->form = $form;  
                    $this->render('nomatch');
                }
                
            }
        }
    }
}








