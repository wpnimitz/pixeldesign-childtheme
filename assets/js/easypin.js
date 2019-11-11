jQuery(document).ready(function( $ ) {
    var newPinMarker = $(".mapper-defaults .pin-marker").attr("src");
    var mapCanvas = $(".mapper-defaults .map-canvas").attr("src");


    $(".rental_mapper .pin").on("click", function(e){
        var parentOffset = $(this).parent().offset();
        console.log($(this).offset()); //if you really just want the current element's offset

        var relX = e.pageX - parentOffset.left;
        var relY = e.pageY - parentOffset.top; 

        $(".rental_coordinates").val(relX + ", " + relY)
    });

    $(".rental_mapper marker").each(function( index ) {
        var coor = $(this).find("img").data("coor");
        var coord = coor.split(", ");
        $(this).css("position", "absolute").css("left", coord[0] + "%").css("top", coord[1] + "%").css("width", "30px").show();
        $(this).parent().css("position", "relative");
    });

    $(".rental_mapper .drag").draggable({
    	containment: "parent",
    	stop: function(e) {
    		var parentOffset = $(this).parent().offset();
	        var relX = e.pageX - parentOffset.left + 15;
        	var relY = e.pageY - parentOffset.top + 21;
        	var iW = $(".rental_mapper .pin").width();
        	var iH = $(".rental_mapper .pin").height();


        	$(".rental_coordinates").val( ((relX / iW) * 100) + ", " + ((relY / iH) * 100));
        	// Show dropped position.
	        var Stoppos = $(this).position();
	        console.log("STOP: \nLeft: "+ Stoppos.left + "\nTop: " + Stoppos.top);
	        $(".rental_coordinates").val( ((Stoppos.left / iW) * 100) + ", " + ((Stoppos.top / iH) * 100));
  		}
    });

});
