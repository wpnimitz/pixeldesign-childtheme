jQuery(document).ready(function( $ ) {

    $mainSite = 'http://newdelmar.flywheelsites.com/';

    $common = {
        actual : $mainSite,
        restate : $mainSite + 'properties/',
        rental : $mainSite + 'rental-properties/',
        difference : $mainSite + 'development/',
        assetSVG : '/wp-content/themes/pixeldesign-childtheme/assets/svg/',
        version : '1.0.6'
    }

    //temporary fix for mobile issue
    $fromBody = false;
    var windowsize = $(window).width();
    if (windowsize < 768) {
        $fromBody = true;
    }

    //console.log("Version: " + $common["version"])


    //initial function
    function checkMenu() {
        logo_img = $(".logo_container img");
        logo_link = $(".logo_container a");
        sMenu = Cookies.get('sMenu');

        //reset
        $("#top-menu > li").addClass("hidemenu");
        $("#et-secondary-nav > li").removeClass("strong");
        
        //try condition first
        if( $('body').hasClass("home") ) {
            sMenu = "actual";
            $("#top-header").hide();
        } else if ( $('body').hasClass('single-property') ) {
            sMenu = "restate";
        } else if ( $('body').hasClass('single-rental') ) {
            sMenu = "rental";
        } else if ( $('body').hasClass('rental-properties') ) {
            sMenu = "rental";
        } else if ( $('body').hasClass('properties') ) {
            sMenu = "restate";
        } else if ( $('body').hasClass("development") ) {
            sMenu = "difference";
        } else {
            console.log("no condition found main check");
        }
        Cookies.set('sMenu', sMenu);
        //console.log("revealing menu: " + sMenu);

        logo_img.attr("src", $common["assetSVG"] + sMenu + '.svg');
        logo_img.removeClass().addClass(sMenu)
        logo_link.attr("href", $mainSite);
        $("#et-secondary-nav li." + sMenu).addClass("strong");
        $("#top-menu li." + sMenu).removeClass("hidemenu");  
    }
    checkMenu();



    /** 
    @event clicker
    #top-header a is for the default menu
    .top-header is added as a class on the menu
    .delmar-logo is added as a header snipper in header.php
    **/
    function click_menus(e) {
        aHTML = $(e).html();
        parentA = $(e).parent();

        if(aHTML == "Real Estate" ) {
            sMenu = "restate";
        } else if (aHTML == "Rental") {
            sMenu = "rental";
        } else if (aHTML == "The Del Mar Difference") {
            sMenu = "difference";
        } else {
            if( parentA.hasClass('rental') ) {
                sMenu = "rental";
            } else if ( parentA.hasClass('restate') ) {
                sMenu = "restate";
            } else if ( parentA.hasClass('difference') ) {
                sMenu = "difference";
            } else {
                console.log("no condition on click event");
            }
        }

        Cookies.set('sMenu', sMenu);
        //console.log("triggered: " + sMenu); // needs to be removed
        location.href = $(this).attr('href');
    }

    $("#top-header a, .top-header a, .delmar-logo a").on("click", function(e) {
        click_menus($(this));
    });

    $("#main-header").on("click", "#mobile_menu a", function(){
        //console.log("mobile item");
        click_menus($(this)); 
    })


    $(".logo_container img").on("click", function(e) {
        Cookies.set('sMenu', $(this).attr("class"));
    })

    $(".filter-buttons a").on("click", function(e){
        e.preventDefault();
        var formGV = $(this).attr("id");
        if( $(this).hasClass( "active" ) ) {
            $("." + formGV).removeClass("visible");
            $(this).removeClass('active');
        } else {
            $("." + formGV).addClass("visible");
            $(this).addClass('active');
        }
    });
    var allowtoLoad = true;
    $("#searchFilterButton").on("click", function(e){
        e.preventDefault();
        allowtoLoad = false;


        postType = $("input[name=post_type]").val();
        if(postType == "property") {
            $action = 'get_easy_property_list_ajax';
        } else {
            $action = 'get_availiability_search';
        }


        var data = $('.property-search-filters').serialize() + "&action=" + $action;
        $(".property-list-message").html('<span class="success">Searching for Properties...</span>');
        $(".property-list").empty();
        $(".loadmore .loading").hide();

        
        console.log(data);

        $.getJSON( get_property_search.ajaxurl, data, function( json ) {
            $(".message").html("").attr("class", '').addClass("message");
            if ( json.success ) {
                var json_data = json["data"];
                $(".property-list").empty();

                var counter = 0;
                $.each(json_data, function(i, item) {
                    $(".property-list").append(item).addClass("appended");
                    counter++;
                });
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(".property-list").offset().top - 100
                }, 2000);

                $(".property-list-message").html('<span class="success">We found ' + counter +' properties based on your search filter.</span>')
                
                $(".loadmore .loading").show();
                allowtoLoad = true;
                button.data("current-page", 1);
                button.data("search-filter", true);

                console.log(json_data);

            } else {
                console.log(json.data);
                allowtoLoad = false;
                $(".loadmore .loading").hide();
                $(".property-list").empty();
                $(".property-list-message").html('<span class="error">Sorry, we couldn\'t find any property within your search criteria. <br> Please try again using different search filter.</span>')
            }

        } );
    });
    $(".minPrice").on("change", function(){
        var minPrice = parseInt($(this).val());
        $('.maxPrice option').attr("disabled", false);
        
        var maxPrice = 0;
        $(".maxPrice option").each(function() {
            maxPrice = parseInt($(this).val());
            if( maxPrice <= minPrice) {
                $(this).attr('disabled', true);
            }
        });

        var currentMaxPrice = $(".maxPrice option[selected]").val();
        console.log(currentMaxPrice)
        if(minPrice < currentMaxPrice ) {

        } else {
            $('.maxPrice option:not([disabled]):first').attr("selected", true)    
        }
        
    });

    function getRentalReserved() {
        return ['10/30/2019','10/31/2019'];
    }

    $('.arrival_selector').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        isInvalidDate: function(date) {
            var formatted = date.format('MM/DD/YYYY');

            return getRentalReserved().indexOf(formatted) > -1;
        },
        minDate: moment().format('MM/DD/YYYY')
    }, function(ev, picker) {
        //#TODO add +1 day to departure base on selected arrival date
        $('.departure_selector').val( moment(picker.startDate).add(1, 'days').format('MM/DD/YYYY') )
    });

    
    var departure_next_day = moment().add(1, 'days');
    $('.departure_selector').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: departure_next_day.format('MM/DD/YYYY')
    });

    
    $('.properties_loadmore').on( "click", function(e){
        e.preventDefault();
        

        button = $(this);
        buttonParent = $(this).parent();

        currentPage = button.data("current-page");
        maxPage = button.data("max-page");
        postType = button.data("post-type");
        button.data("current-page", currentPage + 1);

        if(postType == "property") {
            $action = 'get_easy_property_list_ajax';
        } else {
            $action = 'get_availiability_search';
        }


        if( button.data("search-filter") == false ) {
           var data = "action=" + $action + "&paged=" + button.data("current-page") + "&post_type=" + postType;
        } else {
            var data = $('.property-search-filters').serialize() + "&action=" + $action + "&paged=" + button.data("current-page");
        }

        console.log(data);

        buttonParent.hide();
        $(".loadmore .loading").show();
        
        allowtoLoad = false;
        if(maxPage <= currentPage) {
            $(".loadmore .loading").show().addClass("success");
            $(".loadmore .message").html("All Results Displayed");
            $(".loadmore img").hide();
            allowtoLoad = false
        } else {
            $(".loadmore .message").html("Loading more properties...");
            $.getJSON( get_property_search.ajaxurl, data, function( json ) {
                $(".message").html("").attr("class", '').addClass("message");
                if ( json.success ) {
                    var json_data = json["data"];

                    var counter = 0;
                    $.each(json_data, function(i, item) {
                        $(".property-list").append(item).addClass("appended");
                        counter++;
                    });
                    buttonParent.show();
                    $(".loadmore .loading").hide();

                    if(counter == 10){
                        allowtoLoad = true;
                    } else {
                        allowtoLoad = false;
                    }
                } else {
                    console.log(json.data);
                    $(".loadmore .loading").show().addClass("success");
                    $(".loadmore .message").html("All Results Displayed");
                    $(".loadmore img").hide();
                   allowtoLoad = false
                }

            } );
        } 
    });

    if( $('.property-list').length ) {
        $(window).on('scroll', function() { 
            if ($(window).scrollTop() >= $( 
              '.property-list').offset().top + $('.property-list'). 
                outerHeight() - window.innerHeight) { 

                if(allowtoLoad) {
                    $('.properties_loadmore').trigger( "click" );
                    allowtoLoad = false;
                }
            } 
        });
    }



});