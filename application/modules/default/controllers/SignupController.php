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

                    
                    // Now ask for a password
                    $this->view->form = new App_Form_Newpassword();
                    $this->render('password');
                }
                else {
                    // Uh oh...
                    $this->view->form = $form;  
                    $this->render('nomatch');
                }
                
            }
        }
    }
    
    public function passwordAction()
    {
        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            $form = new App_Form_Newpassword();

            if ($form->isValid($formData))
            {        
                // Get the session
                $userSession = new Zend_Session_Namespace('userSession');

                // Get an instance of the kvs
                $kvs = Kvs::get('token');

                // Kill the verification
                $kvs->delete($userSession->number);

                // Create wallet
                $wallet = Wallet::create($userSession->number);
                $wallet->save($formData['password']);

                $this->_redirect('/user');
            }
            else {
                $form->populate($formData);
                $this->view->form = $form;
            }
        }
    }
    
    public function logoutAction()
    {
        $userSession = new Zend_Session_Namespace('userSession');
        $userSession->unLock();
        Zend_Session::namespaceUnset('userSession');
        $this->_redirect('/');
    }
}








