<?php
function delmar_assets() {
	$version = strtotime("now");
    //wp_enqueue_script( 'js-mobile', 'http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js', array(), $version );
    wp_enqueue_script( 'js-cookie', 'https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js', array(), $version );
    wp_enqueue_script( 'child-epl-js', get_stylesheet_directory_uri() . '/plugins/child-epl-js.js?version=1.0.' . $version, array(), $version );
    wp_localize_script( 'child-epl-js', 'get_property_search', array(
    	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	) );	
}
add_action( 'wp_enqueue_scripts', 'delmar_assets' );

function delmar_admin_assets() {
   $version = strtotime("now");
    wp_enqueue_style( 'delmar-admin-style', get_stylesheet_directory_uri() . '/plugins/delmar-admin-style.css?version=1.0.' . $version );
    wp_enqueue_script( 'delmar-admin-script', get_stylesheet_directory_uri() . '/plugins/delmar-admin-script.js?version=1.0.' . $version, array(), $version );
}
add_action( 'admin_enqueue_scripts', 'delmar_admin_assets' );

add_filter( 'body_class', 'common_body_class_post_name' );
function common_body_class_post_name( $classes ) {
    if ( is_page() ) {
    	global $post;
        $classes[] = $post->post_name;
    }
    return $classes;
}

/*sadfasdfasdf*/
/****
ANY Small functions Goes here.
****/
function if_selected_option($value, $data) {
	if($value == $data) { 
		return ' selected';
	} else {
		return '';
	}
}

//get Smart Slider Pro
function get_smart_sliders() {
	global $wpdb;
	$results = $wpdb->get_results('SELECT id, title FROM ' . $wpdb->prefix . 'nextend2_smartslider3_sliders');

	return $results;
}




/****
SHORTCODES SECTION GOES HERE
****/
function get_delmar_properties( $atts ){
	$atts = shortcode_atts(
		array(
			'type' => 'property',
		), $atts );
	$type = $atts["type"];

	$args = array(
	  'post_type'   => $type,
	  'post_status' => 'publish',
	  'posts_per_page' => '10',
	);

	$ret = '';

	if($type == "property") {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = 'property_price';
		$args['order'] = 'DESC';
	}

	$ret .= '<div class="property-list-message"></div>';
	$ret .= '<div class="property-list">';

	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
	    $ret .= easy_property_blurb_extra(get_the_ID(), $type);
	endwhile;
	$ret .= '</div>';
	$ret .= '<div class="loadmore button"><a href="#" class="properties_loadmore amenityButton" data-post-type="'. $type .'" data-current-page="1" data-max-page="'.$loop->max_num_pages.'" data-search-filter="false">Load More</a></div>';
	$ret .= '<div class="loadmore spinner">
			<div class="loading" style="display:none;"><img src="'. get_stylesheet_directory_uri() .'/assets/img/spin.png"> <span class="message">Loading more properties...<span></div>
		</div>';
	return $ret;

	wp_reset_postdata();

}
add_shortcode( 'show_properties', 'get_delmar_properties' );


