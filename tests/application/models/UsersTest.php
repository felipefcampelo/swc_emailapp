<?php

class UsersTest extends PHPUnit_Framework_TestCase
{
    protected $usersModel;

    protected function setUp(): void
    {
        require_once 'tests/bootstrap.php';
        require_once 'application/models/Users.php';
        require_once 'vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable('.');
        $dotenv->load();

        parent::setUp();

        $dbConfig = array(
            'host'     => $_ENV['DB_HOST'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'dbname'   => $_ENV['DB_NAME'],
            'charset'  => 'utf8',
        );

        $db = Zend_Db::factory('PDO_MYSQL', $dbConfig);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

        $this->usersModel = new Application_Model_Users();
    }

    public function testCreateUser()
    {
        $data = [
            'name' => 'John Doe test',
            'email' => 'johntest@example.com',
            'phone' => '123456789',
        ];

        $userId = (int) $this->usersModel->insert($data);
        $this->assertInternalType('integer', $userId, 'User ID should be an integer');
        $this->assertGreaterThan(0, $userId, 'User ID should be greater than 0');

        $createdUser = $this->usersModel->fetchRow($this->usersModel->select()->where('id = ?', $userId))->toArray();

        $expectedData = [
            'id' => $userId,
            'name' => 'John Doe test',
            'email' => 'johntest@example.com',
            'phone' => '123456789',
            'created_at' => $createdUser['created_at'],
        ];

        $this->assertEquals($expectedData, $createdUser, 'Created user data should match the provided data');
    }

    public function testUpdateUser()
    {
        // Implement test logic for updating a user
    }

    public function testRemoveUser()
    {
        // Implement test logic for removing a user
    }
}
