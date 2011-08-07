<?php
class UserController extends Zend_Controller_Action
{
    
    protected $wallet;
    protected $password;
    protected $number;
    
    public function init()
    {
        // WHO GOES THERE!
        $userSession = new Zend_Session_Namespace('userSession');
        if (!$userSession->number) {
            $this->_redirect('/login/');
        }
        $this->wallet = Wallet::open($userSession->number, $userSession->password);
        $this->password = $userSession->password;
        $this->number = $userSession->number;
    }
    
    public function indexAction()
    {
        $this->_redirect('/user/dashboard');
    }
    
    public function dashboardAction()
    {
        $userSession = new Zend_Session_Namespace('userSession');
        $this->view->flashMessage = $userSession->flashMessage;
    }

    public function xhrGetTagListAction()
    {
        $this->view->tags = $this->wallet;
        $this->_helper->layout->disableLayout();
    }
    
    public function xhrGetActivityStreamAction()
    {
    	$userSession = new Zend_Session_Namespace('userSession');
    	
    	$this->view->activitystream = ActivityStream::getByAccountId($userSession->number);
    	
    	$this->_helper->layout->disableLayout();
    }
    
    public function xhrDeleteTagAction()
    {
        $tag = $this->_request->getParam('tag');
        if (isset($this->wallet[$tag])) {
            unset($this->wallet[$tag]);
        }
        $this->wallet->save($this->password);
        ActivityStream::create($this->number, 'Deleted tag ' . $tag);
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()
            ->appendBody('success');
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
                ActivityStream::create($this->number, 'Edited data for tag ' . $form->getValue('tag'));
	
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
                        $userSession->flashMessage = 'Your password has been changed.';
                        $wallet->save($formData['password']);
                        
                        ActivityStream::create($userSession->number, 'Changed your password');
                        
                        $this->_redirect('/user');  
                    } 
                    catch(BadWalletPassword $e) {
                        $form->getElement('password')->addError('Your password is wrong, douche.');
                    }
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

                        // Update the activity stream (This is not scalable)
                        ActivityStream::changeNumber($userSession->number, $formData['number']);
                        
                        // Get wallet
                        $wallet = Wallet::open($userSession->number, $userSession->password);
                        $wallet->rename($userSession->password, $formData['number']);
                                                
                        // Store
                        $userSession->number = $formData['number'];
                        $userSession->flashMessage = 'Your number has been changed.';
                        
                        // Save
                        ActivityStream::create($userSession->number, 'Changed your mobile number to '.$userSession->number);
                        
                        $this->_redirect('/user');
                    } 
                    catch(WalletNotFound $e) {
                        $form->getElement('number')->addError('That is not your current number!');
                    }
                }
                else {
                    $form->getElement('currentNumber')->addError('This is not your current number');
                }
            }
            else {
                $form->populate($formData);

            }
        }
        $this->view->form = $form;
    }
    
    public function detailsAction()
    {
        
    }
}

