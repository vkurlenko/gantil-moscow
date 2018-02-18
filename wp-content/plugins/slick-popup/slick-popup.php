<?php
/*
Plugin Name:  Slick Popup
Plugin URI:   http://www.omaksolutions.com
Description:  A lightweight plugin that converts a Contact Form 7 form into a customizable pop-up form which is slick, beautiful and responsive to different screen-sizes.
Author URI:   http://www.omaksolutions.com 
Author:       Om Ak Solutions 
Version:      1.4.1
Text Domain: sp-pro-txt-domain
*/


define( 'SPLITE_VERSION', '1.4.1' );

define( 'SPLITE_REQUIRED_WP_VERSION', '3.0.1' );

define( 'SPLITE_PLUGIN', __FILE__ );

define( 'SPLITE_PLUGIN_BASENAME', plugin_basename( SPLITE_PLUGIN ) );

define( 'SPLITE_PLUGIN_NAME', trim( dirname( SPLITE_PLUGIN_BASENAME ), '/' ) );

define( 'SPLITE_PLUGIN_DIR', untrailingslashit( dirname( SPLITE_PLUGIN ) ) );

define ( 'SPLITE_DEBUG', FALSE );

require_once( SPLITE_PLUGIN_DIR . '/libs/admin/codestar/cs-framework.php' );
require_once( SPLITE_PLUGIN_DIR . '/libs/extras.php' );
require_once( SPLITE_PLUGIN_DIR . '/libs/classes/splite-importer.php' );

	//require_once(ABSPATH.'wp-admin/includes/plugin.php');	
	if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/libs/admin/redux-framework/framework.php' ) ) {
		require_once( dirname( __FILE__ ) . '/libs/admin/redux-framework/framework.php' );
	}

	if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/libs/admin/admin-init.php' ) ) {
		require_once( dirname( __FILE__ ) . '/libs/admin/admin-init.php' );
	}

/////////////////////////////////////
// Activation Hook
/////////////////////////////////////
register_activation_hook(__FILE__, 'splite_on_activate'); 
function splite_on_activate(){
	// Empty Activation Hook
}


/////////////////////////////////////
// Update Action using version compare
/////////////////////////////////////
//add_action( 'plugins_loaded', 'splite_update_db_check' );
//add_action( 'redux/options/splite_opts/register', 'splite_update_db_check' );
add_action( 'redux/loaded', 'splite_update_db_check' );
function splite_update_db_check() {
    if ( SPLITE_VERSION >= '1.3' ) {
        spplite_update_db(); 
    }
}


function spplite_update_db() {
	
	if( !get_option( 'cf7_id' ) OR !get_option( 'form_title' ) ) {
		// do nothing
		return; 
	}
	
	global $splite_opts;
	
	$options_array = array( 'cf7_id', 'primary_color', 'border_color', 'form_title', 'form_description', 'side_image' );
	
	$custom_theme_color = get_option('primary_color');
	$custom_border_color = get_option('border_color');	
	$form_id = get_option('cf7_id');
	$popup_heading = get_option('form_title');
	$popup_cta_text = get_option('form_description');
	$side_image = get_option('side_image');
	
	//Redux::setOption( $splite_opts, 'custom-theme-color',get_option('border_color') );	
	Redux::setOption( 'splite_opts', 'choose-color-scheme', 'custom_theme' );
	Redux::setOption( 'splite_opts', 'custom-theme-color', $custom_theme_color );
	
	Redux::setOption( 'splite_opts', 'form-id', $form_id );
	Redux::setOption( 'splite_opts', 'popup-heading', $popup_heading );
	Redux::setOption( 'splite_opts', 'popup-cta-text', $popup_cta_text );
	
	if( $side_image == 'have-a-query' )
		Redux::setOption( 'splite_opts', 'side-button-text', 'Have a query?' );
	elseif( $side_image == 'get-a-quote' )
		Redux::setOption( 'splite_opts', 'side-button-text', 'Get a Quote' );
	else 
		Redux::setOption( 'splite_opts', 'side-button-text', 'Contact Us' );
	
	foreach( $options_array as $option ) {
		delete_option( $option );
	}
}



/////////////////////////////////////
// Deactivation Hook
/////////////////////////////////////
register_deactivation_hook(__FILE__, 'splite_on_deactivate'); 
function splite_on_deactivate(){
	// Empty Deactivation Hook
}



