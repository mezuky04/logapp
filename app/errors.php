<?php

/**
 * MissingParameterException
 *
 * This exception is thrown when a parameter is missing
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(MissingParameterException $exception) {
    Log::error($exception);
});


/**
 * InvalidUserIdException
 *
 * This exception is thrown when an user id is not numeric
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(InvalidUserIdException $exception) {
    Log::error($exception);
});


/**
 * MissingUserDetailException
 *
 * This exception is thrown when a required user detail that should be inserted in database is missing
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(MissingUserDetailException $exception) {
    Log::error($exception);
});


/**
 * CouldNotInsertNewUserException
 *
 * This exception is thrown when a new user could not be inserted in database
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(CouldNotInsertNewUserException $exception) {
    Log::error($exception);
});


/**
 * InvalidPhoneNumberPrefixException
 *
 * Thrown when a given phone number prefix is not valid
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(InvalidPhoneNumberPrefixException $exception) {
    Log::error($exception);
});


/**
 * CouldNotGetPrefixIdException
 *
 * Thrown when phone number prefix id is not returned from database
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(CouldNotGetPrefixIdException $exception) {
    Log::error($exception);
});


/**
 * CouldNotGetCountryIdExceotion
 *
 * Thrown when country id is not returned from database
 * @author Alexandu Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(CouldNotGetCountryIdException $exception) {
    Log::error($exception);
});


/**
 * CouldNotInsertPhoneNumberException
 *
 * Thrown when a phone number can not be inserted in database
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
App::error(function(CouldNotInsertPhoneNumberException $exception) {
    Log::error($exception);
});