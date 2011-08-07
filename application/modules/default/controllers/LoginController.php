<?php
class LoginController extends Zend_Controller_Action
{
    public function init()
    {
        
    }
    
    public function indexAction()
    {
        $form = new App_Form_Login();
      if ($this->_request->isPost()) {


            if ($form->isValid($this->_request->getPost())) {

                try {
                    // Get wallet
                    Wallet::open($form->getValue('number'), $form->getValue('password'));

                    // Create session
                    $userSession = new Zend_Session_Namespace('userSession');
                    $userSession->number = $form->getValue('number');
                    $userSession->password = $form->getValue('password');
                    $this->_redirect('/user');
                } 
                catch(WalletNotFound $e) {

                    $form->getElement('number')->addError('This user cannot be found');
                }
                catch(BadWalletPassword $e) {
                    $form->getElement('password')->addError('Your password is wrong, douche.');
                    ActivityStream::create($form->getValue('number'), 'Failed login attempt');
                }
                
                // Uh oh...
                $this->view->form = $form;  
            }
            else {
                // Uh oh...
                $this->view->form = $form;  
            }

        }
        $this->view->form = $form;  
    }

}