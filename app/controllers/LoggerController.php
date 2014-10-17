<?php

/**
 * Class LoggerController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class LoggerController extends BaseController {

    /**
     * @var string Create new logger page template name
     */
    private $_newLoggerLayout = 'new-logger';

    /**
     * @var int Create new logger page redirect code
     */
    private $_newLoggerRedirectCode = 1;


    /**
     * Show create new logged page
     *
     * @return mixed
     */
    public function showCreateNewLoggerPage() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('login?next='.$this->_newLoggerRedirectCode);
        }

        return View::make($this->_newLoggerLayout);
    }


    /**
     * Validate and create new logger
     */
    public function processNewLogger() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('login?next='.$this->_newLoggerRedirectCode);
        }

        $loggerName = Input::get('logger-name');
        $loggerDescription = Input::get('logger-description');
        $defaultLogLevel = Input::get('default-log-level');
        $smsNotificationLogLevel = Input::get('sms-notification-log-level');
        $emailNotificationLogLevel = Input::get('email-notification-log-level');

        // Empty logger name
        if (!isset($loggerName)) {
            return View::make($this->_newLoggerLayout, array('emptyLoggerName' => true));
        }

        // Logger name can have only alpha-numeric characters
        if (!ctype_alnum($loggerName)) {
            return View::make($this->_newLoggerLayout, array('invalidLoggerName' => true));
        }

        // Too long logger name
        if (strlen($loggerName) > 50) {
            return View::make($this->_newLoggerLayout, array('tooLongLoggerName' => true));
        }

        // Check if logger name is not already used

        // Empty logger description
        if (!isset($loggerDescription)) {
            return View::make($this->_newLoggerLayout, array('emptyLoggerDescription' => true));
        }
    }
}