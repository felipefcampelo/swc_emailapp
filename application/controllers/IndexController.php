<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
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
            if (empty($formData['content'])) {
                $isValid = false;
                $errors[] = 'Content is required.';
            }

            if ($isValid) {
                $mail = new Zend_Mail();
                $mail->setFrom($formData['fromEmail'], $formData['fromName'])
                     ->addTo($formData['toEmail'], $formData['toName'])
                     ->setSubject($formData['subject'])
                     ->setBodyHtml($formData['emailContent']);

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
                    $mail->send($transport);
                    $this->view->success = "";
                    $this->render('index');
                } catch (Exception $e) {
                    $this->view->fail = "";
                    $this->render('index');
                }

                $this->_helper->redirector('index');
            } else {
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
}