/////////////////////////////////////
// Uninstall Hook
/////////////////////////////////////
register_uninstall_hook(__FILE__, 'splite_on_uninstall'); 
function splite_on_uninstall(){
	// Empty Activation Hook
	// Temporary Fix
	//delete_option( 'splite_opts' ); 	
	
	// Do the action
	splite_uninstall_plugin(true);
}


/////////////////////////////////////////
// Initialise the plugin and scripts
/////////////////////////////////////////
add_action('template_redirect','splite_slick_popup_loaded');
function splite_slick_popup_loaded(){
    
	global $splite_opts, $post; 
	$current_post_id = isset($post->ID) ? strval($post->ID): "";
	$show = true; 
	
	if( ! isset( $splite_opts['plugin_state'] ) ) {
		// Temporary Fix For First Installation
		// Error Notice: Plugin State Variable Not Available
		// Is expected to be done in Activation Hook 
		$splite_opts['plugin_state'] = 1;
		$splite_opts['where_to_show'] = 'everywhere';
	}	
	
	if( $splite_opts['plugin_state'] != 1 ) {
		$show = false; 
	}
	else {
		$page_ids = isset($splite_opts['choose_pages']) ? $splite_opts['choose_pages'] : '';
		$page_ids = is_array($page_ids) ? $page_ids : array($page_ids);
		switch($splite_opts['where_to_show']) {			
			case 'everywhere': break; 
			case 'onselected': 
				if( isset($page_ids) AND is_array($page_ids) AND !in_array($current_post_id, $page_ids)) {
					$show = false; 
				}
				break; 
			case 'notonselected': 
				if( isset($page_ids) AND is_array($page_ids) AND in_array($current_post_id, $page_ids)) {
					$show = false; 
				}
				break; 
			default: break; 
		}
	}	
	
	$show = apply_filters( 'splite_dollar_show', $show );
	
	if( $show ) {		
		// If Plugin State is Enabled = 1
		// Let us Create the Beauty		
		splite_add_html_and_scripts();
		splite_add_html();
	}
	else {
		// So, it's Sunday. Don't Do Nothing!!
	}
	
}



/////////////////////////////////////////
// Enqueue Scripts and Custom CSS
/////////////////////////////////////////
function splite_add_html_and_scripts(){
	// Add Pop Up Scripts to Footer Here
	add_action( 'wp_enqueue_scripts', 'splite_enqueue_popup_scripts' );	
	add_action('wp_footer', 'splite_option_css');	
}


/////////////////////////////////////////
// Add Popup HTML To the Footer
/////////////////////////////////////////
function splite_add_html(){
	add_action('wp_footer', 'splite_add_my_popup');		
}


