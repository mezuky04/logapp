$(document).ready(function() {
    // Expand account setting option
    expandAccountOption();

    // Rollback an expanded option
    hideAccountOption();

    // Submit to srever changes
    submitAccountSetting();
});

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
        var form = $(this).parent().serialize();

        // POST to server
        $.post('settings', form, function(serverResponse) {
            // Response is in JSON format, so decode it
            serverResponse = jQuery.parseJSON(serverResponse);

            if (serverResponse.fail) {
                // An error occurred, display error message returned by the server
                displayAccountSettingsErrorMessage(serverResponse.fail);
            }
            // All is fine, display the success message returned by the server
            displayAccountSettingsSuccessMessage(serverResponse.success);
        });
        // Prevent redirect
        return false;
    });
}

function displayAccountSettingsErrorMessage(message) {
    //
}

function displayAccountSettingsSuccessMessage(message) {
    //
}