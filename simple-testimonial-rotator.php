<?php
/*
Plugin Name: Simple Testimonial Rotator
Plugin URI: http://raghunathgurjar.wordpress.com
Description: "Simple Testimonial Rotator" is a very simple plugins for add to testimonials on your site. 
Version: 1.0
Text Domain: raghunath
Author: Raghunath Gurjar
Author URI: http://www.facebook.com/raghunathprasadgurjar
*/

/* 
* Setup Admin menu item 
*/
//Admin "Simple Testimonial Rotator" Menu Item
add_action('admin_menu','str_testimonials_menu');

function str_testimonials_menu(){

	add_options_page('Simple Testimonial Rotator','Simple Testimonial Rotator','manage_options','str-testimonials-plugin','str_testimonials_admin_option_page');

}

//Define Action for register "Simple Testimonial Rotator" Options
add_action('admin_init','str_testimonials_init');


//Register "Simple Testimonial Rotator" options
function str_testimonials_init(){

	register_setting('str_testimonial_options','str_effect');

	register_setting('str_testimonial_options','str_speed');

	register_setting('str_testimonial_options','str_sortby');
	
	register_setting('str_testimonial_options','str_orderby');
} 




/* 

*Display the Options form for Custom Tweets 

*/

function str_testimonials_admin_option_page(){ ?>

	<div style="width: 50%; padding: 10px; border: 1px dashed #ccc; margin: 10px;"> 

	<h2>Simple Testimonial Rutator Settings</h2>
	
	<p>Please fill all options value.</p>

<!-- Start Options Form -->
	<form action="options.php" method="post" id="str-testimonial-admin-form">
		<table class="simple-testimonial-rotator">
			<tr>
				<th>Choose Effect</th>
				<td >
				<select id="str_effect" name="str_effect">
				<option value="fade" <?php if(get_option('str_effect')=='fade'){echo 'selected="selected"';}?>>fade</option>
				</select>
				</td>
				<td rowspan="10" valign="top" style="border-left: 1px solid rgb(204, 204, 204); padding-left: 20px;"><div style="width: 100%; font-size: 24px;">
	<br>Author:<a href="http://raghunathgurjar.wordpress.com">Raghunath Gurjar</a>
	<br><br><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4" target="_blank" style="font-size: 17px; font-weight: bold;">Donate for this plugin</a>
	</div></td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>		
			<tr>
				<th><?php echo 'Delay Time:';?></th>
				<td>
					<input type="text" id="str_speed" name="str_speed" value="<?php echo esc_attr(get_option('str_speed')); ?>" size="5"/><br>Default time is 5000
				</td>
			</tr>
<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<th><?php echo 'Sort By:';?></th>
				<td>
				<select id="str_sortby" name="str_sortby" >
				<option value="title" <?php if(get_option('str_sortby')=='title'){echo 'selected="selected"';}?>>Title</option>
				<option value="date" <?php if(get_option('str_sortby')=='date'){echo 'selected="selected"';}?>>Date</option>
				</select>
				</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<th><?php echo 'Order By:';?></th>
				<td>
				<select id="str_orderby" name="str_orderby" >
				<option value="ASC" <?php if(get_option('str_orderby')=='ASC'){echo 'selected="selected"';}?>>ASC</option>
				<option value="DESC" <?php if(get_option('str_orderby')=='DESC'){echo 'selected="selected"';}?>>DESC</option>
				</select>
				</td>
			</tr>
				<tr><td colspan="3">&nbsp;</td></tr>		
			<tr>
				<th>&nbsp;</th>
				<td><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>		
		</table>
    <?php settings_fields('str_testimonial_options'); ?>
	</form>
<!-- End Options Form -->
	</div>

<?php
}
// return the Simple Testimonial Rutator settings
	function get_str_testimonials_options() {
		global $wpdb;
		$ctOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'str_%'");
								
		foreach ($ctOptions as $option) {
			$ctOptions[$option->option_name] =  $option->option_value;
		}
	
		return $ctOptions;	
	}
	
/*
-----------------------------------------------------------------------------------------------
                        Simple Testimonials Rutator Posts                              
-----------------------------------------------------------------------------------------------
*/


//Include Post files
include dirname( __FILE__ ) .'/lib/class-str-testimonial.php';



/*
-----------------------------------------------------------------------------------------------
                              Simple Testimonials Rutator Widget  
-----------------------------------------------------------------------------------------------
*/

//register style and scriptit files
function str_testimonials_scripts() {
wp_enqueue_script( 'jquery' ); // wordpress jQuery
wp_register_style( 'simple_testimonial_rotator_widget_style', plugins_url( 'simple-testimonial-rotator/widget/str-testimonial-widget.css' ) );
wp_enqueue_style( 'simple_testimonial_rotator_widget_style' );
}

add_action( 'wp_enqueue_scripts', 'str_testimonials_scripts' );


//Include Widget files
include dirname( __FILE__ ) .'/widget/str-testimonial-wdget.php';

/* 

*Delete the options during disable the plugins 

*/

if( function_exists('register_uninstall_hook') )

	register_uninstall_hook(__FILE__,'str_testimonial_uninstall');   

//Delete all Custom Tweets options after delete the plugin from admin
function str_testimonial_uninstall(){
	delete_option('str_effect');
	delete_option('str_speed');
	delete_option('str_sortby');
	delete_option('str_orderby');
} 



?>
