<?php
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    public $application;

    public function tearDown()
    {
        $this->resetRequest();
        $this->resetResponse();
        parent::tearDown();
    }
}