<?php

/**
 * Class ApplicationHelper
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ApplicationHelper {

    /**
     * @var string Application config name
     */
    private static $_applicationConfig = 'application.';


    /**
     * Validate the given application name
     *
     * @param string $applicationName
     * @param int $userId
     * @return array
     */
    public static function validateApplicationName($applicationName, $userId) {

        // Application name can not be empty
        if (empty($applicationName)) {
            return array(
                'appNameError' => true,
                'emptyAppName' => true
            );
        }

        // Application name can contain only alpha-numeric characters
        if (!ctype_alnum($applicationName)) {
            return array(
                'appNameError' => true,
                'invalidAppName' => true
            );
        }

        // Check for app max length
        if (strlen($applicationName) > Config::get(self::$_applicationConfig.'nameMaxLength')) {
            return array(
                'appNameError' => true,
                'tooLongAppName' => true,
                'appNameMaxLength' => Config::get(self::$_applicationConfig.'nameMaxLength')
            );
        }

        // Check if name is already used
        $applicationsModel = new ApplicationsModel();
        if ($applicationsModel->appNameUsed($applicationName, $userId)) {
            return array(
                'appNameError' => true,
                'alreadyUsedAppName' => true
            );
        }
    }

    public static function validate2($applicationName, $userId) {
        // Application name can not be empty
        if (empty($applicationName)) {
            return array(
                'error' => 'Empty application name'
            );
        }

        // Application name can contain only alpha-numeric characters
        if (!ctype_alnum($applicationName)) {
            return array(
                'error' => 'Application name can contain only alpha-numeric characters'
            );
        }

        // Check for app max length
        if (strlen($applicationName) > Config::get(self::$_applicationConfig.'nameMaxLength')) {
            return array(
                'error' => 'Application name can have a max length of'.Config::get(self::$_applicationConfig.'nameMaxLength')
            );
        }

        // Check if name is already used
        $applicationsModel = new ApplicationsModel();
        if ($applicationsModel->appNameUsed($applicationName, $userId)) {
            return array(
                'error' => 'This name is already used by another of your applications'
            );
        }
    }
}