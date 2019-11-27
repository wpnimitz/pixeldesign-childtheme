<?php
// Creating a Real Estate Custom Post Type
add_action( 'init', 'real_estate_custom_post_type', 0 );
function real_estate_custom_post_type() {
	$labels = array(
		'name'                => __( 'Real Estate' ),
		'singular_name'       => __( 'Real Estate'),
		'menu_name'           => __( 'Real Estate'),
		'parent_item_colon'   => __( 'Parent Real Estate'),
		'all_items'           => __( 'All Real Estate'),
		'view_item'           => __( 'View Real Estate'),
		'add_new_item'        => __( 'Add New Property'),
		'add_new'             => __( 'Add New Property'),
		'edit_item'           => __( 'Edit Property'),
		'update_item'         => __( 'Update Property'),
		'search_items'        => __( 'Search Property'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'Real Estate'),
		'description'         => __( 'Del Mar Real Estate'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'author', 'thumbnail', 'revisions'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'rewrite' 				=> array('slug' => 'property'),
		'can_export'          => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
	);
	register_post_type( 'property', $args );
}

// Add the custom columns to the book post type:
add_filter( 'manage_property_posts_columns', 'set_custom_columns_properties' );
function set_custom_columns_properties($columns) {
    unset( $columns['author'] );
    $columns['rental_map'] = __( 'Has Map?', 'property' );
    //$columns['vrp_approved'] = __( 'VRP Sync', 'property' );
    //$columns['rental_summary'] = __( 'Property Summary', 'property' );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_property_posts_custom_column' , 'set_custom_columns_properties_render', 10, 2 );
function set_custom_columns_properties_render( $column, $post_id ) {
    switch ( $column ) {
        case 'rental_map' :
        	$rental_map = get_post_meta( $post_id, 'rental_coordinates', true );
        	
        	if($rental_map != "") {
        		echo "Yes";
        	} else {
        		echo "--";
        	}

            break;
        case 'vrp_approved' :
        	$vrp_assigned_id = get_post_meta( $post_id, 'vrp_assigned_id', true );
        	
        	if($vrp_assigned_id != "") {
        		echo "<span class='price'>" .$vrp_assigned_id . "</span>";
        	} else {
        		echo "--";
        	}

            break;
    }
}



//create a custom taxonomy name it property type for your properties 
add_action( 'init', 'real_esate_property_add_property_type', 0 );
function real_esate_property_add_property_type() {
	// Add new taxonomy, make it hierarchical like categories
	//first do the translations part for GUI 
	$labels = array(
		'name' => _x( 'Property Type', 'taxonomy general name' ),
		'singular_name' => _x( 'Property Type', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Property Type' ),
		'all_items' => __( 'All Property Type' ),
		'parent_item' => __( 'Parent Property Type' ),
		'parent_item_colon' => __( 'Parent Property Type:' ),
		'edit_item' => __( 'Edit Property Type' ), 
		'update_item' => __( 'Update Property Type' ),
		'add_new_item' => __( 'Add New Property Type' ),
		'new_item_name' => __( 'New Property Type Name' ),
		'menu_name' => __( 'Property Type' ),
	);    
 
	// Now register the taxonomy
	register_taxonomy('property-type',array('property'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => false,
		'meta_box_cb' => false,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'property-type' ),
	));
}


//create a custom taxonomy name it property communities for your properties 
add_action( 'init', 'real_esate_property_add_property_communities', 5 );
function real_esate_property_add_property_communities() {
	// Add new taxonomy, make it hierarchical like categories
	//first do the translations part for GUI 
	$labels = array(
		'name' => _x( 'Property Communities', 'taxonomy general name' ),
		'singular_name' => _x( 'Property Communities', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Property Communities' ),
		'all_items' => __( 'All Property Communities' ),
		'parent_item' => __( 'Parent Property Communities' ),
		'parent_item_colon' => __( 'Parent Property Communities:' ),
		'edit_item' => __( 'Edit Property Communities' ), 
		'update_item' => __( 'Update Property Communities' ),
		'add_new_item' => __( 'Add New Property Communities' ),
		'new_item_name' => __( 'New Property Communities Name' ),
		'menu_name' => __( 'Property Communities' ),
	);    
 
	// Now register the taxonomy
	register_taxonomy('property-communities',array('property'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => false,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'property-communities' ),
	));
}


//Register Meta Box
function real_estate_property_details() {
    add_meta_box( 
    	'rm-meta-box-id', 
    	esc_html__( 'Property Details', 'text-domain' ), 
    	'cepl_meta_box_callback', 
    	'property', 
    	'normal', 
    	'high'
    );
}
add_action( 'add_meta_boxes', 'real_estate_property_details');


//Add fields to meta box
function cepl_meta_box_callback( $meta_id ) {
	
	wp_nonce_field( 'rm_meta_box_callback_nonce', 'child_epl_meta_nonce' );

	echo '<div class="delmar-property-details">';
	echo '<h2 class="fullwidth">Property Information</h2>';

	echo '<div class="child-epl-options">';

	echo '<h3>Assign a Slider</h3>';
	$property_assigned_slider = get_post_meta( $meta_id->ID, 'property_assigned_slider', true );
	$results = get_smart_sliders();

	echo '<select name="property_assigned_slider">';
		foreach($results as $result) {
    		echo '<option value="'.$result->id.'"'.if_selected_option($result->id,$property_assigned_slider).'>'.$result->title.'</option>';
		}
	echo '</select>';


	echo '<h3>Community</h3>';
	$property_individual_title = get_post_meta( $meta_id->ID, 'property_individual_title', true );
	echo '<input type="text" name="property_individual_title" placeholder="Enter your title" value="'.  $property_individual_title .'">';


	echo '<h3 style="margin-bottom: -26px; ">Property Description</h3>';
    $property_description = get_post_meta( $meta_id->ID, 'property_description', true );
    wp_editor(
    	$property_description,
    	'property-description',
    		array(
                'wpautop'       =>      true,
                'media_buttons' =>      false,
                'textarea_name' =>      'property_description',
                'textarea_rows' =>      10,
                'teeny'         =>      true
        	)
    );

    echo '<h3>Property Status</h3>';
	$property_status = get_post_meta( $meta_id->ID, 'property_status', true );
	echo '<select name="property_status">';
	echo '<option value="new" '.if_selected_option("new",$property_status).'> Available  </option>';
	echo '<option value="sold" '.if_selected_option("sold",$property_status).'> Sold </option>';
	echo '</select>';



    echo '</div>';
    echo '<div class="child-epl-options">';



    echo '<h3>Indoor Living Space in Sq Ft.</h3>';
	$property_indoor_space = get_post_meta( $meta_id->ID, 'property_indoor_space', true );
	echo '<input type="text" step="1" name="property_indoor_space" placeholder="Enter indoor living space in Sq Ft." value="'.  $property_indoor_space .'">';

	echo '<h3>Outdoor Living Space in Sq Ft.</h3>';
	$property_outdoor_space = get_post_meta( $meta_id->ID, 'property_outdoor_space', true );
	echo '<input type="text" step="1" name="property_outdoor_space" placeholder="Enter outdoor living space in Sq Ft." value="'.  $property_outdoor_space .'">';

	echo '<h3>Bedrooms</h3>';
	$property_bedrooms = get_post_meta( $meta_id->ID, 'property_bedrooms', true );
	echo '<input type="text" step="1" name="property_bedrooms" placeholder="How many bedrooms?" value="'.  $property_bedrooms .'">';

	echo '<h3>Bathrooms</h3>';
	$property_bathrooms = get_post_meta( $meta_id->ID, 'property_bathrooms', true );
	echo '<input type="text" step="1" name="property_bathrooms" placeholder="How many bathrooms?" value="'.  $property_bathrooms .'">';

    echo '<h3>Half-baths</h3>';
	$property_powder_room = get_post_meta( $meta_id->ID, 'property_powder_room', true );
	echo '<input type="text" step="1" name="property_powder_room" placeholder="How many half-baths?" value="'.  $property_powder_room .'">';

	echo '<h3>Garage Stalls</h3>';
	$property_garage_stalls = get_post_meta( $meta_id->ID, 'property_garage_stalls', true );
	echo '<input type="text" step="1" name="property_garage_stalls" placeholder="How many garage stalls?" value="'.  $property_garage_stalls .'">';

	echo '<h3>Is this property fully furnished?</h3>';
	$property_fully_furnished = get_post_meta( $meta_id->ID, 'property_fully_furnished', true );
	echo '<select name="property_fully_furnished">';
	echo '<option value="Fully Furnished" '.if_selected_option("Fully Furnished",$property_fully_furnished).'>Fully Furnished</option>';
	echo '<option value="No" '.if_selected_option("No",$property_fully_furnished).'>No</option>';
	echo '<option value="Lot Only" '.if_selected_option("Lot Only",$property_fully_furnished).'>Lot Only</option>';
	echo '</select>';
	

	echo '<h3>Lot Only Area</h3>';
	$lot_only_area = get_post_meta( $meta_id->ID, 'lot_only_area', true );
	echo '<input type="text" name="lot_only_area" placeholder="Enter indoor living space in Sq Ft." value="'.  $lot_only_area .'">';
	echo '</div>';


	echo '<h2 class="fullwidth additional-heading">Property Pricing</h2>';
	echo '<div class="child-epl-options">';
	echo '<h3>Property Price</h3>';
	$property_price = get_post_meta( $meta_id->ID, 'property_price', true );
	echo '<input type="number" step="1" name="property_price" value="'.  $property_price .'">';
	echo '</div>';
	

	echo '<div class="child-epl-options">';
	echo '</div>';


	echo '<h2 class="fullwidth additional-heading">Search Dropdown: Type</h2>';
	echo '<div class="child-epl-options checkbox-options">';

	$terms = get_terms( array(
	    'taxonomy' => 'property-type',
	    'hide_empty' => false,
	) );


	if(!empty($terms)) {
		foreach ($terms as $term) {
			$view_name = "type_" . $term->term_id;

			$term_view = get_post_meta( $meta_id->ID, $view_name, true );
			$checked = "";
			if(!empty($term_view)) {
				$checked = "checked";
			}
			echo '<div class="checkbox-group"><input type="checkbox" name="tax_property_type['. $view_name  .']" value="'. $term->name .'" '.$checked.'>';
			echo '<label for="' . $view_name . '">' . $term->name . '</label></div>';
		}
	} else {
		echo 'No available "type". Please add one in Real Estate -> Property Type.';
	}
	

	echo '</div>';

	echo '<h2 class="fullwidth additional-heading">Additional Details</h2>';
	echo '<div class="child-epl-options">';
	echo '<h3 style="margin-bottom: -26px; ">Property Features</h3>';
    $property_text_features = get_post_meta( $meta_id->ID, 'property_text_features', true );
    wp_editor(
    	$property_text_features,
    	'property_text_features',
    		array(
                'wpautop'       =>      true,
                'media_buttons' =>      false,
                'textarea_name' =>      'property_text_features',
                'textarea_rows' =>      10,
                'teeny'         =>      true
        	)
    );
	echo '</div>';
	

	echo '<div class="child-epl-options">';
	echo '<h3 style="margin-bottom: -26px; ">Property Community</h3>';
    $property_text_community = get_post_meta( $meta_id->ID, 'property_text_community', true );
    wp_editor(
    	$property_text_community,
    	'property_text_community',
    		array(
                'wpautop'       =>      true,
                'media_buttons' =>      false,
                'textarea_name' =>      'property_text_community',
                'textarea_rows' =>      10,
                'teeny'         =>      true
        	)
    );
	echo '</div>';





	echo '<h2 class="fullwidth additional-heading">Map Pin Mapper</h2>';

	echo '<div class="mapper-defaults" style="display:none;">';
		echo '<img src="'. get_stylesheet_directory_uri() .'/assets/svg/MapPointer.svg" class="pin-marker">';
		echo '<img src="'. get_stylesheet_directory_uri() .'/assets/img/delmar_map.jpg" class="map-canvass">';
	echo '</div>';

	$rental_coordinates = get_post_meta( $meta_id->ID, 'rental_coordinates', true );

	if($rental_coordinates == "") {
		$rental_coordinates = "30, 30";
	}
	echo '<div class="rental_mapper">';

		echo '<img src="'. get_stylesheet_directory_uri() .'/assets/img/delmar_map.jpg" width="800" class="pin" easypin-id="rental_map">';
		echo '<marker style="position: absolute; top: 30px; left: 30px; width: 30px;" class="drag"><img data-coor="'. $rental_coordinates. '" src="'. get_stylesheet_directory_uri() .'/assets/svg/MapPointer.svg" class="map-canvass"></marker>';

		
	echo '</div>'; //end rental_mapper


	
	echo '<input type="text" name="rental_coordinates" value="'. $rental_coordinates. '" data-value="'. $rental_coordinates. '" class="rental_coordinates" required>';

	echo '<div id="rental_coordinates"></div>';


	echo '</div>'; //delmar property details


}


//save meta box
function cepl_meta_box_save_meta( $post_id ) {
  if( !isset( $_POST['child_epl_meta_nonce'] ) || !wp_verify_nonce( $_POST['child_epl_meta_nonce'],'rm_meta_box_callback_nonce') ) 
    return;

  if ( !current_user_can( 'edit_post', $post_id ))
    return;

  
  if ( isset($_POST['property_assigned_slider']) ) {        
    update_post_meta($post_id, 'property_assigned_slider', sanitize_text_field($_POST['property_assigned_slider']));      
  }
  if ( isset($_POST['property_individual_title']) ) {        
    update_post_meta($post_id, 'property_individual_title', sanitize_text_field($_POST['property_individual_title']));      
  }
  if ( isset($_POST['property_description']) ) {        
    update_post_meta($post_id, 'property_description', $_POST['property_description']);      
  }
  if ( isset($_POST['property_status']) ) {        
    update_post_meta($post_id, 'property_status', $_POST['property_status']);      
  }


  if ( isset($_POST['property_indoor_space']) ) {        
    update_post_meta($post_id, 'property_indoor_space', sanitize_text_field($_POST['property_indoor_space']));      
  }
  if ( isset($_POST['property_outdoor_space']) ) {        
    update_post_meta($post_id, 'property_outdoor_space', sanitize_text_field($_POST['property_outdoor_space']));      
  }
  if ( isset($_POST['property_bathrooms']) ) {        
    update_post_meta($post_id, 'property_bathrooms', sanitize_text_field($_POST['property_bathrooms']));      
  }
  if ( isset($_POST['property_bedrooms']) ) {        
    update_post_meta($post_id, 'property_bedrooms', sanitize_text_field($_POST['property_bedrooms']));      
  }
  if ( isset($_POST['property_powder_room']) ) {        
    update_post_meta($post_id, 'property_powder_room', sanitize_text_field($_POST['property_powder_room']));      
  }
  if ( isset($_POST['property_garage_stalls']) ) {        
    update_post_meta($post_id, 'property_garage_stalls', sanitize_text_field($_POST['property_garage_stalls']));      
  }
  if ( isset($_POST['property_fully_furnished']) ) {        
    update_post_meta($post_id, 'property_fully_furnished', sanitize_text_field($_POST['property_fully_furnished']));      
  }

  if ( isset($_POST['property_price']) ) {        
    update_post_meta($post_id, 'property_price', sanitize_text_field($_POST['property_price']));      
    update_post_meta($post_id, 'property_price_display', "yes");      
  }

  if ( isset($_POST['tax_property_type']) ) { 
  	$tax_property_type = $_POST['tax_property_type'];

  	foreach ($tax_property_type as $key => $value) {
  		update_post_meta($post_id, $key, $value); 
  	}    
  }

  if ( isset($_POST['property_text_features']) ) {        
    update_post_meta($post_id, 'property_text_features', $_POST['property_text_features']);      
  }
  if ( isset($_POST['property_text_community']) ) {        
    update_post_meta($post_id, 'property_text_community', $_POST['property_text_community']);      
  }
  if ( isset($_POST['rental_coordinates']) ) {        
    update_post_meta($post_id, 'rental_coordinates', $_POST['rental_coordinates']);      
  }

}
add_action('save_post', 'cepl_meta_box_save_meta');

function get_easy_property_info_child_theme( $atts ){
	$atts = shortcode_atts(
		array(
			'display' => 'content',
			'type' => 'string',
			'format' => 'string', //string or number,
			'limit' => 2 // for format number
		), $atts, 'easy_property' );

	$post_id = get_the_ID();
	$display = $atts["display"];		
	$type = $atts["type"];		
	$format = $atts["format"];
	$limit = $atts["limit"];

	$meta = get_post_meta( $post_id, $display, true);

	$meta_default = array(
		'page_title' => 'current page title',
		'property_price_view' => '$5,750,000',
		'property_category' => 'Fully Furnished',
		'property_description' => 'Edit the property description to remove this section text. Lorem sitas que re, is am faces inulpa sequiat eatiorio. Rores sus cum apictint exero dolore, ut omni cum endeliquid ut quam fuga. Atur, odipsum fuga. Natibus auda volores et plantem reptae quas et eos ab iunt quam elis doles sit esedis volupti orrumendit volorio nsectatin cust aliquis endit que voles re nos ius, ommolupta si doluptat Moluptat. Estrum aut excerci liquae pro temod magnis et eos remporpos re res is Imusam consequatus. Ehendis nis illanime que verepe deligen estianis mossintio tor aut voles eost voluptia quaspelestet molorep erupictatus, quuntec aborro et vollatur, quunt estios int et quia commoss itamet eat facil id ullut.',
		'property_indoor_space' => '0',
		'property_building_area_unit' => 'SQ FT INDOOR LIVING SPACE',
		'property_bedrooms' => '0',
		'property_bathrooms' => '0',
		'property_powder_room' => '0',
		'property_garage_stalls' => '0'
	);

	if($display == "property_fully_furnished") {
		if($meta != "Fully Furnished") {
			return '';
		}
	}

	if($display == "property_indoor_space") {
		if($meta > 0) {
			return number_format($meta);
		} else {
			return '0';
		}
	}


	if( !empty($meta) ) {
		if($format == "number") {
			$ret = number_format($meta,$limit);
		} else {
			$ret = '<span class="'. $display .'">' . $meta . '</span>';
		}
	} else {
		if(isset($meta_default[$display])){
			$ret = $meta_default[$display];
		} else {
			$ret = "unavailable";
		}
	}
	
	
	return $ret;

}
add_shortcode( 'easy_property', 'get_easy_property_info_child_theme' );



function get_easy_property_search_filters( $atts ){

	$ret = '<div class="property-search-wrapper">';
	$ret .= '<form class="property-search-filters">';
	$ret .= '<input name="post_type" value="property" type="hidden">';
	$ret .= '<div class="et_pb_row visible">';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Minimum Price</label>';
	$ret .= '<select name="minPrice" class="minPrice">';
	$ret .= '<option value="500000">$500,000</option>';
	$ret .= '<option value="1000000">$1,000,000</option>';
	$ret .= '<option value="2500000">$2,500,000</option>';
	$ret .= '<option value="5000000">$5,000,000</option>';
	$ret .= '<option value="10000000">$10,000,000</option>';
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Maximum Price</label>';
	$ret .= '<select name="maxPrice" class="maxPrice">';
	$ret .= '<option value="1000000">$1,000,000</option>';
	$ret .= '<option value="2500000">$2,500,000</option>';
	$ret .= '<option value="5000000">$5,000,000</option>';
	$ret .= '<option value="10000000">$10,000,000</option>';
	$ret .= '<option value="2000000000" selected>$20,000,000+</option>';
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '</div>';//et_pb_row

	$ret .= '<div class="et_pb_row visible">';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Type</label>';
	$ret .= '<select name="property_type">';
	$ret .= '<option value="">Any</option>';
	$terms = get_terms( array( 'taxonomy' => 'property-type', 'hide_empty' => false ) );
	foreach($terms as $term) {
      $ret .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
    }
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Bedrooms</label>';
	$ret .= '<select name="bedrooms">';
	$ret .= '<option value="0">All</option>';
	for ($i=2; $i < 11; $i++) { 
		$ret .= '<option value="'.$i.'">'.$i.'</option>';
	}
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '</div>'; //et_pb_row

		$ret .= '<div class="et_pb_row visible">';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Listing Status</label>';
	$ret .= '<select name="listingOptions">';
	$ret .= '<option value="new">Available</option>';
	$ret .= '<option value="sold">Sold</option>';
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Sort Order</label>';
	$ret .= '<select name="sortOrder">';
	$ret .= '<option value="hightolow">High to Low</option>';
	$ret .= '<option value="lowtohigh">Low to High</option>';
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '</div>';
	$ret .= '</form>';

	$ret .= '<div class="et_pb_button_alignment_center" style="padding-top:30px;text-align: center;"> <a id="searchFilterButton" class="amenityButton" href="#">Search</a> </div>';
	$ret .= '</div>'; //property-search-wrapper



	return $ret;

}
add_shortcode( 'epl_search_filters', 'get_easy_property_search_filters' );