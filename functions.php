<?php
include_once("plugins/common.php");
include_once("plugins/real-estate/real-estate.php");
include_once("plugins/rentals/rentals.php");
include_once("theme/theme.php");

function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/app-style.css' );
    wp_enqueue_script( 'jquery-easing', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js', array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


// create new column in et_pb_layout screen
add_filter( 'manage_et_pb_layout_posts_columns', 'ds_create_shortcode_column', 5 );
add_action( 'manage_et_pb_layout_posts_custom_column', 'ds_shortcode_content', 5, 2 );
// register new shortcode
add_shortcode('ds_layout_sc', 'ds_shortcode_mod');

// New Admin Column
function ds_create_shortcode_column( $columns ) {
$columns['ds_shortcode_id'] = 'Module Shortcode';
return $columns;
}

//Display Shortcode
function ds_shortcode_content( $column, $id ) {
if( 'ds_shortcode_id' == $column ) {
?>
<p>[ds_layout_sc id="<?php echo $id ?>"]</p>
<?php
}
}
// Create New Shortcode
function ds_shortcode_mod($ds_mod_id) {
extract(shortcode_atts(array('id' =>'*'),$ds_mod_id));
return do_shortcode('[et_pb_section global_module="'.$id.'"][/et_pb_section]');
}

