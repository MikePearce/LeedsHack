<?php
class LoginController extends Zend_Controller_Action
{
    public function init()
    {
        
    }
    
    public function indexAction()
    {
      if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            $form = new App_Form_Login();

            if ($form->isValid($formData)) {

                try {
                    // Get wallet
                    Wallet::open($formData['number'], $formData['password']);

                    // Create session
                    $userSession = new Zend_Session_Namespace('userSession');
                    $userSession->number = $formData['number'];
                    $this->_redirect('/user');
                } 
                catch(WalletNotFound $e) {

                    $form->getElement('number')->addError('This user cannot be found');
                }
                catch(BadWalletPassword $e) {
                    $form->getElement('password')->addError('Your password is wrong, douche.');
                }
                
                // Uh oh...
                $this->view->form = $form;  
            }
            else {
                // Uh oh...
                $this->view->form = $form;  
            }

        }
    }

}