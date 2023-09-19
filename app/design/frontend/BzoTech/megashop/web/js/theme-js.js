define([
        "jquery",
        "unveil",
        "domReady!",
        "slickJs"
    ],
    function ($) {
        /**
         * Theme custom javascript
         */
        /* update main image when click thumb */
        $('.product_thumbs ul li').click(function(){
            var mainimage = $(this).find('img').attr('data-large-image');
            $(this).closest('.product-image').find('.product-image-photo').attr('src', mainimage);
        });

        /**
         * Fix hover on IOS
         */
        $('body').bind('touchstart', function () {
        });

        /**
         * Click show filter on mobile
         */

        $("body").on('click', '#btn-filter, .filter-overlay, #close-filter', function () {
            $('html').toggleClass('show-filter-sidebar');
        });

        /**
         * Focus input search
         */

        $('.block-search .input-text,.block-search .searchbox-cat').focus(function () {
            $('body').addClass('search-active');
        });

        $('.block-search .input-text,.block-search .searchbox-cat').blur(function () {
            $('body').removeClass('search-active');
        });

        /**
         * Mobile
         */

        $('#desktop-customer-links > .header.links').clone().appendTo('#mobile-customer-links');

        $("body").on('click', '#mobile-nav,.nav-mobile-overlay', function () {
            $('body').toggleClass('show-nav-mobile');
        });

        function appendToMobile() {
            var breakpoints = $('#bzo-header-mobile').data('breakpoint');
            var doc_width = $(window).width();
            if (doc_width <= breakpoints) {
                var mobileElement = $('div[data-element-mobile]');
                mobileElement.each(function () {
                    var mobileContent = $(this).find('div[data-mobile-content]');
                    var mobileElement = $(this).data('element-mobile');
                    $("#" + mobileElement).append(mobileContent);
                })
            } else {
                var desktopElement = $('div[data-element-desktop]');
                desktopElement.each(function () {
                    var desktopContent = $(this).find('div[data-mobile-content]');
                    var desktopElement = $(this).data('element-desktop');
                    $("#" + desktopElement).append(desktopContent);
                })
            }
        }

        appendToMobile();

        $(window).resize(function () {
            appendToMobile();
        });

        /**
         * Sticky menu
         */

        $("body").on('click', '.sticky-nav-btn a, .overlay-sticky-menu', function () {
            $('html').toggleClass('show-sticky-menu');
        });

        /**
         * Filter Sidebar
         */
        $("body").on('click', '.close-filter, .toolbar-filter .btn-filter, .filter-overlay', function () {
            $('html').toggleClass('show-filter');
        });

        /**
         * Button Qty
         */
        $('.qty-plus').click(function () {
            var qty = $(this).parent().prev(".tf-qty");
            qty.val(Number(qty.val()) + 1);
            return;
        });

        $('.qty-minus').click(function () {
            var qty = $(this).parent().prev(".tf-qty");
            var value = Number(qty.val()) - 1;
            if (value > 0) {
                $(qty).val(value);
            }
            return;
        });

        /**
         * Countdown static
         */

        function _countDownStatic(date, id) {
            var dateNow = new Date();
            var amount = date.getTime() - dateNow.getTime();
            delete dateNow;
            if (amount < 0) {
                id.html("Now!");
            } else {
                var days = 0;
                hours = 0;
                mins = 0;
                secs = 0;
                out = "";
                amount = Math.floor(amount / 1000);
                days = Math.floor(amount / 86400);
                amount = amount % 86400;
                hours = Math.floor(amount / 3600);
                amount = amount % 3600;
                mins = Math.floor(amount / 60);
                amount = amount % 60;
                secs = Math.floor(amount);
                $(".time-day .num-time", id).text(days);
                $(".time-day .title-time", id).text(((days <= 1) ? "Day" : "Days"));
                $(".time-hours .num-time", id).text(hours);
                $(".time-hours .title-time", id).text(((hours <= 1) ? "Hour" : "Hours"));
                $(".time-mins .num-time", id).text(mins);
                $(".time-mins .title-time", id).text(((mins <= 1) ? "Min" : "Mins"));
                $(".time-secs .num-time", id).text(secs);
                $(".time-secs .title-time", id).text(((secs <= 1) ? "Sec" : "Secs"));
                setTimeout(function () {
                    _countDownStatic(date, id)
                }, 1000);
            }
        }

        $(".countdown-static").each(function () {
            var timer = $(this).data('timer');
            var data = new Date(timer);
            _countDownStatic(data, $(this));
        });

        /**
         * Close message qty error
         */

        $("body").on('click', '.box-tocart .control div.mage-error', function () {
            $(this).parent('.control').children('.qty').removeClass('mage-error');
            $(this).remove();
        });    
        $(".add-review-link a").click(function(){
            $(".reviews-actions .action.add").trigger('click');
        });
        var $owl = $( '.list.owl-carousel' );
        $owl.on('initialized.owl.carousel', function(event){ 
            // Do something

            $(".tabs-product-wrapper .product-items .slide-item").each(function(){
            var h =$(this).height();
            $(this).css('height', h);
            });

            $('.product_thumbs ul').slick ({
                slidesToShow: 4,
                infinite: false,
                slidesToScroll: 1,
                draggable:true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: false,
                dots: false,
                vertical: true,
                verticalSwiping: true,
                responsive: [
                    {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        infinite: false,
                        slidesToScroll: 1,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        arrows: true,
                        dots: false,
                        vertical: false,
                    }
                    },
                    {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        infinite: false,
                        slidesToScroll: 1,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        arrows: true,
                        dots: false,
                        vertical: false,
                    }
                    }
                ]
            }); 
        });
    });
  
