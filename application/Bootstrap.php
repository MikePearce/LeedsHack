<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initConfig()
	{
		$configFile = APPLICATION_PATH . '/configs/config.ini';

		$config = new Zend_Config_Ini($configFile, APPLICATION_ENV);
		Zend_Registry::set('config', $config);
		return $config;
	}

	protected function _initBaseUrl()
	{
		$this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');

		$front->setBaseUrl(Zend_Registry::get('config')->siteurl);
	}

	protected function _initCache()
	{
		$frontend_options = array(
			'lifetime' => 3600, // cache lifetime of 1 hour
			'automatic_serialization' => true
		);

		$backend_options = array(
			'cache_dir' => '../cache/' // Directory where to put the cache files
		);

		//Normal Cache
		$cache = Zend_Cache::factory('Core', 'File', $frontend_options, $backend_options);

		Zend_Registry::set('cache', $cache);
	}

	protected function _initView()
	{
		$view = new Zend_View();
		$view->doctype('HTML5');

		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$view->jQuery()->enable();
		$view->jQuery()->setVersion('1.5.2');

		$view->jQuery()->UiEnable();
		$view->jQuery()->setUiVersion('1.8.6');
		$view->jQuery()->addStyleSheet('/themes/redmond/jquery-ui.custom.css');

		$view->jQuery()->addJavascriptFile('/js/app.js');

		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setView($view);
		return $view;
	}


	/**
	 * Initialize routes
	 *
	 * @return void
	 */
	protected function _initRouter()
	{
		$front = $this->getResource('FrontController');
		$router = $front->getRouter();
		return $router;
	}
}

