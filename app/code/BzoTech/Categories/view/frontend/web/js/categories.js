define([
        "jquery",
        "domReady!"
    ],
    function ($) {
        'use strict';

        $('.tabs-cate').each(function () {
            $(this).find('.tabs-list-cate .tab-item.active span').clone().appendTo($(this).find('.cat-select'));
        });

        $("body").on('click', '.cat-select', function () {
            $(this).closest('.tabs-cate').toggleClass('active');
        });

        $("body").on('click', '.tabs-cate .tabs-list-cate li', function () {
            var tabContent = "." + $(this).data('tab-content');

            if ($(this).hasClass() == 'active') {
                return;
            } else {
                $($(this).closest('.block-tab-categories').find('.tab-item')).removeClass('active');
                $(this).addClass('active');
                $(this).closest('.block-tab-categories').find('.tabs-cate-content').removeClass('active');
                $(this).closest('.block-tab-categories').find(tabContent).addClass('active');

                var nameCategory = $(this).find('span').clone();
                $(this).closest('.tabs-cate').find('.cat-select').html(nameCategory);
            }

            $(this).closest('.tabs-cate').removeClass('active');
        });
    });