<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function sendEmailAction()
    {
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $isValid = true;
            $errors = [];

            // Validate name
            if (empty($formData['name'])) {
                $isValid = false;
                $errors[] = 'Name is required.';
            }

            // Validate email
            if (empty($formData['email'])) {
                $isValid = false;
                $errors[] = 'Email is required.';
            } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $isValid = false;
                $errors[] = 'Email is not valid.';
            }

            // Validate content
            if (empty($formData['email-content'])) {
                $isValid = false;
                $errors[] = 'Content is required.';
            }

            if ($isValid) {
                // Email template
                $emailTemplate = file_get_contents(APPLICATION_PATH . '/views/email/templates/default.html');

                // Formated date and time
                $datetime = new DateTime();
                $currentDateTime = $datetime->format('m/d/Y') . ' at ' . $datetime->format('H\hi\m\i\n');

                $emailHtmlContent = str_replace('{{datetime}}', $currentDateTime, $emailTemplate);
                $emailHtmlContent = str_replace('{{name}}', $formData['name'], $emailHtmlContent);
                $emailHtmlContent = str_replace('{{content}}', $formData['email-content'], $emailHtmlContent);

                $mail = new Zend_Mail();
                $mail->setFrom($_ENV['FROM_EMAIL'], $_ENV['FROM_NAME'])
                    ->addTo($formData['email'], $formData['name'])
                    ->setSubject($_ENV['EMAIL_SUBJECT'])
                    ->setBodyHtml($emailHtmlContent);

                $transport = new Zend_Mail_Transport_Smtp(
                    $_ENV['SMTP_HOST'],
                    [
                        'auth'     => $_ENV['SMTP_AUTH'],
                        'username' => $_ENV['SMTP_USERNAME'],
                        'password' => $_ENV['SMTP_PASSWORD'],
                        'ssl'      => $_ENV['SMTP_SSL'],
                        'port'     => (int) $_ENV['SMTP_PORT'],
                    ]
                );

                try {
                    // Send the email
                    $mail->send($transport);

                    // Store in the database
                    $this->storeSentEmail($formData['email'], $emailHtmlContent);

                    // Success message
                    $this->_helper->flashMessenger->addMessage('success', 'Email sent successfully!');
                    $this->render('index');
                } catch (Exception $e) {
                    // Log
                    $this->logError($e->getMessage());

                    // Error message
                    $this->_helper->flashMessenger->addMessage('error', "We can't send the email.");

                    $errors[] = "We can't send the email.";
                    $this->view->errors = $errors;
                    $this->render('index');
                }

                $this->_helper->redirector('index');
            } else {
                // Log
                $errorMsg = implode(' | ', $errors);
                $this->logError($errorMsg);

                // Error message
                $this->_helper->flashMessenger->addMessage('error', $errorMsg);

                $this->view->errors = $errors;
                $this->render('index');
            }
        } else {
            $this->_helper->redirector('index');
        }
    }

    public function sentAction()
    {
        // Fetch the sent emails from the database
        $sentEmailsModel = new Application_Model_SentEmails();
        $sentEmails = $sentEmailsModel->fetchAllSentEmails();

        $this->view->sentEmails = $sentEmails;
    }

    protected function storeSentEmail($email, $emailContent)
    {
        $userModel = new Application_Model_Users();
        $user = $userModel->fetchRow(['email = ?' => $email]);

        if ($user) {
            $sentEmailsModel = new Application_Model_SentEmails();
            $data = [
                'user_id' => $user->id,
                'email_content' => $emailContent,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $sentEmailsModel->insert($data);
        }
    }

    // Initialize the Zend Log
    protected function getLogger()
    {
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/error.log');
        $logger->addWriter($writer);
        return $logger;
    }

    // Create a log entry
    protected function logError($errorMessage)
    {
        $logger = $this->getLogger();
        $logger->err($errorMessage);
    }
}
