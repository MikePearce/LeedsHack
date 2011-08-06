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
}

