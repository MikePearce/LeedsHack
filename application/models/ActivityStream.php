<?php
class ActivityStream
{	
    
	public static function getByAccountId($accountId, $limit = 20)
	{
		try {
			$db = Zend_Registry::get('db');
		    $select = $db->select()
			          ->from('activityStream')
				      ->where('accountId = ? ', $accountId);
            
            if ($limit) {
                $select->limit($limit, 0);
            }

            $res = $select->query();
				      
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
}