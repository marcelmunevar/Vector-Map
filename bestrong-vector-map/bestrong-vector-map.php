<?php
/*
Plugin Name: BeStrong Vector Map
Plugin URI:  
Description: Interactive vector map of USA for Be Strong.
Version:     1.01
Author:      Marcel Munevar
Author URI:  
License:     Copyright (see below)
License URI: 
Text Domain: 
Domain Path: 

/*******************************************************
 * Copyright (C) 2017 Marcel Munevar marcelmunevar@gmail.com
 * 
 * This file is part of bestrong-vector-map.
 * 
 * bestrong-vector-map can not be copied and/or distributed without the express
 * permission of Marcel Munevar
 *******************************************************/
 
 defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
 $states = array( "washington", "oregon", "idaho" , "montana" , "north_dakota" , "wyoming" , "south_dakota" , "minnesota" , "wisonsin" , "california" , "nevada" , "utah" , "arizona" , "colorado" , "nebraska" , "new_mexico" , "oklahoma" , "kansas" , "iowa" , "missouri" , "illinois" , "indiana" , "michigan" , "ohio" , "kentucky" , "pennsylvania" , "new_jersey" , "new_york" , "vermont" , "new_hampshire" , "connecticut" , "rhode_island" , "massachusetts" , "maine" , "delaware" , "maryland" , "west_virginia" , "virginia" , "arkansas" , "tennessee" , "north_carolina" , "south_carolina" , "georgia" , "alabama" , "florida" , "mississippi" , "louisiana" , "texas" , "hawaii" , "alaska" , "washington_dc" );
 
 //DEFINE SCRIPTS
 function vectormap_load_scripts() {
	wp_register_style( 'vectormap-style', plugins_url( 'map.css', __FILE__ ) );
	wp_register_script( 'vectormap-raphael', plugins_url( 'raphael-min.js', __FILE__ ), false, '1.0', true );
	wp_register_script( 'vectormap-script', plugins_url( 'map.js', __FILE__ ), array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'vectormap_load_scripts' );
 
 
//ADD SHORTCODE 
function vector_map_shortcode( $attributes ) {

	wp_enqueue_style( 'vectormap-style' );
	wp_enqueue_script( 'vectormap-raphael' );
	wp_enqueue_script( 'vectormap-script' );

	$output = "";
	$output .= '<div class="wrap-svg-vector-map">';
	$output .= '<img class="blank-img-vector-map" src="'.plugins_url( 'blank.png', __FILE__ ).'" />';
	$output .= '<div id="rsr-vector-map" class="rsr-vector-map"></div>';
	
	
		
	
	global $states;
	
	foreach ($states as $state) {
		
		
		$active = get_option('active_'.$state);
		$attachment_id = get_option( 'media_selector_attachment_id_'.$state );
		$students_grade = get_option('students_grade_'.$state);
		$students_quote = get_option('students_quote_'.$state);
		$students_name = get_option('students_name_'.$state);
		$educators_comments = get_option('educators_comments_'.$state);
		$student_video = get_option('student_video_'.$state);
		$cdc_stats = get_option('student_cdc_statistics_'.$state);
		
		
		if( isset($active) && $active != null  ){
			
			$state_nicename = str_replace("_", " ", $state);
			$state_nicename = ucwords($state_nicename);
			if($state_nicename == "Washington Dc"){$state_nicename = "Washington DC";}

			$output .= '<div class="modal-vector-map '.$state.'">';/*state*/
				
				$output .= '<div class="modal-content-vector-map">';
					$output .= '<div class="modal-header-vector-map">';
						$output .= '<span class="close-vector-map">&times;</span>';
						$output .= '<h2>'.$state_nicename.'</h2>';/*state*/
					$output .= '</div>';
					$output .= '<div class="modal-body-vector-map">';
					
						if( isset($attachment_id) && $attachment_id != null ){
						$output .= '<img class="students-image-vector-map" src="'.wp_get_attachment_url( $attachment_id ).'">';/*image*/
						}
					
						
						if(  isset($students_quote) && $students_quote != null && isset($students_name) && $students_name != null ){
						$output .= '<p>"'.esc_attr( $students_quote ).'"</p><p class="students-name">- '.esc_attr( $students_name ).'</p>';/*quote*/
						}
						
						
						
					$output .= '</div>';
					$output .= '<div class="et_pb_promo et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_cta_0 et_pb_no_bg">';
					$output .= "<a class='et_pb_promo_button et_pb_button modal-button-vector-map' href='/participate/state-representative-program/".str_replace("_", "-", $state)."'>JOIN ME</a>";
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}
	
	}
	
	$output .= "</div>";
	
	return $output;
}

add_shortcode( 'vector_map', 'vector_map_shortcode' );


//ADD MENU ITEM TO ADMIN
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('BeStrong Vector Map', 'Vector Map', 'edit_pages', 'bestrong-vector-map-settings', 'bestrong_vector_map_settings_page', 'dashicons-admin-generic');
}