function easy_property_blurb_extra($post_id, $type = "property") { 
	$prop_title = get_the_title($post_id);
	$prop_price = get_post_meta( $post_id, 'property_price', true);
	$prop_price_max = get_post_meta( $post_id, 'property_price_max', true);
	$property_indoor_space = get_post_meta( $post_id, 'property_indoor_space', true);
	$prop_building_area_unit_text = 'Sq Ft Indoor Living Space';
	$prop_bedrooms = get_post_meta( $post_id, 'property_bedrooms', true);
	$prop_bathrooms = get_post_meta( $post_id, 'property_bathrooms', true);
	$prop_powderrooms = get_post_meta( $post_id, 'property_powder_room', true);
	$prop_garagestalls = get_post_meta( $post_id, 'property_garage_stalls', true);
	$prop_permalink = get_the_permalink( $post_id);

	$featured_image = get_the_post_thumbnail_url();
	if ( $featured_image == "" ) {
		$featured_image = get_stylesheet_directory_uri() . '/assets/img/no-featured.jpg';
	}

	if($prop_price == "") {
		$prop_price = 0;
	}

	if($prop_price_max == "") {
		$prop_price_max = 0;
	}


	$ret = '<div class="blurb-extra et_pb_blurb '. $type .'" style="background-image:url('.$featured_image.')">';

		$ret .= '<div class="et_pb_blurb_content">';
			$ret .= '<div class="et_pb_blurb_container">';

				
				if($type == 'property') {
					$ret .= '<h4 class="et_pb_module_header"><span class"prop-title">' . $prop_title . '</span> <span class="line"></span> <span class="prop-price">$' . number_format($prop_price) .  '</span><span class="propertyQuality"> Fully Furnished</span></h4>';
				} else {
					$ret .= '<h4 class="et_pb_module_header"><span class"prop-title">' . $prop_title . '</span></h4>';
				}


				$ret .= '<div class="et_pb_blurb_description">';
				if($type != 'property') {
					$ret .= '<h4 class="et_pb_module_header"><span class="line"></span> <span class="prop-price">$' . number_format($prop_price) .  ' - $' . number_format($prop_price_max) . '</span><span class="propertyQuality"> per Night</span></h4>';
				}

				$ret .= '<p>';

				$ret .= $property_indoor_space . ' ' . $prop_building_area_unit_text . '<br>';

				if($prop_bedrooms > 1) {
					$ret .= $prop_bedrooms . ' Bedrooms';
				} else {
					$ret .= $prop_bedrooms . ' Bedroom';
				}

				if($prop_bathrooms > 1) {
					$ret .= ' / ' . $prop_bathrooms . ' Bath Rooms';
				} else {
					$ret .= ' / ' . $prop_bathrooms . ' Bath Room';
				}

				
				if($prop_powderrooms > 1) {
					$ret .= ' / ' . $prop_powderrooms . ' Half Baths';
				} else {
					$ret .= ' / ' . $prop_powderrooms . ' Half Bath';
				}
				
				if($type == "property") {
					if($prop_garagestalls > 1) {
						$ret .= '<br>' . $prop_garagestalls . ' Garage Stalls';
					} else {
						$ret .= '<br>' . $prop_garagestalls . ' Garage Stall';
					}
				}
				
				$ret .= '</p>';
				$ret .= '<p><a href="'. $prop_permalink .'" class="amenityButton">View More</a></p>' ;
				$ret .= '</div>';
			$ret .= '</div>';
		$ret .= '</div>';

	$ret .= '</div>'; //extra-blurb

	return $ret;

}


add_action( 'wp_ajax_get_easy_property_list_ajax', 'get_easy_property_list_ajax' );
add_action( 'wp_ajax_nopriv_get_easy_property_list_ajax', 'get_easy_property_list_ajax' );

