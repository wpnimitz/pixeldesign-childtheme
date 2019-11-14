jQuery(document).ready(function( $ ) {   
    var unavailable_dates = [];
    var $rental_days = $("input[name=unavailable_rental_days]");

    if($rental_days.val() != "") {
        var get_unavailable_dates = $rental_days.val() + '';
        var keys = get_unavailable_dates.split(",");

        $.each( keys, function( key, value ) {
          unavailable_dates.push(value);
          //$(".display-unavailable").append("<span>" + moment(value, "MM/DD/YYYY").format('MM/DD/YYYY') + "</span>");
          //console.log(value);
        });
    }

    $('.unavailable-adder input').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        isInvalidDate: function(date) {
            var formatted = date.format('MM/DD/YYYY');

            return unavailable_dates.indexOf(formatted) > -1;
        },
        minDate: moment().format('MM/DD/YYYY')
    });

    $(".unavailable-adder").on("click", ".display-unavailable span", function(){
        var removeItem = $(this).html()

        unavailable_dates = jQuery.grep(unavailable_dates, function(value) {
          return value != removeItem;
        });

        $(this).remove();

        $rental_days.val(unavailable_dates);
        $rental_days.data("blocked", unavailable_dates);
    });

    $("body").on("click", ".daterangepicker td", function(){
        $(this).attr("class", '').addClass("off disabled");
    });

    $('.unavailable-adder input').on('apply.daterangepicker', function(ev, picker) {
        $(".display-unavailable").append("<span>" + picker.startDate.format('MM/DD/YYYY') + "</span>");
        unavailable_dates.push( picker.startDate.format('MM/DD/YYYY') );
        //$("input[name=unavailable_rental_days]").val(unavailable_dates);

        $rental_days.val(unavailable_dates);
        $rental_days.data("blocked", unavailable_dates);
        
    });

    /* when user edits the post but didn't add anything to blocked days,
    // the holder of blocked days get the current date for unknow reason
    // whishing that this line is a temporary fix for the bug I am facing 
    */
    $rental_days.val($rental_days.data("blocked"));

    $('.add_more_bedroom').on('click', function(){
        var main = $("#main-bedroom-wrapper").html();
        var tempHtml = main; 

        $(".extra-bedroom-details").append('<div class="main_bedroom_details"> ' + tempHtml + '</div>');

    });

    $("#rental-property-details-id").on('click', ".remove_bedroom", function(){
        $(this).closest(".main_bedroom_details").remove();
    });

    $(".capture_map").on("click", function(){
        html2canvas(document.querySelector(".rental_mapper")).then(canvas => {
            $(".rental_coordinates").val(canvas.toDataURL('image/jpeg'));
            console.log(canvas.toDataURL('image/jpeg'));
        });
    });

    
    

});