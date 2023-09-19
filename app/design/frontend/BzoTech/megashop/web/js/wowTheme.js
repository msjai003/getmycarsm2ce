define([
        "jquery",
        "wowJs",
        "domReady!"
    ],
    function ($, WOW) {
        var wow = new WOW(
            {
                mobile: false,
            }
        );
        wow.init();
    });