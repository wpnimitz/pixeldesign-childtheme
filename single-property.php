<?php

get_header();


$slider_id = get_post_meta( get_the_ID(), 'property_assigned_slider', true);
$prop_status = get_post_meta( get_the_ID(), 'property_status', true);
$rental_coordinates = get_post_meta( get_the_ID(), 'rental_coordinates', true );
$rental_coordinates_count = explode(",", $rental_coordinates);
$allow_view_location = false;
if(count($rental_coordinates_count) >= 3) {
	$allow_view_location = true;
}


$divi_option = get_option("et_divi");
$extra_layout1 = $divi_option["divi_property_extra_layout_1"];
$extra_layout2 = $divi_option["divi_property_extra_layout_2"];
$top_footer = $divi_option["divi_property_top_footer"];
$bottom_footer = $divi_option["divi_property_bottom_footer"];



if(empty($slider_id)) {
	$slider_id = $divi_option["divi_default_slider_id"];
}

$property_title =   get_the_title();
$property_individual_title =  get_post_meta( get_the_ID(), 'property_individual_title', true);
if( empty($property_individual_title) ) {
	$property_title = get_the_title();
} else {
	$property_title = $property_title . ' - ' . $property_individual_title;
}


$property_price =  get_post_meta( get_the_ID(), 'property_price', true);
$property_description =  get_post_meta( get_the_ID(), 'property_description', true);

if( empty($property_description) ) {
	$property_description = 'Edit the property description to remove this section text. Lorem sitas que re, is am faces inulpa sequiat eatiorio. Rores sus cum apictint exero dolore, ut omni cum endeliquid ut quam fuga. Atur, odipsum fuga. Natibus auda volores et plantem reptae quas et eos ab iunt quam elis doles sit esedis volupti orrumendit volorio nsectatin cust aliquis endit que voles re nos ius, ommolupta si doluptat Moluptat. Estrum aut excerci liquae pro temod magnis et eos remporpos re res is Imusam consequatus. Ehendis nis illanime que verepe deligen estianis mossintio tor aut voles eost voluptia quaspelestet molorep erupictatus, quuntec aborro et vollatur, quunt estios int et quia commoss itamet eat facil id ullut.';
}

$property_indoor_space =  get_post_meta( get_the_ID(), 'property_indoor_space', true);
$lot_only_area =  get_post_meta( get_the_ID(), 'lot_only_area', true);
$property_bedrooms =  get_post_meta( get_the_ID(), 'property_bedrooms', true);
$property_bathrooms =  get_post_meta( get_the_ID(), 'property_bathrooms', true);
$property_powder_room =  get_post_meta( get_the_ID(), 'property_powder_room', true);
$property_garage_stalls =  get_post_meta( get_the_ID(), 'property_garage_stalls', true);

$property_text_features =  get_post_meta( get_the_ID(), 'property_text_features', true);
$property_text_community =  get_post_meta( get_the_ID(), 'property_text_community', true);

$property_fully_furnished = get_post_meta( get_the_ID(), 'property_fully_furnished', true);

?>

