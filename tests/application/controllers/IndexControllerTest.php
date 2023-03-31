<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $usersModel;
    protected $sentEmailsModel;

    public function setUp(): void
    {
        require_once 'application/models/Users.php';
        require_once 'application/models/SentEmails.php';
        require_once 'vendor/autoload.php';
        
        parent::setUp();

        $dotenv = Dotenv\Dotenv::createImmutable('.');
        $dotenv->load();

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
        $this->sentEmailsModel = new Application_Model_SentEmails();
    }

    public function testSendEmail()
    {
        // Set up the request parameters
        $params = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'email-content' => 'Hello John, this is a test email!',
        ];

        // Dispatch the request
        $this->getRequest()
             ->setMethod('POST')
             ->setPost($params);
        $this->dispatch('/index/send-email');

        // Check that the email was sent
        $this->assertModule('default');
        $this->assertController('index');
        $this->assertAction('send-email');
        $this->assertQueryContentContains('div.alert', 'Email sent successfully!');

        // Check that the user was added to the database
        $usersModel = new Application_Model_Users();
        $user = $usersModel->fetchRow(['email = ?' => $params['email']]);
        $this->assertNotNull($user, 'User was not added to the database.');
    }
}
