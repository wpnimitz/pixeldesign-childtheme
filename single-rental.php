<?php

get_header();


$slider_id = get_post_meta( get_the_ID(), 'property_assigned_slider', true);
$allow_view_location = true;
$allow_view_calendar = false;



$divi_option = get_option("et_divi");
$extra_layout1 = $divi_option["divi_rental_property_extra_layout_1"];
$extra_layout2 = $divi_option["divi_rental_property_extra_layout_2"];
$top_footer = $divi_option["divi_rental_property_top_footer"];
$bottom_footer = $divi_option["divi_rental_property_bottom_footer"];



if(empty($slider_id)) {
	$slider_id = $divi_option["divi_rental_default_slider_id"];
}

$property_title =   get_the_title();;
$property_individual_title =  get_post_meta( get_the_ID(), 'property_individual_title', true);
if( empty($property_individual_title) ) {
	$property_title = get_the_title();
} else {
	$property_title = $property_title . ' - ' . $property_individual_title;
}

$property_price =  get_post_meta( get_the_ID(), 'property_price', true);
$property_price_max =  get_post_meta( get_the_ID(), 'property_price_max', true);
$property_description =  get_post_meta( get_the_ID(), 'property_description', true);

if( empty($property_description) ) {
	$property_description = 'Edit the property description to remove this section text. Lorem sitas que re, is am faces inulpa sequiat eatiorio. Rores sus cum apictint exero dolore, ut omni cum endeliquid ut quam fuga. Atur, odipsum fuga. Natibus auda volores et plantem reptae quas et eos ab iunt quam elis doles sit esedis volupti orrumendit volorio nsectatin cust aliquis endit que voles re nos ius, ommolupta si doluptat Moluptat. Estrum aut excerci liquae pro temod magnis et eos remporpos re res is Imusam consequatus. Ehendis nis illanime que verepe deligen estianis mossintio tor aut voles eost voluptia quaspelestet molorep erupictatus, quuntec aborro et vollatur, quunt estios int et quia commoss itamet eat facil id ullut.';
}



$property_indoor_space =  get_post_meta( get_the_ID(), 'property_indoor_space', true);
$property_bedrooms =  get_post_meta( get_the_ID(), 'property_bedrooms', true);
$property_bedrooms =  get_post_meta( get_the_ID(), 'property_bedrooms', true);
$bedroom_label =  get_post_meta( get_the_ID(), 'bedroom_label', true);
$bedroom_details =  get_post_meta( get_the_ID(), 'bedroom_details', true);
$property_bathrooms =  get_post_meta( get_the_ID(), 'property_bathrooms', true);
$property_powder_room =  get_post_meta( get_the_ID(), 'property_powder_room', true);
$property_garage_stalls =  get_post_meta( get_the_ID(), 'property_garage_stalls', true);

$property_text_features =  get_post_meta( get_the_ID(), 'property_text_features', true);
$property_text_community =  get_post_meta( get_the_ID(), 'property_text_community', true);

if($property_price == "") {
	$property_price = "0";
}

if($property_price_max == "") {
	$property_price_max = "0";
}


if($property_indoor_space == "") {
	$property_indoor_space = "0";
}

if($property_bedrooms == "") {
	$property_bedrooms = "0";
}

if($property_bathrooms == "") {
	$property_bathrooms = "0";
}
if($property_powder_room == "") {
	$property_powder_room = "0";
}

if($property_garage_stalls == "") {
	$property_garage_stalls = "0";
}

