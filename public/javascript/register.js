$(document).ready(function() {
    $('.choose-country').click(function() {
        $('.countries-popup').show();
    });

    $('.country-item').click(function() {
        var countryPrefix = $(this).attr('prefix');
        var countryFlag = $(this).children().attr('src');
        $('.prefix').html('+' + countryPrefix);
        $('.country-icon').attr('src', countryFlag);
        $('.prefix-input').val(countryPrefix);
        $('.countries-popup').hide();
    });

    $('.register-button').click(function() {
        //
    });
});
