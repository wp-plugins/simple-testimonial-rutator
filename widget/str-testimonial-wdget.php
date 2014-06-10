<?php
/*
 * Register Simple Testimonial Rotator 
 * */
class str_testimonials_widget extends WP_Widget {

    
        function str_testimonials_widget() {
            $widget_ops = array('description' => __('Display auto rutate testimonials in your sidebar', 'Simple Testimonial Rotator'));
            $this->WP_Widget('str_testimonials', __('Simple Testimonial Rotator'), $widget_ops);
        }
       
        // Display Widget
        function widget($args, $instance) {
            extract($args);
            $title = esc_attr($instance['title']);

            echo $before_widget.$before_title.$title.$after_title;

                get_str_testimonials_content();

            echo $after_widget;
        }

        // When Widget Control Form Is Posted
        function update($new_instance, $old_instance) {
            if (!isset($new_instance['submit'])) {
                return false;
            }
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            return $instance;
        }

        // DIsplay Widget Control Form
        function form($instance) {
            global $wpdb;
            $instance = wp_parse_args((array) $instance, array('title' => __('str_testimonials', 'str_testimonials')));
            $title = esc_attr($instance['title']);
    ?>

    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'Simple Testimonial Widget'); ?>
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>

    <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
    <?php
        }
    }

    ### Function: Init Simple Testiomonial Rotator Widget
    add_action('widgets_init', 'str_testiomonials_init');
    function str_testiomonials_init() {
        register_widget('str_testimonials_widget');
    }
    
    
 add_action('wp_head','str_load_inline_js');

function str_load_inline_js()
{
	$getOptions =get_str_testimonials_options();
	$delayTimeVal=$getOptions['str_speed'];
	if($delayTimeVal!=''):$delayTime=$delayTimeVal;else:$delayTime='5000'; endif;
	
	$jscnt ="<script>
		jQuery(function() {
			jQuery('#str_testimonial > div:gt(0)').hide();
			setInterval(function() { 
			  jQuery('#str_testimonial > div:first')
			    .fadeOut(1000)
			    .next()
			    .fadeIn(1000)
			    .end()
			    .appendTo('#str_testimonial');
			},  ".$delayTimeVal.");
		});
	</script>";
	
	echo $jscnt;
	}	   

function get_str_testimonials_content() {
//get the post
$getOptions =get_str_testimonials_options();
if($getOptions['str_sortby']!=''):$str_sortBy=$getOptions['str_sortby']; else: $str_sortBy='title'; endif;
if($getOptions['str_orderby']!=''):$str_orderby=$getOptions['str_orderby']; else: $str_orderby='ASC'; endif;
$str_query = new WP_Query('post_type=str_testimonial&post_status=publish&orderby='.$str_sortBy.'&order='.$str_orderby);

 // Restore global post data stomped by the_post().

?>
<div id="str_testimonial">
<?php
if( $str_query->have_posts() ) {
  while ($str_query->have_posts()) : $str_query->the_post(); ?>
    <div>
	    <blockquote class="style1">
			<div>
				<?php
				//get the testimonial content
				 the_content();?>
			</div>
			</blockquote>
	     <div  class="str_author">
			<?php 
			//get the author image
			echo get_the_post_thumbnail(get_the_ID(), array(50,40) );?>  
			 <span>
			<?php
			if(get_post_meta(get_the_ID(), '_str_testimonial_url', true)==''): 
			 //get author title
			 the_title();
			 else:
			 echo '<a href="'.get_post_meta(get_the_ID(), '_str_testimonial_url', true).'" target="_blank">'.get_the_title().'</a>';
			 endif;
			  ?>
			 </span>
			  <?php if(get_post_meta(get_the_ID(), '_str_testimonial_designation', true)!=''): echo '<span class="authorRole">'.get_post_meta(get_the_ID(), '_str_testimonial_designation', true).'</span>';endif; ?>
			 </div>
	   </div>
<?php
endwhile;
} 
wp_reset_query();
?>
	</div>
<?php

}
?>
