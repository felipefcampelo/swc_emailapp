<?php

class Application_Model_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';
    protected $_primary = 'id';

    public function getName()
    {
        return $this->_name;
    }
}
