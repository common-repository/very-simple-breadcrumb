<?php
/* 
Plugin Name: Very Simple Breadcrumb
Plugin URI: http://sumonhasan.com/plugins/very-simple-breadcrumb/
Description: A simple wordpress breadcrumb plugin. It's very easy to use with a simple shortcode. Call the the_vs_breadcrumb() function in single.php file and others files where you want to display the breadcrumbs on your WordPress site. You can change color & other setting from <a href="options-general.php?page=very-simple-breadcrumb">Breadcrumb Options </a>
Version: 1.0
Author: Sumon Hasan
Author URI: http://www.sumonhasan.com
*/
// Set-up
define('VERY_SIMPLE_BREADCRUMB', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function very_simple_breadcrumb_latest_jquery()
{
	wp_enqueue_script('jquery');
}
add_action('init', 'very_simple_breadcrumb_latest_jquery');


// Very Simple Breadcrumb options
function very_simple_breadcrumb_options_panel()  
{  
	add_options_page('Very Simple Breadcrumb', 'Very Simple Breadcrumb', 'manage_options', 'very-simple-breadcrumb','very_simple_breadcrumb_options_framwrork');  
}  
add_action('admin_menu', 'very_simple_breadcrumb_options_panel');

// Very Simple Breadcrumb wp color picke
add_action( 'admin_enqueue_scripts', 'very_simple_breadcrumb_color_pickr_function' );
function very_simple_breadcrumb_color_pickr_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/color-pickr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Default options values
$very_simple_breadcrumb_default_options = array(
	'home_link_title' => 'Home',
	'breadcrumb_arrow' => '&#10095;',
	'breadcrumb_color' => '428bca',
	'breadcrumb_bg_color' => '#ccc',
	'breadcrumb_class_name' => 'breadcrumb',
	'padding_top' => '10',
	'padding_left' => '15',
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function very_simple_breadcrumb_settings_register() {
	// Register settings and call sanitation functions
	register_setting( 'very_simple_breadcrumb_p_options', 'very_simple_breadcrumb_default_options', 'very_simple_breadcrumb_validate_options' );
}

add_action( 'admin_init', 'very_simple_breadcrumb_settings_register' );

// Function to generate options page
function very_simple_breadcrumb_options_framwrork() {
	global $very_simple_breadcrumb_default_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; ?>

	<div class="wrap">

	<h2><?php _e('Very Simple Breadcrumb Options', 'vs-breadcrumb'); ?></h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
	<?php $settings = get_option( 'very_simple_breadcrumb_default_options', $very_simple_breadcrumb_default_options ); ?>
	<?php settings_fields( 'very_simple_breadcrumb_p_options' ); ?>
		<table class="form-table">

		<tr valign="top">
			<th scope="row"><label for="home_link_title"><?php _e('Main page url title', 'vs-breadcrumb'); ?></label></th>
			<td>
				<input id="home_link_title" type="text" name="very_simple_breadcrumb_default_options[home_link_title]" value="<?php echo stripslashes($settings['home_link_title']); ?>" class="height-width" /><p class="description"><?php _e('Example: Home, মূলপাতা,  κύριος', 'vs-breadcrumb'); ?></p>
			</td>
		</tr>	
		<tr valign="top">
			<th scope="row"><label for="padding_top"><?php _e('Padding Top & Bottom', 'vs-breadcrumb'); ?></label></th>
			<td>
				<input id="padding_top" type="number" name="very_simple_breadcrumb_default_options[padding_top]" value="<?php echo stripslashes($settings['padding_top']); ?>" class="height-width" /><p class="description"><?php _e("It's must count by px value. Example: 10", 'vs-breadcrumb'); ?></p>
			</td>
		</tr>	
		<tr valign="top">
			<th scope="row"><label for="padding_left"><?php _e("Padding Left & Right", 'vs-breadcrumb'); ?></label></th>
			<td>
				<input id="padding_left" type="number" name="very_simple_breadcrumb_default_options[padding_left]" value="<?php echo stripslashes($settings['padding_left']); ?>" class="height-width" /><p class="description"><?php _e("It's must count by px value. Example: 15", 'vs-breadcrumb'); ?></p>
			</td>
		</tr>		
		<tr valign="top">
			<th scope="row"><label for="breadcrumb_class_name"><?php _e("Breadcrumb css class name", 'vs-breadcrumb'); ?></label></th>
			<td>
				<input id="breadcrumb_class_name" type="text" name="very_simple_breadcrumb_default_options[breadcrumb_class_name]" value="<?php echo stripslashes($settings['breadcrumb_class_name']); ?>" class="height-width" /><p class="description"><?php _e('Default class name is breadcrumb. If you want you can change class from here.', 'vs-breadcrumb'); ?></p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="breadcrumb_arrow"><?php _e('Breadcrumb arrows', 'vs-breadcrumb'); ?></label></th>
			<td>
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#x2192;') echo 'checked="checked"'; ?> value="&#x2192;" />&#x2192;
				
				<input checked="checked" type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8596;') echo 'checked="checked"'; ?> value="&#8596;" />&#8596;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8611;') echo 'checked="checked"'; ?> value="&#8611;" />&#8611;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8608;') echo 'checked="checked"'; ?> value="&#8608;" />&#8608;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8614;') echo 'checked="checked"'; ?> value="&#8614;" />&#8614;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8640;') echo 'checked="checked"'; ?> value="&#8640;" />&#8640;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8649;') echo 'checked="checked"'; ?> value="&#8649;" />&#8649;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8652;') echo 'checked="checked"'; ?> value="&#8652;" />&#8652;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8655;') echo 'checked="checked"'; ?> value="&#8655;" />&#8655;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8658;') echo 'checked="checked"'; ?> value="&#8658;" />&#8658;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8667;') echo 'checked="checked"'; ?> value="&#8667;" />&#8667;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#x21e2;') echo 'checked="checked"'; ?> value="&#x21e2;" />&#x21e2;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#x21e8;') echo 'checked="checked"'; ?> value="&#x21e8;" />&#x21e8;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#x21f0;') echo 'checked="checked"'; ?> value="&#x21f0;" />&#x21f0;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#8702;') echo 'checked="checked"'; ?> value="&#8702;" />&#8702;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10230;') echo 'checked="checked"'; ?> value="&#10230;" />&#10230;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10233;') echo 'checked="checked"'; ?> value="&#10233;" />&#10233;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10236;') echo 'checked="checked"'; ?> value="&#10236;" />&#10236;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10238;') echo 'checked="checked"'; ?> value="&#10238;" />&#10238;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10509;') echo 'checked="checked"'; ?> value="&#10509;" />&#10509;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10511;') echo 'checked="checked"'; ?> value="&#10511;" />&#10511;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10518;') echo 'checked="checked"'; ?> value="&#10518;" />&#10518;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10596;') echo 'checked="checked"'; ?> value="&#10596;" />&#10596;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10132;') echo 'checked="checked"'; ?> value="&#10132;" />&#10132;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10137;') echo 'checked="checked"'; ?> value="&#10137;" />&#10137;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10139;') echo 'checked="checked"'; ?> value="&#10139;" />&#10139;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10140;') echo 'checked="checked"'; ?> value="&#10140;" />&#10140;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10141;') echo 'checked="checked"'; ?> value="&#10141;" />&#10141;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10142;') echo 'checked="checked"'; ?> value="&#10142;" />&#10142;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10144;') echo 'checked="checked"'; ?> value="&#10144;" />&#10144;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10145;') echo 'checked="checked"'; ?> value="&#10145;" />&#10145;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10146;') echo 'checked="checked"'; ?> value="&#10146;" />&#10146;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10148;') echo 'checked="checked"'; ?> value="&#10148;" />&#10148;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10149;') echo 'checked="checked"'; ?> value="&#10149;" />&#10149;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10151;') echo 'checked="checked"'; ?> value="&#10151;" />&#10151;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10154;') echo 'checked="checked"'; ?> value="&#10154;" />&#10154;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10155;') echo 'checked="checked"'; ?> value="&#10155;" />&#10155;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10157;') echo 'checked="checked"'; ?> value="&#10157;" />&#10157;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10162;') echo 'checked="checked"'; ?> value="&#10162;" />&#10162;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10163;') echo 'checked="checked"'; ?> value="&#10163;" />&#10163;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10165;') echo 'checked="checked"'; ?> value="&#10165;" />&#10165;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10168;') echo 'checked="checked"'; ?> value="&#10168;" />&#10168;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10170;') echo 'checked="checked"'; ?> value="&#10170;" />&#10170;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10171;') echo 'checked="checked"'; ?> value="&#10171;" />&#10171;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10172;') echo 'checked="checked"'; ?> value="&#10172;" />&#10172;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10173;') echo 'checked="checked"'; ?> value="&#10173;" />&#10173;
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10174;') echo 'checked="checked"'; ?> value="&#10174;" />&#10174;
				
				<input type="radio" id="breadcrumb_arrow" name="very_simple_breadcrumb_default_options[breadcrumb_arrow]" <?php if($settings['breadcrumb_arrow'] == '&#10095;') echo 'checked="checked"'; ?> value="&#10095;" />&#10095;
				<p class="description"><?php _e('Choose your breadcrumb arrow from here', 'vs-breadcrumb'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="breadcrumb_color"><?php _e('Breadcrumb Text Color', 'vs-breadcrumb'); ?></label></th>
			<td>
				<input id="breadcrumb_color" type="text" name="very_simple_breadcrumb_default_options[breadcrumb_color]" value="<?php echo stripslashes($settings['breadcrumb_color']); ?>" class="my-color-field" /><p class="description"><?php _e('Choose your breadcrumb  text color.', 'vs-breadcrumb'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="breadcrumb_bg_color"><?php _e('Breadcrumb  background color.', 'vs-breadcrumb'); ?></label></th>
			<td>
				<input id="breadcrumb_bg_color" type="text" name="very_simple_breadcrumb_default_options[breadcrumb_bg_color]" value="<?php echo stripslashes($settings['breadcrumb_bg_color']); ?>" class="my-color-field" /><p class="description"><?php _e('Choose your breadcrumb background color.', 'vs-breadcrumb'); ?></p>
			</td>
		</tr>

	
		
	</table>
	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>

	</form>
	</div>

	<?php
}

function very_simple_breadcrumb_validate_options( $input ) {
	global $very_simple_breadcrumb_default_options;

	$settings = get_option( 'very_simple_breadcrumb_default_options', $very_simple_breadcrumb_default_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['home_link_title'] = wp_filter_post_kses( $input['home_link_title'] );
	$input['breadcrumb_arrow'] = wp_filter_post_kses( $input['breadcrumb_arrow'] );

	
	

	return $input;
}

endif;  // EndIf is_admin()



/* breadcrumbs */

function the_vs_breadcrumb() {
	
global $very_simple_breadcrumb_default_options; $very_simple_breadcrumb_ebtt_settings = get_option( 'very_simple_breadcrumb_default_options', $very_simple_breadcrumb_default_options ); 

    echo '<a href="'.home_url().'" rel="nofollow">'.$very_simple_breadcrumb_ebtt_settings['home_link_title'].'</a>';
	
    if (is_category() || is_single()) {
        echo "&nbsp;&nbsp;".$very_simple_breadcrumb_ebtt_settings['breadcrumb_arrow']."&nbsp;&nbsp;";
        the_category(' &bull; ');
            if (is_single()) {
                echo " &nbsp;&nbsp;".$very_simple_breadcrumb_ebtt_settings['breadcrumb_arrow']."&nbsp;&nbsp; ";
                the_title();
            }
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;".$very_simple_breadcrumb_ebtt_settings['breadcrumb_arrow']."&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;&nbsp;".$very_simple_breadcrumb_ebtt_settings['breadcrumb_arrow']."&nbsp;&nbsp;Search Results for... ";
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
}


add_action( 'wp_head','wpcc_colorpicker_generate' );

function wpcc_colorpicker_generate()
{
	// Get options
	 global $very_simple_breadcrumb_default_options; $very_simple_breadcrumb_ebtt_settings = get_option( 'very_simple_breadcrumb_default_options', $very_simple_breadcrumb_default_options ); 


	// Finally, print colorpicker field options
	echo '<style type="text/css">';
	?>
		.<?php echo $very_simple_breadcrumb_ebtt_settings['breadcrumb_class_name']; ?> {
			color: <?php echo $very_simple_breadcrumb_ebtt_settings['breadcrumb_color']; ?>!important;
			padding: <?php echo $very_simple_breadcrumb_ebtt_settings['padding_top']; ?>px <?php echo $very_simple_breadcrumb_ebtt_settings['padding_left']; ?>px;
			margin-bottom: 20px;
			list-style: none;
			background-color:<?php echo $very_simple_breadcrumb_ebtt_settings['breadcrumb_bg_color']; ?>!important;
			border-radius: 4px;
		}
		.<?php echo $very_simple_breadcrumb_ebtt_settings['breadcrumb_class_name']; ?> a {
			color: <?php echo $very_simple_breadcrumb_ebtt_settings['breadcrumb_color']; ?>!important;
			text-decoration: none;
		}
 <?php		
	echo '</style>';
 }

