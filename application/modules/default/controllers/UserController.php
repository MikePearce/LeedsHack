<?php
class UserController extends Zend_Controller_Action
{
    public function init()
    {
        // WHO GOES THERE!
        $userSession = new Zend_Session_Namespace('userSession');
        if (!$userSession->number) {
            $this->_redirect('/login/');
        }

    }
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
    
    public function numberAction()
    {
    	var_dump('Hello :)');exit;
    }

    public function tagAction()
    {
        //HACK: Pull these from session
        $id = '12345';
        $pass = 'test';
        
        $wallet = Wallet::open($id, $pass);
        
        $form = new App_Form_TagData();
        //open wallet
        $tag = $this->_request->getParam('tag');
        
        if ($tag) {
            if (isset($wallet[$tag])) {
                $form->populate(array('tag' => $tag, 'tag_content' => $wallet[$tag]));
            } else {
                $form->populate(array('tag' => $tag));
            }
        }
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $form->populate($formData);
            if ($form->isValid($formData)) {
                $wallet[$form->getValue('tag')] = $form->getValue('tag_content');
                $wallet->save($pass);
                $this->_redirect('/user/dashboard');
            }
        }

        $this->view->form =  $form;
    }
    
    public function changepasswordAction()
    {
        // Show the form
        $form = new App_Form_Newpassword(null, '/user/changepassword');
        
        // Add a 'current password' field
        $currentPass = new Zend_Form_Element_Password('currentPassword');
        $currentPass->setLabel('Your old password:')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);
        $currentPass->setOrder(0);
        $form->addElement($currentPass);

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();

            if ($form->isValid($formData))
            {        
                // Get the session
                $userSession = new Zend_Session_Namespace('userSession');
                
                // Check the old password
                if ($userSession->password == $formData['currentPassword'])
                {
                   try {
                        // Get wallet
                        $wallet = Wallet::open($userSession->number, $userSession->password);
                        $userSession->password = $formData['password'];
                        $wallet->save($formData['password']);
                    } 
                    catch(WalletNotFound $e) {

                        $form->getElement('number')->addError('This user cannot be found');
                    }
                    catch(BadWalletPassword $e) {
                        $form->getElement('password')->addError('Your password is wrong, douche.');
                    }
                        
                    $this->view->flashMessage = 'Your password has been changed.';
                    
                }
                else {
                    $form->getElement('currentPassword')->addError('This is not your password.');
                }
            }
            else {
                $form->populate($formData);

            }
        }
        $this->view->form = $form;
    }
    
    public function changenumberAction()
    {                
        // Show the form
        $form = new App_Form_Signup(null, '/user/changenumber');
        
        // Add a 'current password' field
        $currentNumber = new Zend_Form_Element_Text('currentNumber');
        $currentNumber->setLabel('Your old number:')
                ->setRequired(true)
                ->addValidator('NotEmpty', true);
        $currentNumber->setOrder(0);
        $form->addElement($currentNumber);

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();

            if ($form->isValid($formData))
            {        
                // Get the session
                $userSession = new Zend_Session_Namespace('userSession');
                
                // Check the old password
                if ($userSession->number == $formData['currentNumber'])
                {
                   try {
                        // Get wallet
                        $wallet = Wallet::open($userSession->number, $userSession->password);
                        $userSession->password = $formData['password'];
                        $wallet->save($formData['password']);
                    } 
                    catch(WalletNotFound $e) {

                        $form->getElement('number')->addError('That is not your current number!');
                    }
                     
                    $this->view->flashMessage = 'Your number has been changed.';
                    
                }
                else {
                    $form->getElement('currentPassword')->addError('This is not your password.');
                }
            }
            else {
                $form->populate($formData);

            }
        }
        $this->view->form = $form;
    }
}

