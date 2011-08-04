<?php
class Admin_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
	}

	public function phpinfoAction()
	{
		phpinfo();
		exit();
	}
}

