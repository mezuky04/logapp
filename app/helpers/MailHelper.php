<?php

/**
 * Class MailHelper
 *
 * Send emails
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class MailHelper {

    /**
     * @var string Recover email view name
     */
    private $_recoverEmailView = 'emails.recover';

    /**
     * @var string Config file name
     */
    private $_configName = 'recover.';

    /**
     * @var string Receiver email
     */
    private $_to = '';

    /**
     * @var string Sender email
     */
    private $_fromEmail = '';

    /**
     * @var string Sender name
     */
    private $_fromName = '';

    /**
     * @var string Email subject
     */
    private $_subject = '';


    /**
     * Send password recover email
     *
     * @param array $details
     * @throws Exception
     */
    public function sendPasswordRecoverEmail($details = array()) {

        $requiredDetails = array(
            'to',
            'recoverLink',
        );

        // Check for all required fields
        foreach ($details as $detail) {
           if (!in_array($detail, $requiredDetails)) {
               throw new Exception("'{{$detail}}' field is required");
           }
        }

        $this->_to = $details['to'];
        // Get values from config
        $this->_fromEmail = Config::get($this->_configName.'fromEmail');
        $this->_fromName = Config::get($this->_configName.'fromName');
        $this->_subject = Config::get($this->_configName.'subject');

        // Send mail
        Mail::send($this->_recoverEmailView, array(), function($message) {
            $message->from($this->_fromEmail, $this->_fromName);
            $message->to($this->_to)->subject($this->_subject);
        });
    }


    /**
     * Send account confirmation email
     *
     * @param string $userEmail
     * @param string $subscriptionPlan
     * @throws Exception
     */
    public function sendConfirmationEmail($userEmail, $subscriptionPlan) {
        // Validate parameters
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Confirmation email was not sent because given email is invalid");
        }
        $subscriptionDetailsModel = new SubscriptionDetailsModel();
        if (!$subscriptionDetailsModel->isSubscriptionPlan($subscriptionPlan)) {
            throw new Exception("Confirmation email was not sent because given subscription plan is invalid");
        }

        // All ok, try to send confirmation email
    }
}