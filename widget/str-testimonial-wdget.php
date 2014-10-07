<?php
/**
 * 
 * Register Simple Testimonial Rotator 
 */

//check widget active or not

add_action( 'wp_enqueue_scripts', 'str_testimonials_scripts' );
add_action('wp_head','str_load_inline_js');



//register style and scriptit files
function str_testimonials_scripts() {
wp_enqueue_script( 'jquery' ); // wordpress jQuery
wp_register_style( 'simple_testimonial_rotator_widget_style', plugins_url( 'simple-testimonial-rotator/widget/str-testimonial-widget.css' ) );
wp_enqueue_style( 'simple_testimonial_rotator_widget_style' );
}

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

/*
 * Load jQuery code in header   
 */

function str_load_inline_js()
{
	$getOptions =get_str_testimonials_options();
	$delayTimeVal=$getOptions['str_speed'];
	$delayTimeVal=5000;
	if($delayTimeVal!=''){$delayTime=$delayTimeVal;}else{$delayTime=5000; };
	
	$jscnt ="<script>
		jQuery(function() {
			jQuery('#str_testimonial > div:gt(0)').hide();
			setInterval(function() { 
			  jQuery('#str_testimonial > div:first')
			    .fadeOut(1000)
			    .next()
			    .fadeIn(1000)
			    .end()
			    .appendTo('#str_testimonial')
			},  ".$delayTimeVal.")
		})
	</script>";
	
	echo $jscnt;
	}	   

function get_str_testimonials_content() {
/** Get Testimonial Content*/

$getOptions =get_str_testimonials_options();
if($getOptions['str_sortby']!=''):$str_sortBy=$getOptions['str_sortby']; else: $str_sortBy='title'; endif;
if($getOptions['str_orderby']!=''):$str_orderby=$getOptions['str_orderby']; else: $str_orderby='ASC'; endif;
$str_query = new WP_Query('post_type=str_testimonial&post_status=publish&orderby='.$str_sortBy.'&order='.$str_orderby);

$effect=$getOptions['str_effect'];

if($effect==''){$effect='fade';}

$delayTimeVal=$getOptions['str_speed'];
$delayTimeVal=5000;
if($delayTimeVal!=''){$delayTime=$delayTimeVal;}else{$delayTime=5000; };

if($getOptions['str_content_limit']!=''):$content_limit=$getOptions['str_content_limit'];else:$content_limit="400";endif;

 // Restore global post data stomped by the_post().
$script="<script type='text/javascript'>
jQuery(document).ready(function() {
    jQuery('#strTestimonialsWidget').cycle({
        fx: '".$effect."', // choose your transition type, ex: fade, scrollUp, scrollRight, shuffle
        speed:".$delayTime.", 
		delay:0,
		/*fit:true,*/
		
     });
});
</script>"; 
 
$strContent='<div id="strWidget" class="strWidget">'; 
$strContent.=$script;
$strContent.='<div id="strTestimonialsWidget" class="strTestimonial">';
if( $str_query->have_posts() ) {
  while ($str_query->have_posts()) : $str_query->the_post();
  
if(strlen(strip_tags(get_the_content())) > $content_limit){ $moreContent='...';}else{$moreContent='';}
  
  if(get_post_meta(get_the_ID(), '_str_testimonial_url', true)==''): 
			 //get author title
			 $authorName=get_the_title();
			 else:
			$authorName='<a href="'.get_post_meta(get_the_ID(), '_str_testimonial_url', true).'" target="_blank">'.get_the_title().'</a>';
			 endif;
		
 if(get_post_meta(get_the_ID(), '_str_testimonial_designation', true)!=''): 
 $authorDesignation='<span class="designation">'.get_post_meta(get_the_ID(), '_str_testimonial_designation', true).'</span>';
 else:
 $authorDesignation='';
 endif; 
 	 
  $strContent.='<blockquote>';
    
  $strContent.='<p><span class="laquo">&nbsp;</span>'.substr(strip_tags(get_the_content()),0,$content_limit).$moreContent.'<span class="raquo">&nbsp;</span></p>';

  $strContent.='<cite>- '.$authorName.$authorDesignation.'</cite>';
			  
  $strContent.='</blockquote>';
  
  endwhile;
} 
wp_reset_query();
$strContent.='</div>';

if($getOptions['str_viewall']==1): 
$strContent.='<div class="view-all"><a href="'.$getOptions['str_viewall_page'].'">View All</a></div>';
endif; 
$strContent.='</div>';
echo $strContent;
}
?>
