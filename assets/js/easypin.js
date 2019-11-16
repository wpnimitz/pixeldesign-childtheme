jQuery(document).ready(function( $ ) {
    var newPinMarker = $(".mapper-defaults .pin-marker").attr("src");
    var mapCanvas = $(".mapper-defaults .map-canvas").attr("src");


    function renderMarker(img) {
        var coor = $(img).find("img").data("coor");
        var coord = coor.split(", ");
        var $top = coord[0];
        var $left = coord[1];

        var initial_iW = coord[2];
        var initial_iH = coord[3];

        //lets get the actual image on user render
        var rendered_image_width = $(".rental_mapper img").width();
        var rendered_image_height = $(".rental_mapper img").height();

        //gets get the difference from the original render to current image display
        var res_percentage = rendered_image_width / initial_iW;
        var res_marker = (30 / initial_iW) * rendered_image_width;

        var newLeft = $top * res_percentage;
        var newTop  = $left * res_percentage;

        $(img).css("width", res_marker.toFixed(2) + "px").css("position", "absolute").css("left", newLeft.toFixed(2) + "px").css("top", newTop.toFixed(2) + "px").show();
        $(img).parent().css("position", "relative");
    }

    if( $(".rental_mapper marker").length ) {
        renderMarker(".rental_mapper marker");
    }

    $(".rental_mapper .drag").draggable({
    	containment: "parent",
    	stop: function(e) {
    		var parentOffset = $(this).parent().offset();
        	var iW = $(".rental_mapper .pin").width();
        	var iH = $(".rental_mapper .pin").height();
	        var Stoppos = $(this).position();
	        $(".rental_coordinates").val( Stoppos.left + ", " + Stoppos.top + ", " + iW + ", " + iH);
  		}
    });
    $(window).on('resize', function(){
        renderMarker(".rental_mapper marker");
    });

    $(".rm_controller span").on("click", function(){
        $this = $(this).html();
        $rmImg = $(".rental_mapper > img");

        var maxPress = 0;        

        if($this == "+" && maxPress != 5) {
            $rmImg
            .css("max-width", "initial")
            .css("width", $rmImg.width() * 1.25)

            maxPress++;
        } else {
            $rmImg
            .css("max-width", "initial")
            .css("width", $rmImg.width() - ($rmImg.width() / 5))
            maxPress = maxPress - 1;
        }
        renderMarker(".rental_mapper marker");
    })

});