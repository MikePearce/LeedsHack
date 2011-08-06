<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function mapError($level, $message, $file, $line)
    {
         if(!($level & error_reporting())) return TRUE;

         $bt = debug_backtrace();
         array_shift($bt);

         foreach($bt as $frame) {
             if($frame['function'] == '__toString') return false;
         }

         throw new \ErrorException($message, 0, $level, $file, $line);
    }

    protected function _initErrorMapper()
    {
        set_error_handler(array($this, 'mapError'));
        register_shutdown_function('restore_error_handler');
    }

    //Hack for now to get models to autoload without the namespace
    protected function _initAutoLoader()
    {
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
    }
    
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

        $view->headMeta()->appendHttpEquiv('Content-Type','text/html;charset=UTF-8');

		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$view->jQuery()->enable();
		$view->jQuery()->setVersion('1.5.2');

		$view->jQuery()->UiEnable();
		$view->jQuery()->setUiVersion('1.8.14');
		$view->jQuery()->addStyleSheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/ui-lightness/jquery-ui.css');

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
        
        $route = new Zend_Controller_Router_Route(
                '/user/tag/:tag',
                        array(
                                'module'        => 'default',
                                'controller' => 'user',
                                'action'     => 'tag'
                        )
        );
        $router->addRoute('tag', $route);
		return $router;
	}
}