function get_easy_property_list_ajax() {
	extract($_REQUEST);

	$args = array(
	  'post_type'   => $post_type,
	  'posts_per_page' => '10'
	);

	// $args = array(
	// 	'post_type'   => $post_type,
	// 	'posts_per_page' => '10',
	// 	'paged' => $paged,
	// 	'meta_query' => array(
	// 	  	array(
	// 			'key' => $key1,
	// 			'value'   => $key1_value,
	// 	        'compare' => '>=',
	// 		),
	// 		array(
	// 			'key' => $key2,
	// 			'value'   => $key2_value,
	// 	        'compare' => '>=',
	// 		)
	// 		//<== the list may go one
	// 	),
	// 	'tax_query' => array(
	// 		'taxonomy' => $tax,
	// 		'field' => 'slug',
	// 		'terms' => array($tax_value) 
	// 	)
	// );

	
	
	//paged works on the continouos scroll
	if(isset($paged)) {
		$args['paged'] = $paged;
	}

	
	//both real estate properties and rental properties are using this cutom meta
	$bedrooms = $_REQUEST["bedrooms"];
	$args['meta_query'][] = array(
		'key' => 'property_bedrooms',
		'value'   => $bedrooms,
        'compare' => '>=',
	);


	if($post_type == "property") {
		//do property real estate
		$minPrice = $_REQUEST["minPrice"];
		$maxPrice = $_REQUEST["maxPrice"];
		
		$prop_status = $_REQUEST["listingOptions"];

		if( $minPrice == "") {
			$minPrice = 0;
		}
		if( $maxPrice == "") {
			$maxPrice = 200000000;
		}

		$args['meta_query'][] = array(
			'key' => 'property_price',
			'value'   => array( $minPrice, $maxPrice ),
	        'type'    => 'numeric',
	        'compare' => 'BETWEEN',
		);

		$args['meta_query'][] = array(
			'key' => 'property_status',
			'value'   => $prop_status,
	        'compare' => '=',
		);

		if(isset($sortOrder) && $sortOrder == 'lowtohigh') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'property_price';
			$args['order'] = 'ASC';
		} else {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'property_price';
			$args['order'] = 'DESC';
		}

	} else {
		if($num_bedrooms == "") {
			$num_bedrooms = 0;
		}
		// $args['meta_query']['relation'] = "AND";
		$args['meta_query'][] = array(
			'key' => 'property_beds',
			'value'   => $num_bedrooms,
	        'compare' => '>=',
		);

		//need to play with 
		//$arrival
		//$departure
		//unavailable_rental_days

		// Start date
		$date = strtotime($arrival);
		// End date
		$end_date = strtotime($departure);

		while (strtotime($date) <= strtotime($end_date)) {
			$args['meta_query'][] = array(
				'key' => 'date_blocked',
				'value'   =>  date("Y-m-d", strtotime($date)),
		        'compare' => '!=',
			);

			$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
		}




	}



	//let be creative using taxonomies
	//property_type use for real estate properties
	//rental_views use for rental properties

	if( isset($property_type) ) {
		if($property_type != "") {
			$args['meta_query'][] = array(
				'key' => 'type_' . $property_type
			);
		}
	}

	if( isset($rental_views) ) {
		if( !empty($rental_views) ) {
			$args['meta_query'][] = array(
				'key' => 'views_' . $rental_views
			);
		}
	}

	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
	    $success[] = easy_property_blurb_extra(get_the_ID(), $post_type);
	endwhile;


	if( $loop->have_posts() ) {
		wp_send_json_success( $success );
	} else {
		wp_send_json_error( "Something went wrong! Please try again later. If the issues is persistent, please contact us. Error: " . $loop->last_query() );
	}

	wp_reset_postdata();
}


function get_availiability_search() {
	extract($_REQUEST);
	global $wpdb;
	$posts = $wpdb->prefix.'posts';
	$meta = $wpdb->prefix.'postmeta';
	$rental = $wpdb->prefix.'rental_blocked_days';

	$arrival = date("Y-m-d", strtotime($arrival));
	$departure = date("Y-m-d", strtotime($departure));

	$sql = "
		SELECT p.ID
		FROM $posts p
		WHERE NOT EXISTS (
			SELECT 1
			FROM $rental r
			WHERE r.blocked_date >= '$arrival'
			AND r.blocked_date <= '$departure'
			AND p.ID = r.property_id
		)
		AND p.post_type = 'rental'
		AND p.post_status = 'publish'

	";


	// if(!empty($rental_views)) {
	// 	$view = "views_" . $rental_views;
	// 	$sql .= " AND v.meta_key = $view";
	// }


	$get_ids = $wpdb->get_results($sql);
	$ids = array();
	foreach ($get_ids as $get_ids => $row) {
		$ids[] = $row->ID;
	}


	wp_reset_postdata();
	//now we need to create a wp query
	$args = array(
	  'post_type'   => $post_type,
	  'posts_per_page' => '10',
	  'post__in' => $ids,
	  'meta_query' => array(
	  	'relation' => 'AND',
	  	array(
			'key' => 'property_beds',
			'value'   => $num_bedrooms,
	        'compare' => '>=',
		),
		array(
			'key' => 'property_bedrooms',
			'value'   => $bedrooms,
	        'compare' => '>=',
		)
	  )
	);

	//paged works on the continouos scroll
	if(isset($paged)) {
		$args['paged'] = $paged;
	}

	//lets see if our rental_views is not empty
	if( !empty($rental_views) ) {
		$args['meta_query'][] = array(
			'key' => 'views_' . $rental_views
		);
	}

	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
	    $success[] = easy_property_blurb_extra(get_the_ID(), $post_type);
	endwhile;





	if( count($success) >= 1 ) {
		wp_send_json_success( $success );
	} else {
		wp_send_json_error( count($results) . " Something went wrong! Please try again later. If the issues is persistent, please contact us. Query: " . $wpdb->last_query . " . ERROR : " . $wpdb->last_error );
	}



}

add_action( 'wp_ajax_get_availiability_search', 'get_availiability_search' );
add_action( 'wp_ajax_nopriv_get_availiability_search', 'get_availiability_search' );