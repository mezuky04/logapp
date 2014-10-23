$(document).ready(function() {
    addNewApp();
    saveAppSettings();
    resetAPIKey();
    expandAccountOption();
    hideAccountOption();
    submitAccountSetting();
    switchListener();
    submitVerificationCode();
    changeLoginButtonText();
});

/**
 * Handle request to create a new application
 */
function addNewApp() {
    $('.add-new-app').click(function() {

        // Empty name
        if ($('.app-name-input').val() == "") {
            $('#form-input').attr("class", "form-group row has-error");
            $('.app-name-error').show().html("Please enter a name for your new application");
            return false;
        }
        showMask();
        $.post('applications', $('.new-app-form').serialize(), function(response) {
            data = jQuery.parseJSON(response);
            if (data.status == 'fail') {
                $('#form-input').attr("class", "form-group row has-error");
                $('.app-name-success').hide();
                $('.app-name-error').show().html(data.message);
                return false;
            } else if (data.status == 'success') {
                $('#form-input').attr("class", "form-group row has-success");
                $('.app-name-error').hide();
                $('.app-name-success').show().html(data.message);
            }
            hideMask();
            $('.apps').prepend('<a href="'+data.appId+'" class="list-group-item">'+data.appName+'<span class="badge">0 logs</span></a>');
        });
        return false;
    });
    $('.app-name-input').focusout(function() {
        $('.app-name-success').fadeOut();
        $('.app-name-input').val('');
    });
}

/**
 * Handle application save settings
 */
function saveAppSettings() {

    // Edit app settings button click
    $('#save-app-changes').click(function() {
        showMask();

        // Make post request
        $.post('application', $('#edit-app').serialize(), function(response) {

            var data = jQuery.parseJSON(response);
            hideMask();

            if (data.status === 'fail') {
                $('.success-message').hide();
                if (data.appNameError) {
                    $('#app-form').attr('class', 'form-group row has-error');
                }
                $('.fail-message').html(data.message).show();
                $('#app-name').focus();

            } else if (data.status === 'success') {
                $('.fail-message').hide();
                $('#app-form').attr('class', 'form-group row has-success');
                $('.success-message').html(data.message).show();
            }
        });
        // Prevent redirect
        return false;
    });
}

function submitVerificationCode() {
    $('.verification-code-form').submit(function() {
        showMask();
        var form = $('.verification-code-form').serialize();
        var requestUrl = $('.verification-code-form').attr('action');

        // Make post request
        $.post(requestUrl, form, function(response) {

            var data = jQuery.parseJSON(response);
            if (data.status == 'fail') {
                // An error occurred, display a message
                var newClass = 'verification-code-input has-error';
                $('#verification-code').attr('class', newClass);
                $('#verification-code-error').html(data.message).show();
            } else if (data.status == 'success') {
                // All ok, hide errors
                var newClass = 'verification-code-input has-success';
                $('#verification-code').attr('class', newClass);
                $('#verification-code-error').html('').hide();
                var redirectUrl = $('.verification-code-form').attr('redirect-url');
                window.location.replace(redirectUrl);
                return;
            }
            hideMask();
        });
        return false;
    });
}

/**
 * Handle application API key reset
 */
function resetAPIKey() {
    // Reset api key button click
    $('#reset-api-key').click(function() {
        showModal();
    });
}

/**
 * Show modal
 */
function showModal() {
    $('.modal').show();
}

/**
 * Hide the modal
 */
function hideModal() {
    $('.modal').hide();
}

/**
 * Show page mask
 */
function showMask() {
    $('.mask').show();
    $('.load').show();
}

/**
 * Hide page mask
 */
function hideMask() {
    $('.mask').hide();
    $('.load').hide();
}

/**
 * Expand an account settings option
 */
function expandAccountOption() {
    $('.account-settings-btn').click(function() {
        hideExpandedOptions();
        showNormalOptions();

        var button = $(this);
        var expand = button.attr('expand');
        $('#'+expand).hide();
        $('#'+expand+'-expanded').show();
    });
}

/**
 * Hide an account settings option
 */
function hideAccountOption() {
    $('.cancel-btn').click(function() {
        var button = $(this);
        var id = button.parents('.option-expanded').attr('id');
        $('#'+id).hide();
        id = id.substr(id, id.length - 9);
        $('#'+id).show();
    });
}

