$(document).ready(function() {

    $(".countries-dropdown img.flag").addClass("flagvisibility");

    $(".countries-dropdown dt").click(function() {
        $(".countries-dropdown dd ul").toggle();
    });

    $(".countries-dropdown dd ul li a").click(function() {
        var text = $(this).html();console.log(text);
        $(".countries-dropdown dt").html(text);
        $(".countries-dropdown dd ul").hide();
    });

    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("countries-dropdown"))
            $(".countries-dropdown dd ul").hide();
    });

    $(".countries-dropdown img.flag").toggleClass("flagvisibility");
});
