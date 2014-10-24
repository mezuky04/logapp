$(document).ready(function() {
    $('.choose-country, .choose-country-error').click(function() {
        $('.countries-popup').show();
    });

    $('.country-item').click(function() {
        var countryPrefix = $(this).attr('prefix');
        var countryFlag = $(this).children().attr('src');
        $('.prefix').html('+' + countryPrefix);
        $('.country-icon').attr('src', countryFlag);
        $('.prefix-input').attr('value', countryPrefix);
        $('.countries-popup').hide();
    });
    changeRegisterButtonText();
});

function changeRegisterButtonText() {
    $('.register-form').submit(function() {
        $('.register-button').attr('value', 'Working...');
        var newButtonClass = $('.register-button').attr('class') + '-disabled';
        $('.register-button').attr('class', newButtonClass).attr('disabled', true);
    });
}