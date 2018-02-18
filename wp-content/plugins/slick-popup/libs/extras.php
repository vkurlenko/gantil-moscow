<?php

// Deprecated Extra Functions


/**
 * Get Color Scheme Options  
 * Since Version 1.0
 * @param string $color_scheme (choosen color scheme)
 * @param string $custom_color_scheme (choosen color when custom scheme)
 * @param string $custom_text_color (choosen text color when custom scheme)
 * Since Version 1.4.0
 * @param string $custom_form_background_color (choosen form background color when custom scheme)
 
 * @return array() $colors (keys: main-text-color, main-color, main-background-color)
 * Called in splite_option_css() to generate custom CSS
 */
function splite_get_theme_colors_values($color_scheme, $custom_color_scheme="", $custom_text_color="", $custom_form_background_color="") {
	$colors = array();
	$colors['main-text-color'] = '#EFEFEF'; 
	$colors['main-background-color'] = '#EFEFEF'; 	
	switch($color_scheme) {
		case 'master_red' :
			$colors['main-color'] = '#ED1C24'; 			
			break; 
		case 'creamy_orange' :
			$colors['main-color'] = '#EE5921'; 
			break; 
		case 'cool_green' :
			$colors['main-color'] = '#00A560'; 
			break; 
		case 'light_blue' :
			$colors['main-color'] = '#08ADDC'; 
			break; 
		case 'custom_theme' : 
			$colors['main-color'] = $custom_color_scheme; 
			$colors['main-text-color'] = $custom_text_color; 
			$colors['main-background-color'] = $custom_form_background_color; 
			break; 
		case 'light' :
			$colors['main-color'] = '#BBB'; 
			$colors['main-text-color'] = '#484848'; 
			break; 
		case 'dark' :
		default :
			$colors['main-color'] = '#484848'; 
			$colors['main-text-color'] = '#DDDDDD'; 
			break; 
	}
	
	return apply_filters( 'splite_dollar_colors', $colors );
		
}

/**
 * Get Popup Border Options
 * Since Version 1.0
 * @param string $popup_corners (choosen popup border radius)
 
 * @return array() $borders (keys: width(radius))
 * Called in splite_option_css() to generate custom CSS
 */
function splite_get_popup_border_values($popup_corners) {
	
	if( SPLITE_DEBUG ) {
		echo '<br/>Corners in Popup: '. $popup_corners; 
	}
	
	global $splite_opts; 	
	$borders = array();	
	$custom_popup_corners = isset($splite_opts['custom-popup-border']) ? $splite_opts['custom-popup-border'] : array('width'=>'20px');
	
	switch($popup_corners) {
		case 'square':
			$border_radius_value = '0px';
			break;
		case 'rounded':
			$border_radius_value = '20px';
			break;
		case 'custom':
			$border_radius_value = $custom_popup_corners['width'];
			break;
		default: 
			$border_radius_value = '0px';
	}
	
	$borders['radius'] = $border_radius_value; 
	return $borders; 
}

/**
 * Get Side Button Options
 * Since Version 1.0
 * @param string $side_button_scheme (choosen scheme for side button (inherit,custom))
 * @param string $side_button_background (choosen color when scheme is custom)
 
 * @return array() $side_button (keys: background-color)
 * Called in splite_option_css() to generate custom CSS
 */
function splite_get_side_button_values($side_button_scheme, $side_button_background) {
	
	if( SPLITE_DEBUG ) {
		echo '<br/>';
		echo '<br/>Side Button Scheme: '. $side_button_scheme; 
		echo '<br/>Side Button Background: '. $side_button_background; 
	}
	
	global $splite_opts; 	
	$side_button = array();	
	$side_button['background-color'] = '';
	
	if($side_button_scheme=='custom') {
		$side_button['background-color'] = $side_button_background;
	}
	
	return $side_button; 
}

/**
 * Get Submit Button Options
 * Since Version 1.0
 * @param string $submit_button_scheme (choosen scheme for submit button (inherit,custom))
 * @param string $submit_button_background (choosen color when scheme is custom)
 
 * @return array() $submit_button (keys: background-color)
 * Called in splite_option_css() to generate custom CSS
 */
function splite_get_submit_button_values($submit_button_scheme, $submit_button_background, $choose_theme_main_color) {
	
	if( SPLITE_DEBUG ) {
		echo '<br/>';
		echo '<br/>Submit Button Scheme: '. $submit_button_scheme; 
		echo '<br/>Submit Button Background: '. $submit_button_background; 
	}
	
	global $splite_opts; 	
	$submit_button = array();	
	$submit_button['background-color'] = '';
	
	if($submit_button_scheme=='custom') {
		$submit_button['background-color'] = $submit_button_background; 
	}
	elseif($submit_button_scheme=='inherit_from_color_scheme') {
		$submit_button['background-color'] = $choose_theme_main_color;
	}
	elseif($submit_button_scheme=='inherit_from_theme') {
		$submit_button['background-color'] = '';
	}
	
	if( SPLITE_DEBUG ) {
		echo '<br/>Submit Button: ';
		print_r($submit_button);
	}
	
	return $submit_button; 
}


