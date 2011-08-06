<?php
class UserController extends Zend_Controller_Action
{
    
    protected $wallet;
    protected $password;
    
    public function init()
    {
        // WHO GOES THERE!
        $userSession = new Zend_Session_Namespace('userSession');
        if (!$userSession->number) {
            $this->_redirect('/login/');
        }
        $this->wallet = Wallet::open($userSession->number, $userSession->password);
        $this->password = $userSession->password;
        

    }
    public function indexAction()
    {
        $this->_redirect('/user/dashboard');
    }
    
    public function dashboardAction()
    {

    }

    public function xhrGetTagListAction()
    {
        $this->view->tags = $this->wallet;
        $this->_helper->layout->disableLayout();
    }
    
    public function xhrTagAction()
    {
        
        $form = new App_Form_TagData();
        $form->setAttrib('action', '/user/xhr-tag');
        //open wallet
        $tag = $this->_request->getParam('tag');
        
        if ($tag) {
            if (isset($this->wallet[$tag])) {
                $form->populate(array('tag' => $tag, 'tag_content' => $this->wallet[$tag]));
            } else {
                $form->populate(array('tag' => $tag));
            }
        }
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $form->populate($formData);
            if ($form->isValid($formData)) {
                $this->wallet[$form->getValue('tag')] = $form->getValue('tag_content');
                $this->wallet->save($this->password);
                $this->_helper->viewRenderer->setNoRender();
                $this->getResponse()
                    ->appendBody('success');
            }
        }

        $this->view->form =  $form;
        $this->_helper->layout->disableLayout();

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

