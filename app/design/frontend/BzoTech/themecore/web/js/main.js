define([
        "jquery",
        "unveil",
        "domReady!"
    ],
    function ($) {
        /**
         * Check Sidebar has content
         */

        var sidebarElement = $(".sidebar").children().length;
        console.log(sidebarElement);
        if (sidebarElement) {
            $(".page-products").addClass('has-sidebar');
        } else {
            $(".page-products").addClass('no-sidebar');
        }


        /**
         * Lazy load image
         */

        if ($('.enable-ladyloading').length) {
            function _runLazyLoad() {
                $("img.lazyload").unveil(600, function () {
                    $(this).load(function () {
                        this.classList.remove("lazyload");
                    });
                });
            }

            setTimeout(function () {
                _runLazyLoad();
            }, 1000);

            $(document).on("afterAjaxLazyLoad", function () {
                _runLazyLoad();
            });
        }

        /**
         * Back to top
         */
        $(function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 500) {
                    $('.back2top').addClass('active');
                } else {
                    $('.back2top').removeClass('active');
                }
            });
            $('.back2top').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

        });

        /**
         * Sticky Menu
         */

        if ($('.enable-stickymenu').length) {
            var wd = $(window);
            if ($('.ontop-element').length) {
                var heightOntop = $('.page-header').height();
                /*$('.page-header-content').css({"height": heightOntop + "px"});*/

                function processScroll() {
                    var scrollTop = wd.scrollTop();
                    if (scrollTop >= heightOntop) {
                        $('body').addClass('body-on-top');

                    } else if (scrollTop < heightOntop) {
                        $('body').removeClass('body-on-top');
                    }
                }

                processScroll();
                wd.scroll(function () {
                    processScroll();
                });
            }
        }

        /**
         * Layered navigation
         */

        $(document).on('click', '.btn-open-close', function () {
            if ($(this).hasClass('active')) {
                $(this).closest('.filter-options-item').removeClass('active');
                $(this).removeClass('active');
                return;
            } else {
                $('.filter-options-item').removeClass('active');
                $('.btn-open-close').removeClass('active');
                $(this).closest('.filter-options-item').addClass('active');
                $(this).addClass('active');
            }

        });
    });