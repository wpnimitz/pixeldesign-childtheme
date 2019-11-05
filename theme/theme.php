<?php
function b3_marve_divi_library() {
	$libs = array();
	$args = array('post_type' => 'et_pb_layout', 'posts_per_page' => -1);

    $alllibrarys = get_posts($args);

    $all_ids = wp_list_pluck( $alllibrarys , 'post_title','ID' );

	if(!empty($all_ids)){
		foreach($all_ids as $key=>$val){
			$libs += ['four_' . $key => esc_html__( $val, 'et_builder' )];
		}
	}else{
		$libs += [null => 'Sorry, Divi Library is empty. Create some layouts...'];
	}
	$libs += [ 'four_0' => '(Disabled)'];
	return $libs;    	
}

function get_theme_info($key) {
	$divi_option = get_option("et_divi");
	$info = $divi_option[$key];

	return $info;	
}
add_action("epanel_render_maintabs", 'add_easy_properties_epanel_tab');

function add_easy_properties_epanel_tab(){

  add_easy_properties_epanel_fields();
  ?>
  <li><a href="#wrap-easy-properties"><?php echo 'Delmar Properties'; ?></a></li>
  <?php
}

add_action("et_epanel_changing_options", 'add_easy_properties_epanel_fields');

function add_easy_properties_epanel_fields(){
	global $epanelMainTabs, $themename, $shortname, $options;

	$prefix = "easy-properties-";
	$suffix = "-easy-properties";

	//content tab
		//subnavtab-start
			//$prefix-N
		//subnavtab-end

		//subcontent-start ($prefix-N)

	$options[] = array(
		"name" => "wrap" . $suffix,
		"type" => "contenttab-wrapstart"
	);

	$options[] = array(
		"type" => "subnavtab-start"
	);

	$options[] = array(
		"name" => $prefix . "1",
		"type" => "subnav-tab",
		"desc" => esc_html__("Real Estate", $themename)
	);

	$options[] = array(
		"name" => $prefix . "2",
		"type" => "subnav-tab",
		"desc" => esc_html__("Rental", $themename)
	);

	$options[] = array(
		"name" => $prefix . "3",
		"type" => "subnav-tab",
		"desc" => esc_html__("Other", $themename)
	);
	$options[] = array(
		"type" => "subnavtab-end"
	);



	$options[] = array(
	    "name" => $prefix . "1",
	    "type" => "subcontent-start",);

	$options[] = array(
		'name' => esc_html__('Default Slider', $themename),
		'type' => 'text',
		'id' => $shortname . "_default_slider_id",
		'desc' => 'Enter your Smart Slider default ID here.',
		'std' => get_theme_info($shortname . "_default_slider_id"),
	);

	$options[] = array(
		'name' => esc_html__('Community Slider', $themename),
		'id' => $shortname . "_property_extra_layout_1",
		'desc' => esc_html__('Enter Property Extra Layout #1', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_property_extra_layout_1"),
	 );

	$options[] = array(
		'name' => esc_html__('Ammenities Slider', $themename),
		'id' => $shortname . "_property_extra_layout_2",
		'desc' => esc_html__('Enter Property Extra Layout #2', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_property_extra_layout_2"),
	);

	$options[] = array(
		'name' => esc_html__('Contact Slider', $themename),
		'id' => $shortname . "_property_top_footer",
		'desc' => esc_html__('Enter Property Top Footer', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_property_top_footer"),
	 );

	$options[] = array(
		'name' => esc_html__('Bottom Footer', $themename),
		'id' => $shortname . "_property_bottom_footer",
		'desc' => esc_html__('Enter Property Bottom Footer', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_property_bottom_footer"),
	);

	$options[] = array(
	    "name" => $prefix . "1",
	    "type" => "subcontent-end"
	);

	$options[] = array(
	    "name" => $prefix . "2",
	    "type" => "subcontent-start"
	);
	$options[] = array(
		'name' => esc_html__('Rental Default Slider', $themename),
		'type' => 'text',
		'id' => $shortname . "_rental_default_slider_id",
		'desc' => 'Enter your Smart Slider default ID here.',
		'std' => get_theme_info($shortname . "_rental_default_slider_id"),
	);

	$options[] = array(
		'name' => esc_html__('Rental Community Slider', $themename),
		'id' => $shortname . "_rental_property_extra_layout_1",
		'desc' => esc_html__('Enter Rental Property Extra Layout #1', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_rental_property_extra_layout_1"),
	 );

	$options[] = array(
		'name' => esc_html__('Rental Ammenities Slider', $themename),
		'id' => $shortname . "_rental_property_extra_layout_2",
		'desc' => esc_html__('Enter Rental Property Extra Layout #2', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_rental_property_extra_layout_2"),
	);

	$options[] = array(
		'name' => esc_html__('Rental Contact Slider', $themename),
		'id' => $shortname . "_rental_property_top_footer",
		'desc' => esc_html__('Enter Rental Property Top Footer', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_rental_property_top_footer"),
	 );

	$options[] = array(
		'name' => esc_html__('Rental Bottom Footer', $themename),
		'id' => $shortname . "_rental_property_bottom_footer",
		'desc' => esc_html__('Enter Rental Property Bottom Footer', $themename),
		'type' => 'text',
		'std' => get_theme_info($shortname . "_rental_property_bottom_footer"),
	);

	$options[] = array(
	    "name" => $prefix . "2",
	    "type" => "subcontent-end"
	);

	$options[] = array(
	    "name" => $prefix . "3",
	    "type" => "subcontent-start"
	);

	// $options[] = array(
	// 	'name' => esc_html__('Property Bottom Footer Layout', $themename),
	// 	'id' => $shortname . "_test_textarea",
	// 	'desc' => esc_html__('Select Property Bottom Footer Layout', $themename),
	// 	'type' => 'textarea',
	// 	'et_save_values' => true
	// );

	$options[] = array(
	    "name" => $prefix . "3",
	    "type" => "subcontent-end"
	);

	$options[] = array(
		"name" =>"wrap" . $suffix,
		"type" => "contenttab-wrapend"
	);
}