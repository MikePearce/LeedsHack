<?php
class ErrorController extends Zend_Controller_Action
{

	/**
	 * This action handles
	 *    - Application errors
	 *    - Errors in the controller chain arising from missing
	 *     controller classes and/or action methods
	 */
	public function errorAction ()
	{
		$content = null;
		$errors = $this->_getParam ('error_handler') ;
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
				// 404 error -- controller or action not found
				$this->getResponse ()->setRawHeader ( 'HTTP/1.1 404 Not Found' ) ;
				// ... get some output to display...
				$content .= "<h1>404 Page not found!</h1>" . PHP_EOL;
				$content .= "<p>The page you requested was not found.</p>";
				break ;

			default :
				$exception = $errors->exception;

				if ($exception->getCode() == 403) {
					$content .= "<h1>Access Denied</h1>" . PHP_EOL;
					$content .= "<p>You do not have sufficient right to access this page, if you believe this to be an error, please contact an administrator.</p>";
				} else {

					$content .= "<h1>Error!</h1>" . PHP_EOL;
					$content .= "<p>An unexpected error occurred with your request. We have been notified and will look into as soon as possible. Please try again later.</p>";
$content .= "
    <div id=error>
        <p>
            <ul class=errorList>
                <li>
                    <h4>Exception information:</h4>
                    <p>". $exception->getMessage() ."</p>
                </li>
                <li>
                    <h4>Stack trace:</h4>
                    <p>". $exception->getTraceAsString() ."</p>
                </li>
               
            </ul>
        </p>
    </div>
";

				}
			break ;
		}

		// Clear previous content
		$this->getResponse()->clearBody();
		$this->view->content = $content;
	}
}