/////////////////////////////////////////
// Add Popup
/////////////////////////////////////////
function splite_add_my_popup() {
	
	if( !is_admin() ) {
		
		global $splite_opts;	
		if( is_user_logged_in() ) {
			if (current_user_can('manage_options')) {
				$user_is_admin = true;
			}			
		}
		
		$choose_layout = $splite_opts['choose-layout'];		
		$color_scheme = $splite_opts['choose-color-scheme'];
		$custom_color_scheme = $splite_opts['custom-theme-color'];		
		$custom_text_color = $splite_opts['custom-text-color'];		
		
		$popup_heading = $splite_opts['popup-heading'];		
		$cta_text = $splite_opts['popup-cta-text'];			
		
		$side_button_scheme = $splite_opts['choose-side-button'];
		$submit_button_scheme = $splite_opts['choose-submit-button'];
		
		$side_button_text = !empty($splite_opts['side-button-text']) ? $splite_opts['side-button-text'] : 'Contact Us';
		$side_button_position = isset($splite_opts['side-button-position']) ? $splite_opts['side-button-position'] : 'left';
		$side_button_switch = isset($splite_opts['plugin_state_on_mobile']) ? 'enabled_on_mobile' : 'disabled_on_mobile';
		
		$activation_mode = array();
		$activation_mode['mode'] = isset($splite_opts['activation_mode']) ? $splite_opts['activation_mode'] : 'manually';
		$activation_mode['autopopup_delay'] = isset($splite_opts['autopopup-delay']) ? $splite_opts['autopopup-delay'] : 4;
		$activation_mode['onscroll_type'] = isset($splite_opts['onscroll-type']) ? $splite_opts['onscroll-type'] : 'pixels';
		$activation_mode['onscroll_pixels'] = isset($splite_opts['onscroll-pixels']) ? $splite_opts['onscroll-pixels'] : 300;	
		$activation_mode['onscroll_percentage'] = isset($splite_opts['onscroll-percentage']) ? $splite_opts['onscroll-percentage'] : 20;
		
		$popup_load_effect = isset($splite_opts['loader-animation']) ? $splite_opts['loader-animation'] : 'fadeIn';
		$popup_load_speed = isset($splite_opts['loader-speed']) ? $splite_opts['loader-speed'] : .75;
		$popup_unload_effect = isset($splite_opts['unloader-animation']) ? $splite_opts['unloader-animation'] : 'fadeOut';
		$popup_unload_speed = isset($splite_opts['unloader-speed']) ? $splite_opts['unloader-speed'] : .50;
		
		$cf7_id = isset($splite_opts['form-id'])? $splite_opts['form-id'] : '';

		global $post; 	
		$custom =  get_post_meta( $post->ID, '_splite_page_options', true); 	
		if( isset($custom['_splite_meta_override']) AND $custom['_splite_meta_override'] ) {
			if( isset($custom['_splite_meta_form_id']) AND !empty($custom['_splite_meta_form_id']) )
				$cf7_id = $custom['_splite_meta_form_id'];
			if( isset($custom['_splite_meta_side_button']) AND !empty($custom['_splite_meta_side_button']) )
				$side_button_text = $custom['_splite_meta_side_button'];
			if( isset($custom['_splite_meta_popup_heading']) AND !empty($custom['_splite_meta_popup_heading']) )
				$popup_heading = $custom['_splite_meta_popup_heading'];			
			if( isset($custom['_splite_meta_cta']) AND !empty($custom['_splite_meta_cta']) )
				$cta_text = $custom['_splite_meta_cta'];			
		}
		
		$cf7_id = apply_filters( 'splite_dollar_cf7_id', $cf7_id );
		$side_button_text = apply_filters( 'splite_dollar_side_button_text', $side_button_text );
		$popup_heading = apply_filters( 'splite_dollar_popup_heading', $popup_heading );
		$cta_text = apply_filters( 'splite_dollar_cta_text', $cta_text );
		
		$activation_mode = apply_filters( 'splite_dollar_activation_mode', $activation_mode );		
	
		// Check if overriding is desired		
		$message = splite_check_form_id($cf7_id);
		
		?>
		
		<!-- SP Pro - Popup Box Curtain Arrangement -->
		<div id="splite_curtain" onClick="splite_unloader();" style=""></div>
		<div class="splite_popup_animator" data-loadspeed="<?php echo $popup_load_speed; ?>" data-loadeffect="<?php echo $popup_load_effect; ?>" data-unloadeffect="<?php echo $popup_unload_effect; ?>" data-unloadspeed="<?php echo $popup_unload_speed; ?>"></div>
		<div id="splite_popup_box" class="<?php echo 'layout_'.$choose_layout; ?> manage">  			
			<div id="splite_popup_title"><?php echo $popup_heading; ?></div>			
			<div id="splite_form_container" class="">
				<p id="splite_popup_description"><?php echo $cta_text; ?></p>
				<?php 
					if( empty($message) ) { 
						echo do_shortcode( '[contact-form-7 id="' .$cf7_id. '" title="' . '' . '"]'); 
					}
					else { 
						echo '<div class="splite_form no-form">'.$message.'</div>'; 
					}
				?>
			</div>
			<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
			<a id="splite_popupBoxClose" onClick="splite_unloader();">X</a>  
		</div>
		
		<?php if( $side_button_position != 'pos_none' ) { ?>
			<a onClick="splite_loader();" class="splite_sideEnquiry <?php echo $side_button_position; ?> on_mobile <?php echo $side_button_switch; ?>"><?php echo $side_button_text; ?></a>
		<?php } ?>
		
		<!-- Slick Popup Lite Box and Curtain Arrangement -->		
		<?php splite_fire_activation_mode_script($activation_mode); ?>
<?php
	}
}



