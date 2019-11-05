<?php
require_once("database.php");

// Creating a Rental Custom Post Type
add_action( 'init', 'rental_custom_post_type', 0 );
function rental_custom_post_type() {
	$labels = array(
		'name'                => __( 'Rental' ),
		'singular_name'       => __( 'Rental'),
		'menu_name'           => __( 'Rental'),
		'parent_item_colon'   => __( 'Parent Rental'),
		'all_items'           => __( 'All Rental'),
		'view_item'           => __( 'View Rental'),
		'add_new_item'        => __( 'Add New Rental Property'),
		'add_new'             => __( 'Add New Property'),
		'edit_item'           => __( 'Edit Property'),
		'update_item'         => __( 'Update Property'),
		'search_items'        => __( 'Search Property'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'Rental'),
		'description'         => __( 'Del Mar Rental'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'author', 'thumbnail', 'revisions'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'rewrite' 				=> array('slug' => 'rental'),
		'can_export'          => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
	);
	register_post_type( 'rental', $args );
}

// Add the custom columns to the book post type:
add_filter( 'manage_rental_posts_columns', 'set_custom_rental_blocked_days' );
function set_custom_rental_blocked_days($columns) {
    unset( $columns['author'] );
    $columns['blocked_days'] = __( 'Blocked Days', 'rental' );
    $columns['rental_summary'] = __( 'Property Summary', 'rental' );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_rental_posts_custom_column' , 'custom_blocked_days', 10, 2 );
function custom_blocked_days( $column, $post_id ) {
    switch ( $column ) {
        case 'blocked_days' :
            $unavailable_rental_days = get_post_meta( $post_id, 'unavailable_rental_days', true );
        	$blocked_days = explode(",", $unavailable_rental_days);

        	$counter = 0;
        	echo '<div class="rental-days count-'. count($blocked_days) .'">';
        	for ($i=0; $i < count($blocked_days); $i++) { 
				
        		$date = new DateTime($blocked_days[$i]);
				$now = new DateTime();

				if($date > $now) {
				    $new_date = date("M. j", strtotime($blocked_days[$i]));
        			echo '<span class="">' .$new_date. '</span>';
        			$counter++;
				}
        	}
        	echo '</div>';
            break;
        case 'rental_summary' :
        	echo '<div class="rental-summary">';

        	$min_night = get_post_meta( $post_id, 'property_price', true );
        	$min_night = $min_night == "" ? "0" : $min_night;
        	$max_night = get_post_meta( $post_id, 'property_price_max', true );
        	$max_night = $max_night == "" ? "0" : $max_night;
        	echo "<span class='price'>$" . number_format($min_night) . " - $" . number_format($max_night) . "</span>";

        	$bedrooms = get_post_meta( $post_id, 'property_bedrooms', true );
        	$numbeds = get_post_meta( $post_id, 'property_beds', true );
        	echo '<span>' . $bedrooms . ' rooms</span>';
        	echo '<span>' . $numbeds . ' beds</span>';

        	$terms = get_terms( array(
			    'taxonomy' => 'rental-views',
			    'hide_empty' => false,
			) );

			echo '<span class="space"></span>';


			if(!empty($terms)) {
				foreach ($terms as $term) {
					$view_name = "views_" . $term->term_id;

					$check_term = get_post_meta( $post_id, $view_name, true );

					if($check_term) {
						echo '<span>' . $term->name . '</span>';
					}
				}
			} 


        	echo '</div>';
        	break;
    }
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}


//create a custom taxonomy name it Views for your properties 
add_action( 'init', 'rental_cpt_add_views', 0 );
function rental_cpt_add_views() {
	// Add new taxonomy, make it hierarchical like categories
	//first do the translations part for GUI 
	$labels = array(
		'name' => _x( 'Views', 'taxonomy general name' ),
		'singular_name' => _x( 'Views', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Views' ),
		'all_items' => __( 'All Views' ),
		'parent_item' => __( 'Parent Views' ),
		'parent_item_colon' => __( 'Parent Views:' ),
		'edit_item' => __( 'Edit Views' ), 
		'update_item' => __( 'Update Views' ),
		'add_new_item' => __( 'Add New Views' ),
		'new_item_name' => __( 'New Views Name' ),
		'menu_name' => __( 'Views' ),
	);    
 
	// Now register the taxonomy
	register_taxonomy('rental-views',array('rental'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => false,
		'meta_box_cb' => false,
		'query_var' => true,		
		'rewrite' => array( 'slug' => 'rental-views' ),
	));
}






function rental_filtered_data( $atts ){

	$ret = '<div class="property-search-wrapper">';
	$ret .= '<form class="property-search-filters">';
	$ret .= '<input name="post_type" value="rental" type="hidden">';
	$ret .= '<div class="et_pb_row visible">';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Arrival</label>';
	$ret .= '<input type="text" name="arrival" class="arrival_selector">';
	$ret .= '</div>';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Departure</label>';
	$ret .= '<input type="text" name="departure" class="departure_selector">';
	$ret .= '</div>';

	$ret .= '</div>';//et_pb_row

	$ret .= '<div class="et_pb_row visible">';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>View</label>';
	$ret .= '<select name="rental_views">';
	$ret .= '<option value="">All</option>';
	$terms = get_terms( array( 'taxonomy' => 'rental-views', 'hide_empty' => false ) );

	foreach($terms as $term) {
      $ret .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
    }
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '<div class="et_pb_column et_pb_column_1_2">';
	$ret .= '<label>Bedrooms</label>';
	$ret .= '<select name="bedrooms">';
	for ($i=2; $i < 11; $i++) { 
		$ret .= '<option value="'.$i.'">'.$i.'</option>';
	}
	$ret .= '</select>';
	$ret .= '</div>';
	$ret .= '</div>';


	$ret .= '<div class="et_pb_row visible">';

	$ret .= '<div class="et_pb_column et_pb_column_4_4">';
	$ret .= '<label>Number of Beds</label>';
	$ret .= '<select name="num_bedrooms">';
	for ($i=2; $i < 13; $i++) { 
		$ret .= '<option value="'.$i.'">'.$i.'</option>';
	}
	$ret .= '</select>';
	$ret .= '</div>';

	$ret .= '</div>'; //et_pb_row
	$ret .= '</form>';

	$ret .= '<div class="et_pb_button_alignment_center" style="padding-top:30px;text-align: center;"> <a id="searchFilterButton" class="amenityButton" href="#">Search</a> </div>';
	$ret .= '</div>'; //property-search-wrapper

	return $ret;

}
add_shortcode( 'rental_search_filters', 'rental_filtered_data' );


function rental_assets() {
	$version = strtotime("now");
    wp_enqueue_style( 'datepicker-style', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css?version=1.0.' . $version );
	wp_enqueue_script( 'moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', array(), $version, true);
	wp_enqueue_script( 'daterangepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array(), $version, true);
}
add_action( 'wp_enqueue_scripts', 'rental_assets' );
add_action( 'admin_enqueue_scripts', 'rental_assets' );



//Register Meta Box
function rental_property_details() {
    add_meta_box( 
    	'rental-property-details-id', 
    	esc_html__( 'Property Details', 'text-domain' ), 
    	'rental_metabox_callback', 
    	'rental', 
    	'normal', 
    	'high'
    );
}
add_action( 'add_meta_boxes', 'rental_property_details');


//Add fields to meta box
function rental_metabox_callback( $meta_id ) {
	
	wp_nonce_field( 'rental_metabox_callback', 'rental_meta_nonce' );

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
    			'media_buttons' =>   	false,
    			'quicktags'     => array("buttons"=>"link,img,close"),
                'wpautop'       =>      true,
                'textarea_name' =>      'property_description',
                'textarea_rows' =>      10,
                'tinymce'       => 		false,
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



	echo '<h3>Indoor Living Space in Sq. Ft.</h3>';
	$property_indoor_space = get_post_meta( $meta_id->ID, 'property_indoor_space', true );
	echo '<input type="number" step="1" name="property_indoor_space" placeholder="Enter indoor living space in sq. ft." value="'.  $property_indoor_space .'">';

	echo '<h3>Outdoor Living Space in Sq. Ft.</h3>';
	$property_outdoor_space = get_post_meta( $meta_id->ID, 'property_outdoor_space', true );
	echo '<input type="number" step="1" name="property_outdoor_space" placeholder="Enter outdoor living in sq. ft." value="'.  $property_outdoor_space .'">';

	echo '<h3>Number of Bedrooms</h3>';
	$property_bedrooms = get_post_meta( $meta_id->ID, 'property_bedrooms', true );
	echo '<input type="number" step="1" name="property_bedrooms" placeholder="How many bedrooms?" value="'.  $property_bedrooms .'">';

	echo '<h3>Number of Beds</h3>';
	$property_beds = get_post_meta( $meta_id->ID, 'property_beds', true );
	echo '<input type="number" step="1" name="property_beds" placeholder="How many beds?" value="'.  $property_beds .'">';

	echo '<h3>Number of Bathrooms</h3>';
	$property_bathrooms = get_post_meta( $meta_id->ID, 'property_bathrooms', true );
	echo '<input type="number" step="1" name="property_bathrooms" placeholder="How many bathrooms?" value="'.  $property_bathrooms .'">';

    echo '<h3>Number of Powder Rooms</h3>';
	$property_powder_room = get_post_meta( $meta_id->ID, 'property_powder_room', true );
	echo '<input type="number" step="1" name="property_powder_room" placeholder="How many powder rooms?" value="'.  $property_powder_room .'">';

	echo '<h3>Number of Garage Stalls</h3>';
	$property_garage_stalls = get_post_meta( $meta_id->ID, 'property_garage_stalls', true );
	echo '<input type="number" step="1" name="property_garage_stalls" placeholder="How many garage stalls?" value="'.  $property_garage_stalls .'">';
	echo '</div>';


	echo '<h2 class="fullwidth additional-heading">Rental Pricing</h2>';
	echo '<div class="child-epl-options">';
	echo '<h3>Min. Per Night Price</h3>';
	$property_price = get_post_meta( $meta_id->ID, 'property_price', true );
	echo '<input type="number" step="1" name="property_price" value="'.  $property_price .'">';
	echo '</div>';

	echo '<div class="child-epl-options">';
	echo '<h3>Max. Per Night Price</h3>';
	$property_price_max = get_post_meta( $meta_id->ID, 'property_price_max', true );
	echo '<input type="number" step="1" name="property_price_max" value="'.  $property_price_max .'">';
	echo '</div>';

	echo '<h2 class="fullwidth additional-heading">Search Dropdown: Views</h2>';
	echo '<div class="child-epl-options checkbox-options">';

	$terms = get_terms( array(
	    'taxonomy' => 'rental-views',
	    'hide_empty' => false,
	) );


	if(!empty($terms)) {
		foreach ($terms as $term) {
			$view_name = "views_" . $term->term_id;

			$term_view = get_post_meta( $meta_id->ID, $view_name, true );
			$checked = "";
			if(!empty($term_view)) {
				$checked = "checked";
			}
			echo '<div class="checkbox-group"><input type="checkbox" name="rental_views['. $view_name  .']" value="'. $term->name .'" '.$checked.'>';
			echo '<label for="' . $view_name . '">' . $term->name . '</label></div>';
		}
	} else {
		echo 'No available "views". Please add one in Rental -> Views.';
	}
	

	echo '</div>';
	

	echo '<div class="child-epl-options">';
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

	echo '<div class="unavailable-adder">';
	echo '<h2 class="fullwidth additional-heading">Block Unavailable / Booked / Holiday Days</h2>';
	$unavailable_rental_days = get_post_meta( $meta_id->ID, 'unavailable_rental_days', true );
	echo '<input type="hidden" name="unavailable_rental_days" value="'.$unavailable_rental_days.'" data-blocked="'.$unavailable_rental_days.'">';
	//echo '<input type="hidden" name="unavailable_rental_days" value="">';

	echo '<h3>Blocked Days</h3>';
	echo '<div class="display-unavailable"></div>';
	echo '<h3>Select the date from the calendar below. Format: MM/DD/YYYY</h3><br />';
	echo '<div class="form-group"><input type="text" name="unavailable_adder" value=""></div>';
	echo '</div>';



	echo '<h2 class="fullwidth additional-heading">Bedroom Details <span class="add_more_bedroom">Add</span></h2>';

	$bedroom_label = get_post_meta( $meta_id->ID, 'bedroom_label', true );
	$bedroom_details = get_post_meta( $meta_id->ID, 'bedroom_details', true );

	echo '<div class="main_bedroom_details" id="main-bedroom-wrapper">';

		echo '<div class="child-epl-options">';
		echo "Bedroom Label: <br>";
		echo '<input name="bedroom_label[]" type="text" value="'.$bedroom_label[0].'">';
		echo '</div>';

		echo '<div class="child-epl-options">';
		echo "Bedroom Details: <br>";
		echo '<input name="bedroom_details[]" type="text" value="'.$bedroom_details[0].'">';
		echo '</div>'; 

		echo '<span class="remove_bedroom">Remove</span>';
	echo '</div>'; //main bedroom details

	echo '<div class="extra-bedroom-details">';

	for ($i=1; $i < count($bedroom_label); $i++) { 
		echo '<div class="main_bedroom_details" >';

			echo '<div class="child-epl-options">';
			echo "Bedroom Label: <br>";
			echo '<input name="bedroom_label[]" type="text" value="'.$bedroom_label[$i].'">';
			echo '</div>';

			echo '<div class="child-epl-options">';
			echo "Bedroom Details: <br>";
			echo '<input name="bedroom_details[]" type="text" value="'.$bedroom_details[$i].'">';
			echo '</div>'; 

			echo '<span class="remove_bedroom">Remove</span>';
		echo '</div>'; //main bedroom details
	}

	echo'</div>';



	echo '</div>'; //delmar property details
}


//save meta box
function rental_meta_box_save_metabox( $post_id ) {
  if( !isset( $_POST['rental_meta_nonce'] ) || !wp_verify_nonce( $_POST['rental_meta_nonce'],'rental_metabox_callback') ) 
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
  if ( isset($_POST['property_beds']) ) {        
    update_post_meta($post_id, 'property_beds', sanitize_text_field($_POST['property_beds']));      
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

  if ( isset($_POST['property_price_max']) ) {        
    update_post_meta($post_id, 'property_price_max', sanitize_text_field($_POST['property_price_max']));      
  }


  if ( isset($_POST['rental_views']) ) { 
  	$rental_views = $_POST['rental_views'];

  	foreach ($rental_views as $key => $value) {
  		update_post_meta($post_id, $key, $value); 
  	}    
  }

  if ( isset($_POST['property_text_features']) ) {        
    update_post_meta($post_id, 'property_text_features', $_POST['property_text_features']);      
  }
  if ( isset($_POST['property_text_community']) ) {        
    update_post_meta($post_id, 'property_text_community', $_POST['property_text_community']);      
  }
  if ( isset($_POST['unavailable_rental_days']) ) {

  	//for easy access and viewing in the editor
    update_post_meta($post_id, 'unavailable_rental_days', $_POST['unavailable_rental_days']);

    

    //for better availiability search function
    global $wpdb;
    $tablename = $wpdb->prefix.'rental_blocked_days';    
    //delete all previous blocked dates first


    //save all new blocked dates
    $blocked_days = explode(",", $_POST['unavailable_rental_days']);
    for ($i=0; $i < count($blocked_days); $i++) { 
    	$date = str_replace("/", "_", $blocked_days[$i]);

    	$wpdb->insert( 
    		$tablename, 
    		array(
            	'property_id' => $post_id,
            	'blocked_date' => date("Y-m-d", strtotime($blocked_days[$i])),
            	'user_id' => get_current_user_id()
        	)
    	);
    	$record_id = $wpdb->insert_id;
    }
  }
  if ( isset($_POST['bedroom_label']) ) {        
    update_post_meta($post_id, 'bedroom_label', $_POST['bedroom_label']);      
  }

  if ( isset($_POST['bedroom_details']) ) {        
    update_post_meta($post_id, 'bedroom_details', $_POST['bedroom_details']);      
  }
}
add_action('save_post', 'rental_meta_box_save_metabox');