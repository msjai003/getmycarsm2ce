define([
    'jquery',
    'bzo_owlCarousel'
], function ($) {

    return function (config, element) {
        $(element).owlCarousel(config);
    };
});