/////////////////////////////////////////
// Add CSS Based on Options
/////////////////////////////////////////
function splite_option_css() {
	
	global $splite_opts;
	$color_scheme = $splite_opts['choose-color-scheme'];
	$custom_color_scheme = $splite_opts['custom-theme-color'];	
	$custom_text_color = $splite_opts['custom-text-color'];	
	$custom_form_background_color = $splite_opts['custom-form-background-color'];	
	
	$popup_corners = $splite_opts['popup-corners'];
	$custom_popup_corners = isset($splite_opts['custom-popup-corners']) ? $splite_opts['custom-popup-corners'] : '';
			
	$heading_typography = $splite_opts['heading-typography'];  		
	$cta_typography = $splite_opts['cta-typography'];
		
	// Side Button
	$side_button_scheme = $splite_opts['choose-side-button'];
	$side_button_background = $splite_opts['side-button-background']['background-color'];
	$side_button_typography = $splite_opts['side-button-typography'];
	
	// Submit Button
	$submit_button_scheme = $splite_opts['choose-submit-button'];
	$submit_button_background = $splite_opts['submit-button-background']['background-color'];	
	$submit_button_typography = $splite_opts['submit-button-typography'];
	//$submit_button_border = $splite_opts['submit-button-border'];
  	
	// Custom CSS Code
	$custom_css_code = isset($splite_opts['custom-css-code']) ? $splite_opts['custom-css-code'] : '';
	
	///////////////////////////////////////////
	// Set Submit Button Styles
	///////////////////////////////////////////
	if( $submit_button_scheme == 'themeinherit' ) {
		$submit_bg = '';
		$submit_typo_color = '';
	}
	elseif( $submit_button_scheme == 'inherit' ) {		
		$submit_bg = $custom_color_scheme;
		$submit_typo_color = $custom_text_color;
	}
	elseif ( $submit_button_scheme == 'custom' ) {
		$submit_bg = $submit_button_background;
	}
	
	// Get The Main Colors from the function
	$theme_colors = splite_get_theme_colors_values($color_scheme, $custom_color_scheme, $custom_text_color, $custom_form_background_color);	
	// Get The Border Options
	$popup_border = splite_get_popup_border_values($popup_corners);
	// Get Side Button Options
	$side_button = splite_get_side_button_values($side_button_scheme, $side_button_background);
	// Get Submit Button Options
	$submit_button = splite_get_submit_button_values($submit_button_scheme, $submit_button_background, $theme_colors['main-color']);
	
	$side_typo_color = $side_button_typography['color'];
	$side_typo_font_family = $side_button_typography['font-family'];
	$side_typo_font_size = $side_button_typography['font-size'];
	$side_typo_font_weight = $side_button_typography['font-weight'];
	$side_typo_line_height = $side_button_typography['line-height'];
	
	$submit_typo_color = $submit_button_typography['color'];
	$submit_typo_font_family = $submit_button_typography['font-family'];
	$submit_typo_font_size = $submit_button_typography['font-size'];
	$submit_typo_font_weight = $submit_button_typography['font-weight'];
	$submit_typo_line_height = $submit_button_typography['line-height'];
	
	if( !is_admin() ) { ?>
			<style>
			#splite_popup_box {
				background: <?php echo $theme_colors['main-background-color']; ?>;
				border-bottom: 5px solid <?php echo $theme_colors['main-color']; ?>;
				border-radius: <?php echo $popup_border['radius']; ?>;
			}
			#splite_popup_title,
			#splite_popup_box div.wpcf7-response-output,
			a.splite_sideEnquiry {
				background-color: <?php echo $theme_colors['main-color']; ?>;
				color: <?php echo $theme_colors['main-text-color']; ?>;  
			}
			#splite_popup_description {  
				color: #959595;  
			}
			#splite_popupBoxClose {
				 color: <?php echo $theme_colors['main-text-color']; ?>;  
			}						
			#splite_popup_box  div.wpcf7 img.ajax-loader,
			#splite_popup_box div.wpcf7 span.ajax-loader.is-active {
				box-shadow: 0 0 5px 1px <?php echo $theme_colors['main-color']; ?>;
			}	
			a.splite_sideEnquiry {
				background: <?php echo $side_button['background-color']; ?>;				
			}
			
			<?php if( $submit_button['background-color'] != '' ) { ?>
				#splite_popup_box input.wpcf7-form-control.wpcf7-submit {
					background: <?php echo $submit_button['background-color']; ?>;
					letter-spacing: 1px;
					padding: 10px 15px;  
					text-align: center;
					border: 0; 
					box-shadow: none;   
				}
			<?php } ?>
			#splite_popup_title {
				color: <?php echo $heading_typography['color']; ?>;
				font-family: <?php echo $heading_typography['font-family']; ?>;
				font-size: <?php echo $heading_typography['font-size']; ?>;
				font-weight: <?php echo $heading_typography['font-weight']; ?>;
				line-height: <?php echo $heading_typography['line-height']; ?>;
			}
			#splite_popup_description {
				color: <?php echo $cta_typography['color'] ; ?>;
				font-family: <?php echo $cta_typography['font-family']; ?>;
				font-size: <?php echo $cta_typography['font-size']; ?>;
				font-weight: <?php echo $cta_typography['font-weight']; ?>;
				line-height: <?php echo $cta_typography['line-height']; ?>;
				text-align: <?php echo $cta_typography['text-align']; ?>;
			}
			a.splite_sideEnquiry {
				color: <?php echo $side_typo_color; ?>;
				font-family: <?php echo $side_typo_font_family; ?>;
				font-size: <?php echo $side_typo_font_size; ?>;
				font-weight: <?php echo $side_typo_font_weight; ?>;
				line-height: <?php echo $side_typo_line_height; ?>;
			}
			#splite_popup_box .wpcf7-form-control.wpcf7-submit {				
				color: <?php echo $submit_typo_color; ?>;
				font-family: <?php echo $submit_typo_font_family; ?>;
				font-size: <?php echo $submit_typo_font_size; ?>;
				font-weight: <?php echo $submit_typo_font_weight; ?>;
				line-height: <?php echo $submit_typo_line_height; ?>;
			}
			<?php echo $custom_css_code; ?>
		</style>