function bestrong_vector_map_settings_page() {
	wp_enqueue_media();
	if(function_exists( 'wp_enqueue_media' )){
		wp_enqueue_media();
	}else{
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	}
	global $states;
	
	sort($states);
	
	?>
	<div class="wrap">
		<h2>Details</h2>
		
		<form method="post" action="options.php">
			<?php settings_fields( 'my-plugin-settings-group' ); ?>
			<?php do_settings_sections( 'my-plugin-settings-group' ); ?>
			<table class="form-table">
			
				<?php foreach ($states as $state) { 
				$state_nicename = str_replace("_", " ", $state);
				$state_nicename = ucwords($state_nicename);
				if($state_nicename == "Washington Dc"){$state_nicename = "Washington DC";}
				?>
				<tr valign="top">
				<th scope="row" colspan="2"><h3><?php echo $state_nicename; ?></h3></th>
				</tr>
				
				<tr valign="top">
				<th scope="row">Active</th>
				<td><input type="checkbox" name="active_<?php echo $state; ?>" value="active" 
				<?php checked( get_option('active_'.$state), 'active' )?> > </td>
				</tr>

				<tr valign="top">
				<th scope="row">Student's name</th>
				<td><input type="text" name="students_name_<?php echo $state; ?>" value="<?php echo esc_attr( get_option('students_name_'.$state) ); ?>" /></td>
				</tr>

								
				<tr valign="top">
				<th scope="row">Student's picture</th>
				<td>
					<div class='image-preview-wrapper'>
						<img class='image-preview_<?php echo $state; ?>' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id_'.$state ) ); ?>' width='100' height='100' style='max-height: 100px; width: 100px;'>
					</div>
					<input class="upload_image_button_<?php echo $state; ?>" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
					<input type='hidden' name='media_selector_attachment_id_<?php echo $state; ?>' class='media_selector_attachment_id_<?php echo $state; ?>' value='<?php echo get_option( 'media_selector_attachment_id_'.$state ); ?>'>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row">Student's quote</th>
				<td><textarea rows="4" cols="50" name="students_quote_<?php echo $state; ?>" /><?php echo esc_attr( get_option('students_quote_'.$state) ); ?></textarea></td>
				</tr>
				

				
				<tr valign="top">
				<td scope="row" colspan="2" style="padding:0;"><?php submit_button(); ?></td>
				</tr>
				
				<script>
				jQuery(document).ready(function($) {
					$('.upload_image_button_<?php echo $state; ?>').click(function(e) {
						e.preventDefault();

						var custom_uploader = wp.media({
							title: 'Custom Image',
							button: {
								text: 'Upload Image'
							},
							multiple: false  // Set this to true to allow multiple files to be selected
						})
						.on('select', function() {
							var attachment = custom_uploader.state().get('selection').first().toJSON();
							$('.image-preview_<?php echo $state; ?>').attr('src', attachment.url);
							$('.media_selector_attachment_id_<?php echo $state; ?>').val(attachment.id);
							//$('.header_logo_url').val(attachment.url);

						})
						.open();
					});
				});
			</script>
				<?php } ?>
			   
			</table>
			
			

		</form>
	</div>
	
	

	

<?php
}


add_action( 'admin_init', 'my_plugin_settings' );

function my_plugin_settings() {
	
	global $states;
	foreach ($states as $state) {
		register_setting( 'my-plugin-settings-group', 'active_'.$state );
		register_setting( 'my-plugin-settings-group', 'students_name_'.$state );
		register_setting( 'my-plugin-settings-group', 'students_quote_'.$state );
		//image
		register_setting( 'my-plugin-settings-group', 'media_selector_attachment_id_'.$state );
	}
	
}