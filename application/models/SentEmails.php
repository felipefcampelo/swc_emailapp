<?php

class Application_Model_SentEmails extends Zend_Db_Table_Abstract
{
    protected $_name = 'sent_emails';
    protected $_primary = 'id';

    public function fetchAllSentEmails()
    {
        $usersModel = new Application_Model_Users();

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from($this->getName())
                       ->join(
                            $usersModel->getName(), 
                            "{$this->getName()}.user_id = {$usersModel->getName()}.id", 
                            ['name', 'email', 'phone']
                        )
                       ->order("{$this->getName()}.created_at DESC");
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }

    public function getName()
    {
        return $this->_name;
    }
}
