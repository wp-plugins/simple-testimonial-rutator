<?php
/*
Plugin Name: Simple Testimonial Rotator
Plugin URI: http://raghunathgurjar.wordpress.com
Description: "Simple Testimonial Rotator" is a very simple plugins for add to testimonials on your site. 
Version: 1.3
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
	register_setting('str_testimonial_options','str_viewall');
	register_setting('str_testimonial_options','str_viewall_page');
} 




/* 

*Display the Options form for Custom Tweets 

*/

function str_testimonials_admin_option_page(){ ?>
	<div> 
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
				<td rowspan="10" valign="top" style="border-left: 1px solid rgb(204, 204, 204); padding-left: 20px;">
	<h2>Plugin Author:</h2>
					<div style="font-size: 14px;">
	<img src="<?php echo  plugins_url( 'images/raghu.jpg' , __FILE__ );?>" width="100" height="100"><br><a href="http://raghunathgurjar.wordpress.com" target="_blank">Raghunath Gurjar</a><br><br>Author Blog <a href="http://raghunathgurjar.wordpress.com" target="_blank">http://raghunathgurjar.wordpress.com</a>
	<br><br><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4" target="_blank" style="font-size: 17px; font-weight: bold;">Donate for this plugin</a><br><br>
	Other Plugins:<br>
	<ul>
		<li><a href="http://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom share buttons with floating sidebar</a></li>
		</ul>
	</div></td>
			</tr>	
			<tr>
				<th><?php echo 'Delay Time:';?></th>
				<td>
					<input type="text" id="str_speed" name="str_speed" value="<?php echo esc_attr(get_option('str_speed')); ?>" size="5"/><br>Default time is 5000
				</td>
			</tr>
			<tr>
				<th><?php echo 'Sort By:';?></th>
				<td>
				<select id="str_sortby" name="str_sortby" >
				<option value="title" <?php if(get_option('str_sortby')=='title'){echo 'selected="selected"';}?>>Title</option>
				<option value="date" <?php if(get_option('str_sortby')=='date'){echo 'selected="selected"';}?>>Date</option>
				</select>
				</td>
			</tr>
			<tr>
				<th><?php echo 'Order By:';?></th>
				<td>
				<select id="str_orderby" name="str_orderby" >
				<option value="ASC" <?php if(get_option('str_orderby')=='ASC'){echo 'selected="selected"';}?>>ASC</option>
				<option value="DESC" <?php if(get_option('str_orderby')=='DESC'){echo 'selected="selected"';}?>>DESC</option>
				</select>
				</td>
			</tr>
			<tr>
				<th><?php echo 'View All:';?></th>
				<td>
					<input type="checkbox" id="str_viewall" name="str_viewall" <?php if(get_option('str_viewall')!=''):echo 'checked="checked"';endif; ?> size="5" value="1"/>Show the "View All" links in testiomonial sidebar
					<?php  if(get_option('str_viewall')!=''):?><br>
						<input type="text" id="str_viewall_page" name="str_viewall_page" value="<?php echo esc_attr(get_option('str_viewall_page')); ?>" size="25" placeholder="Enter testiomonals list page url"/><br>
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr><td colspan="3"><strong>Shortcode</strong></td></tr>
			<tr><td colspan="3">[str-random] for add to testimonial rotator on any page</td></tr>
			<tr><td colspan="3">[str_testimonials] for publish all testimonials on a single page</td></tr>
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
	delete_option('str_viewall');
	delete_option('str_viewall_page');
} 



?>
