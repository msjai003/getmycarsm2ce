define([
        "jquery",
        'mage/url',
        "domReady!"
    ],
    function ($, urlBuilder) {
        var searchKeyTimout;
        var firstFocus = true;
        var keyWordComplete = $(".ajax-search #search").val();

        function defaultSearch() {
            $.ajax({
                url: urlBuilder.build("ajaxsearch/ajax/trending"),
                type: "GET",
                data: "",
                success: function (res) {
                    console.log(res);
                    $(".drop-search-content").html(res);
                    $(document).trigger("afterAjaxLazyLoad");
                    $(".ajax-search").removeClass('loading');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }

        function searchByKeyword() {
            var keyWord = $('.ajax-search #search').val();

            $.ajax({
                url: urlBuilder.build("ajaxsearch/ajax/search/?q=" + keyWord),
                type: "GET",
                data: "",
                success: function (res) {
                    var resAjaxSearch = res.replaceAll("/ajaxsearch/ajax/", "/catalogsearch/result/");
                    $(".drop-search-content").html(resAjaxSearch);
                    $(document).trigger("afterAjaxLazyLoad");
                    $(".ajax-search").removeClass('loading');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }

        function ajaxSearch() {
            $(".ajax-search").addClass('loading');
            var searchKeyword = $(".ajax-search #search").val();
            $('body').addClass('search-ajax-active');

            if (searchKeyword.length >= 3) {
                searchByKeyword();
            } else {
                defaultSearch();
            }

            keyWordComplete = searchKeyword;
        }

        $("body").on('click', '.close-search-ajax', function () {
            $('body').removeClass('search-ajax-active');
        });

        $(document).mouseup(function (e) {
            var container = $(".bzotech-search-autocomplete, .ajax-search");

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('body').removeClass('search-ajax-active');
            }
        });

        $('.ajax-search .input-text').focus(function () {
            $('body').addClass('search-ajax-active');

            if ($(".ajax-search #search").val() != keyWordComplete || firstFocus) {
                ajaxSearch();
                firstFocus = false;
            }
        });

        $(".ajax-search #search").keyup(function () {
            clearTimeout(searchKeyTimout);
            searchKeyTimout = setTimeout(function () {
                if (keyWordComplete != $(".ajax-search #search").val()) {
                    ajaxSearch();
                }

            }, 700);
        })
    });