<?php
class App_Filter_MobileNumber implements Zend_Filter_Interface
{
	public function filter($value)
	{
		//07825750807
		return preg_replace('/^0/','44', $value);
	}
}