<div id="main-content">
	<div class="container">
		<div class="entry-content">
			<?php echo do_shortcode('<div class="property-slider">[smartslider3 slider='. $slider_id .']</div>'); ?>

				<div class="property-wrapper single-property-info">
					<div class="et_pb_section">
						<div class="et_pb_row">
							<div class="et_pb_column et_pb_column_2_3">
								<h1 class="line"><?php echo $property_title ?></h1>
								<h3>	

									<?php 

									if($property_price > 0) {
										if( strpos($property_price, ',') !== false ) {
										    echo '$'. $property_price;
										} else {
											echo '$'. number_format($property_price);	
										}
									} else {
										echo 'Price Available Upon Request';
									}
									if($property_fully_furnished == "Fully Furnished") {
										echo ' <span class="priceDetail">Fully Furnished</span>';
									}
									if($property_fully_furnished == "Lot Only") {
										echo ' <span class="priceDetail">Lot Only</span>';
									}

									?>
								</h3>

								<?php if($prop_status == "sold") {
									echo '<div class="status-sold">Sold</div>';
								} ?>
								
								<div class="property-description">
									<?php echo $property_description; ?>
								</div>
							</div>
							<div class="et_pb_column et_pb_column_1_3">
								<div class="wrapper">

									<?php if($property_fully_furnished != "Lot Only") { ?>
									<div class="one">
										<span class="propertyNumber"><?php echo number_format($property_indoor_space) ?></span>
										<br>Sq Ft Indoor Living Space 
									</div>								
									<div class="two">
										<span class="propertyNumber"><?php echo $property_bedrooms ?></span>
										<br>Bedroom<?php echo ($property_bedrooms > 1) ? 's' : '' ?>
									</div>
									<div class="three">
										<span class="propertyNumber"><?php echo ($property_garage_stalls>0) ? $property_garage_stalls : '&mdash;' ?></span>
										<br>Garage Stall<?php echo ($property_garage_stalls > 1) ? 's' : '' ?>
									</div>
									<div class="four">
										<span class="propertyNumber"><?php echo $property_bathrooms ?></span>
										<br>Bathroom<?php echo ($property_bathrooms > 1) ? 's' : '' ?>
									</div>
									<div class="five">
										<span class="propertyNumber"><?php echo ($property_powder_room>0) ? $property_powder_room : '&mdash;' ?></span>
										<br>Half Bath<?php echo ($property_powder_room > 1) ? 's' : '' ?>
									</div>
									<?php } ?>

									<?php if($property_fully_furnished == "Lot Only") { ?>
									<div class="one alone">
										<span class="propertyNumber"><?php echo number_format($lot_only_area) ?></span>
										<br>Sq Ft Lot Size
									</div>
									<?php } ?>
								</div>
								<br>
								<?php if($allow_view_location) {?>
								<div style="margin-top: 1.5em;clear:left;'"><a class="amenityButton locButton" href="#unique_overlay_menu_id_13643" id="overlay_unique_id_13643">View Location</a></div>
								<?php } ?>
							</div>
						</div>

						<?php if(!empty($property_text_features)) {?>
						<div class="et_pb_row property-features">
							<div class="et_pb_column et_pb_column_2_3">
								<h3 class="line">Features</h3>
								<div class="property-description">
									<?php echo $property_text_features ?>
								</div>
							</div>
							<div class="et_pb_column et_pb_column_1_3">
								<div class="empty-space"></div>
							</div>
						</div>
						<?php } ?>

						<?php if(!empty($property_text_community)) {?>
						<div class="et_pb_row property-community">
							<div class="et_pb_column et_pb_column_2_3">
								<h3 class="line">Communities</h3>
								<div class="property-description">
									<?php echo $property_text_community ?>
								</div>
							</div>
							<div class="et_pb_column et_pb_column_1_3">
								<div class="empty-space"></div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>

				<?php


				if(!empty($extra_layout1)) {
					echo do_shortcode('<div class="property-wrapper">[et_pb_section global_module="'.$extra_layout1.'"][/et_pb_section]</div>');
				}
				if(!empty($extra_layout2)) {
					echo do_shortcode('<div class="property-wrapper">[et_pb_section global_module="'.$extra_layout2.'"][/et_pb_section]</div>');
				}

				if(!empty($top_footer)) {
					echo do_shortcode('<div class="property-wrapper">[et_pb_section global_module="'.$top_footer.'"][/et_pb_section]</div>');
				}
				if(!empty($bottom_footer)) {
					echo do_shortcode('<div class="property-wrapper">[et_pb_section global_module="'.$bottom_footer.'"][/et_pb_section]</div>');
				}
			?>
		</div> <!-- .entry-content -->
		<?php do_action( 'et_after_post' ); ?>
	</div> <!-- .container -->
</div> <!-- #main-content -->


<?php

get_footer();
