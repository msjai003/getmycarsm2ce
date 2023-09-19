define([
        "jquery",
        "domReady!"
    ],
    function ($) {
        'use strict';

        if ($('.megamenu-vertical .megamenu-items > li[data-hidden="true"]').length) {
            $('.megamenu-vertical .more-items').css({"display": "block"})
        }

        $(document).on('click', '.megamenu-vertical .more-items', function () {
            $('.megamenu-vertical').toggleClass('show-all');
        });

        var currentUrl = window.location.href

        /**
         * Active item custom url primary
         */
        $('.megamenu-items > li.custom-url > a').each(function () {
            var href = $(this).attr('href');
            if (currentUrl == href) {
                $(this).parent('.custom-url').addClass('active');
            }
        });

        /**
         * Active item secondary menu
         */
        $('.secondary-megamenu .nav-items > li > a').each(function () {
            var href = $(this).attr('href');
            if (currentUrl == href) {
                $(this).parent().addClass('active');
            }
        });

        /**
         * Mobile
         */

        $(document).on('click', '.action.nav-toggle', function () {
            $('html').toggleClass('nav-open');
        });

        $('.megamenu-items .level0.parent').append('<span class="show-drop-btn"></span>');

        $(document).on('click', '.show-drop-btn', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).parent('.level0').removeClass('show-dropdown-menu');
                return;
            } else {
                $('.megamenu-items .level0').removeClass('show-dropdown-menu');
                $('.megamenu-items .show-drop-btn').removeClass('active');
                $(this).toggleClass('active');
                $(this).parent('.level0').toggleClass('show-dropdown-menu');

                setTimeout(function () {
                    $(document).trigger("afterAjaxProductsLoaded");
                    $(document).trigger("afterAjaxLazyLoad");
                }, 1000);
                return;
            }
        });
    });