if($property_beds == "") {
	$property_beds = "0";
}

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
								<h3>$<?php echo number_format($property_price) ?>  - $<?php echo number_format($property_price_max) ?><span class="priceDetail"> Per Night</span> </h3>
								<div class="propertyButtons"><a class="amenityButton" href="#unique_overlay_menu_id_13593" id="overlay_unique_id_13593"	>Book Now</a>&nbsp;<a class="amenityButton whiteFill" id="overlay_unique_id_13600" href="#unique_overlay_menu_id_13600">Share this property</a></div>
								<div class="property-description">
									<?php echo $property_description ?>
								</div>
								
								<!-- START: BOILERPLATE FEATURE LIST (THE DIV BELOW WAS ADDED BY DOUG) -->
							</div>
							<div class="et_pb_column et_pb_column_2_3">
	                            <div class="featureBoilerplate"> 
									<h4>Amenities</h4>
	                                <p>Our collection of two to eight bedroom beachfront and oceanview villas offer:</p>
	                                <ul>
	                                  <li>Oceanview master suites</li>
	                                  <li>Well-appointed indoor and outdoor dining areas</li>
	                                  <li>Private infinity-edged pools and spas</li>
	                                  <li>Outdoor fireplaces, fire pits and kitchens</li>
	                                  <li>Televisions with DVD players and high-speed Internet</li>
	                                  <li>Personal golf cart</li>
	                                </ul>
	                                <p>In addition, guests enjoy:</p>
	                                <ul>
	                                  <li>Personal butler included</li>
	                                  <li>Meticulous pre-arrival planning by concierge staff</li>
	                                  <li>Dedicated House Manager</li>
	                                  <li>Daily Housekeeping services</li>
	                                  <li>Offsite activity coordination and in-residence spa services</li>
	                                  <li>Available personal chef (cost additional)</li>
	                                  <li>Exclusive Villas Del Mar and Espiritu Del Mar amenities, including the Club Ninety Six beach club and kids club, Club Espiritu fitness club and spa, private sailing catamaran, and access to Palmilla golf all just minutes away via golf cart ride.</li>
	                                  <li>Restaurants, boutiques, beaches, golf and tennis are just a short walk or golf cart ride away from your villa</li>
	                                  <li>24-hour gated security and emergency medical response</li>
	                                </ul>
	                            </div> 

								<!-- END: BOILERPLATE FEATURE LIST -->
							
							</div>
							
							<!-- Property Grid layout -->
							<div class="et_pb_column et_pb_column_1_3 property_grid">
								<div class="property-meta">
									<div class="meta full">
										<span class="propertyNumber"><?php echo number_format($property_indoor_space) ?></span>
										<br>Sq Ft Indoor Living Space
									</div>
									
									<div class="meta full noBottomBorder">
									<span class="propertyNumber"><?php echo $property_bedrooms ?></span>
										<br>Bedroom<?php echo ($property_bedrooms > 1) ? 's' : '' ?><br><br>
									<?php if(!empty($bedroom_label)) {
										echo '<table id="bedrooms" class="bedroomsDetails" style="margin-top:.4em;">';
										for ($i=0; $i < count($bedroom_label); $i++) { 
											echo '<tr>';
											echo '<td class="bedroomName">'.$bedroom_label[$i].'</td>';
											echo '<td class="beds">'.$bedroom_details[$i].'</td>';
											echo '</tr>';
										}
										echo '</table>';
									}?>
									</div>
									
											

									<div class="meta left meta-last">
										<span class="propertyNumber"><?php echo $property_bathrooms ?></span>
										<br>Bathroom<?php echo ($property_bathrooms > 1) ? 's' : '' ?>
									</div>
									<div class="meta  meta-last">
										<span class="propertyNumber"><?php echo ($property_powder_room>0) ? $property_powder_room : '&mdash;' ?></span>
										<br>Half Bath<?php echo ($property_powder_room > 1) ? 's' : '' ?>
									</div>
									
								</div><br>
								<div style="margin-top: 1.5em;clear:left;text-align:center;">
									<?php if($allow_view_location) {?>
									<a class="amenityButton locButton" href="#" id="overlay_unique_id_13643">View Location</a>
									<?php } ?>

									<?php if($allow_view_calendar) {?>
									<a class="amenityButton view-calendar" href="#" id="overlay_unique_id_13820">View Calendar</a>
									<?php } ?>
								</div>
							</div>

							<!-- END: Property Grid layout asdf -->
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