/**
 * Get fire_activation_mode_script
 * Since Version 1.2
 * @param string $activation_mode (manually,autopopup,onscroll,onexit) 
 
 * @return none
 * Echo the script for activation mode choosen
 * Called in splite_add_my_popup() 
 */
function splite_fire_activation_mode_script($activation_mode) {
	
	switch($activation_mode['mode']) {
		case 'autopopup':
			echo '<script>
					setTimeout(function () { splite_loader(); }, '.($activation_mode['autopopup_delay'] * 1000).');
				</script>';
			break; 
		case 'onscroll':
			if( $activation_mode['onscroll_type'] == 'pixels'  ) {
				echo '<script>
					var eventFired = false;
					jQuery(window).on("scroll", function() {
						var currentPosition = jQuery(document).scrollTop();
						if (currentPosition > '.$activation_mode['onscroll_pixels'].' && eventFired == false) {
							eventFired = true;
							//console.log( "scrolled" );
							splite_loader(); 
						}
					});
				</script>';
			}
			if( $activation_mode['onscroll_type'] == 'percentage'  ) {
				echo '<script>
						var eventFired = false;
						jQuery(window).on("scroll", function() {
							var currentPosition = jQuery(document).scrollTop();
							if (currentPosition > jQuery(document).height()* '.($activation_mode['onscroll_percentage']/100).' && eventFired == false) {
								eventFired = true;
								//console.log( "scrolled" );
								splite_loader(); 
							}
						});
					</script>';
			}
			break; 
		case 'onexit':
			echo '<script>
					jQuery(window).addEventListener("beforeunload", function (e) {
						//e.preventDefault();
						e.returnValue = "Would you like to fill up our form?";
						setTimeout(function () { // Timeout to wait for user response
						setTimeout(function () { // Timeout to wait onunload, if not fired then this will be executed
							//console.log("User stayed on the page.");
							splite_loader();					
						}, 50)}, 50);
						return "Would you like to fill up our form?";
					});
				</script>';
			break; 
		default: break; 
	}
}


/////////////////////////////////////
// Uninstall Hook Helper
/////////////////////////////////////
function splite_uninstall_plugin($test_mail=false) { // Uninstallation actions here
	
	global $splite_opts; 
	$option_name = 'splite_opts'; 	
	$delete_data = $splite_opts['delete_data'];	
	$send_test_email = $test_mail;
	$admin_email = 'poke@slickpopup.com';
	$site_url = site_url(); 
	$headers[] = 'From: Om Ak <om.akdeveloper@gmail.com>';
	$headers[] = 'CC: ';
	$headers[] = 'BCC: '; 
	
	if( $delete_data=='on' ) {
		if( delete_option($option_name) AND $send_test_email ) {
			$body = 'Settings: ' .$delete_data. ' Plugin has been successfully deleted including options variable.';
			wp_mail( $admin_email, 'SP Lite Uninstall: '.$site_url, $body, $headers ); 			
		}
		else {
			$body = 'Settings: ' .$delete_data. ' Plugin was uninstalled but the delete data could NOT be deleted.'; 
			wp_mail( $admin_email, 'SP Lite Uninstall: '.$site_url, $body, $headers ); 
		}
	}
	else {
		$body = 'Settings: ' .$delete_data. ' Plugin was uninstalled but the delete data was not On, so settings are kept.'; 
		wp_mail( $admin_email, 'SP Lite Uninstall: '.$site_url, $body, $headers ); 
	}
}


add_action( 'wp_footer', 'check_global' );
/////////////////////////////////////
// Print Global Variable In Footer
/////////////////////////////////////
function check_global() { 
	if( SPLITE_DEBUG ) {
		global $splite_opts, $post; 
		echo '<div style="display:hidden;">';
			echo '<br/><div>'; echo $post->ID; echo '</div><br/>';
			echo '<br/><div>'; var_dump( $splite_opts ); echo '</div><br/>';
		echo '</div>';
		//echo '<br/>'; echo absint('103');
	}
}

//add_action('admin_menu', 'splite_all_settings_link'); 
/////////////////////////////////////////
// Link to Go To options.PHP (All Settings)
////////////////////////////////////////
function splite_all_settings_link() {
	add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}

/////////////////////////////////////////////
// Test function - to check overriden fields
/////////////////////////////////////////////
function splite_check_overriding_for_page_options() {
	echo '<br/><br/>running0'; 
	global $post; 	
	$custom =  get_post_meta( $post->ID, '_splite_page_options', true); 
	print_r($custom);
	if( !is_array($custom) ) return; 
	//if( !$custom['_splite_meta_override'] ) return; 
	
	//if( isset($custom['_splite_meta_form_id']) AND !empty($custom['_splite_meta_form_id']) )
		add_filter( 'splite_dollar_cf7_id', function($cf7_id) { return 103; } );

	echo '<br/><br/>running1'; 
	return true; 
}

?>