/**
 * Hide all account settings expanded options
 */
function hideExpandedOptions() {
    var options = ['email-option-expanded', 'phone-number-option-expanded', 'password-option-expanded'];
    $(buildSelector(options)).hide();
}

/**
 * Display normal options (not expanded)
 */
function showNormalOptions() {
    var options = ['email-option', 'phone-number-option', 'password-option'];
    $(buildSelector(options)).show();
}

/**
 * Build selector used to hide and show settings options
 *
 * @param options
 * @returns {string}
 */
function buildSelector(options) {
    var select = '';
    // Loop through options array and build the select string
    for (var i = 0; i < options.length; i++) {
        select += '#' + options[i] + ', ';
    }

    return select.substring(0, select.length - 2);
}

/**
 * Submit to server an account settings form
 */
function submitAccountSetting() {
    $('.update-btn').click(function() {
        // Serialize form in base on clicked update button
        var form = $(this).parent();

        // Get POST url
        var url = getPostUrl(form);

        form = form.serialize();

        // POST to server
        $.post(url, form, function(serverResponse) {
            // Response is in JSON format, so decode it
            serverResponse = jQuery.parseJSON(serverResponse);

            if (serverResponse.status === 'fail') {
                // An error occurred, display error message returned by the server
                displayAccountSettingsErrorMessage(serverResponse.message);
            } else {
                // Update header email
                updateHeaderEmail(serverResponse.newEmail);
                // All is fine, display the success message returned by the server
                displayAccountSettingsSuccessMessage(serverResponse.message);
            }
            return false;
        });
        return false;
    });
    $('.setting-form').submit(function(e) {
        // Serialize form in base on clicked update button
        var form = $('.setting-form');

        // Get POST url
        var url = $('.setting-form').attr('action');

        form = form.serialize();

        // POST to server
        $.post(url, form, function(serverResponse) {
            // Response is in JSON format, so decode it
            serverResponse = jQuery.parseJSON(serverResponse);

            if (serverResponse.status === 'fail') {
                // An error occurred, display error message returned by the server
                displayAccountSettingsErrorMessage(serverResponse.message);
            } else {
                // Update header email
                updateHeaderEmail(serverResponse.newEmail);
                // All is fine, display the success message returned by the server
                displayAccountSettingsSuccessMessage(serverResponse.message);
            }
            return false;
        });
        e.preventDefault();
    });
}

/**
 * Get form action attribute
 *
 * @param form
 * @returns {*}
 */
function getPostUrl(form) {
    return form.attr('action');
}

/**
 * Update user email in header
 *
 * @param email
 */
function updateHeaderEmail(email) {
    $('.account-email').html(email);
}

/**
 * Display error message returned by server for account settings
 *
 * @param message
 */
function displayAccountSettingsErrorMessage(message) {
    // todo same as the next function
    alert(message)
}

/**
 * Display success message returned by server for account settings
 *
 * @param message
 */
function displayAccountSettingsSuccessMessage(message) {
    // todo add a modal or light box instead of the alert
    alert(message);
}

/**
 * Two factor auth checkbox listener
 */
function switchListener() {
    $('.switch').change(function() {
//        if ($('.switch').is(':checked')) {
//            // 1 means checked
//            doTwoFactorAuthChangeRequest(1);
//        } else {
//            // 0 means unchecked
//            doTwoFactorAuthChangeRequest(0);
//        }
        doTwoFactorAuthChangeRequest();
    });
}

/**
 * Make two factor auth change request
 */
function doTwoFactorAuthChangeRequest() {
    var form = $('.two-factor-auth-form').serialize();
    var url = $('.two-factor-auth-form').attr('action');
    $.post(url, form, function(serverResponse) {
        serverResponse = jQuery.parseJSON(serverResponse);
        if (serverResponse.status === 'success') {
            alert('Two factor auth status was updated');
        }
    });
}

function changeLoginButtonText() {
    $('.login-form').submit(function() {
        $('.login-button').attr('value', 'Working...');
        var newButtonClass = $('.login-button').attr('class') + '-disabled';
        $('.login-button').attr('class', newButtonClass).attr('disabled', true);
    });
    $('.login-button-disabled').click(function() {
        alert('c');
    })
}

function checkFieldValue() {
    $('.login-input-error').focus(function() {
        //
    });
}