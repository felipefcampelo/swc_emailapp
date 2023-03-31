<?php

class Application_Model_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';
    protected $_primary = 'id';

    public function getUserIdByEmail($email)
    {
        $select = $this->select()
                       ->from($this->_name, 'id')
                       ->where('email = ?', $email)
                       ->limit(1);

        $row = $this->fetchRow($select);
        if ($row) {
            return $row->id;
        }

        return null;
    }

    public function getName()
    {
        return $this->_name;
    }
}