<?php	
	}
}


/**
 * Set Plugin URL Path (SSL/non-SSL)
 * @param  string - $path
 * @return string - $url 
 * Return https or non-https URL from path
 */
function splite_plugin_url( $path = '' ) {
	$url = plugins_url( $path, SPLITE_PLUGIN );

	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}

add_action( 'redux/page/splite_opts/enqueue', 'splite_addAndOverridePanelCSS' );
/////////////////////////////////////////
// Override Redux Panel CSS (farbtastci)
/////////////////////////////////////////
function splite_addAndOverridePanelCSS() {
	wp_register_style( 'redux-custom-css', splite_plugin_url( '/libs/css/redux-admin.css' ), '', time(), 'all' );    
	wp_enqueue_style('redux-custom-css');
}

/////////////////////////////////////////
// Add Scripts for the Popup
/////////////////////////////////////////
function splite_enqueue_popup_scripts() {
	if ( !is_admin() ) {
		wp_register_style( 'splite-css', splite_plugin_url( '/libs/css/styles.css' ) );
		wp_enqueue_style( 'splite-css' ); 
		wp_register_style( 'splite-animate', splite_plugin_url( '/libs/css/animate.css' ) );
		wp_enqueue_style( 'splite-animate' ); 
		wp_register_script( 'nicescroll-js', splite_plugin_url( '/libs/js/jquery.nicescroll.min.js' ) );
		wp_enqueue_script( 'nicescroll-js' ); 
		
		wp_register_script( 'splite-js', splite_plugin_url( '/libs/js/custom.js' ) );
		wp_enqueue_script( 'splite-js' ); 
	}
}


/**
 * Check Form Availability
 * @param  int - ID of the CF7 Form
 * @return string - message for admin or front-end user
 * If the Form ID is not available for not valid, return appropriate message
 */
function splite_check_form_id($cf7_id) {
	
	if( is_user_logged_in()  AND current_user_can('manage_options') )
		$user_is_admin = true; 
	
	$message = '';
	if( empty($cf7_id)) {
		if( isset($user_is_admin) ) { $message = __('No form choosen. Please select a form from <a target="_blank" href="'.admin_url('admin.php?page=sppro_options').'">plugin options</a>.'); }
		else { $message = __('Form is not available. Please visit our contact page.'); }		
	}
	else {
		$post_type = get_post_type($cf7_id);
		if( !absint($cf7_id) OR ($post_type != 'wpcf7_contact_form') OR !is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
			if( isset($user_is_admin) ) { $message = __('Invalid Form ID. Please select a form from <a target="_blank" href="'.admin_url('admin.php?page=sppro_options').'">plugin options</a>.'); }
			else { $message = __('Form is temporarily not available. Please visit our contact page.'); }
		}
	}
	
	return $message;
}

?>