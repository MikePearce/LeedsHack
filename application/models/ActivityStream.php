<?php
class ActivityStream
{	
    
	public static function getByAccountId($accountId, $limit = 20)
	{
		try {
			$db = Zend_Registry::get('db');

            $res = $db->select()
            		  ->from('activityStream')
				      ->where('accountId = ? ', $accountId)
				      ->order('date DESC')
			      	  ->limit(10)
				      ->query();
				      
			return $res->fetchAll();
		} catch(Exception $e) {
			throw new Zend_Db_Exception($e->getMessage(), $e->getCode());
		}
	}
	
	public static function create($accountId, $message)
	{
		try {
			$db = Zend_Registry::get('db');
			$db->insert('activityStream', array(
				'accountId' => $accountId, 'message' => $message));
		} catch (Exception $e) {
			throw new Zend_Db_Exception($e->getMessage(), $e->getCode());
		}
		
	}
	
	public static function changeNumber($oldId, $newId)
	{
	    try {
			$db = Zend_Registry::get('db');
			$where = $db->quoteInto('accountId = ?', $oldId);
			$db->update('activityStream', array(
				'accountId' => $newId), $where);
		} catch (Exception $e) {
			throw new Zend_Db_Exception($e->getMessage(), $e->getCode());
		}
	}
}