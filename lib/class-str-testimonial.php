<?php
/*
 *  Define all Simple Testimonial Rutator Functions
 * */

/*
 Create a new Simple Testimonial Rutator Post Type
*/

//Define action for register to "Simple Testimonial Rutator" Post Type
add_action( 'init', 'register_str_testimonial_post_type' );

function register_str_testimonial_post_type() {
	//define array argument
	register_post_type( 'str_testimonial',
		array(
			'labels' => array(
				'name' => __( 'Simple Testimonials' ),
				'singular_name' => __( 'Simple Testimonial' )
			),
		  'public' => true,
		  'has_archive' => true,
          'supports' => array(
                           'title',
                           'editor',
                           'custom-fields',
                           'thumbnail')
		)
	);
}



/*
 * Change the "Featured Image" meta box title
 * 
**/
add_action( 'add_meta_boxes', 'str_testimonial_change_featured_meta_boxes_title');

function str_testimonial_change_featured_meta_boxes_title() {
 
	if ( $_GET['post_type'] === 'str_testimonial' ) {
		//Remove the exist Featured Image Metabox Div
		remove_meta_box( 'postimagediv', 'str_testimonial', 'side' );
		//add new metaboxes DIV
		add_meta_box( 'postimagediv', __('Featured Image (author image)'), 'post_thumbnail_meta_box', 'str_testimonial', 'side', 'low' );
	}


}


//Define the Action for change title of "Featured Image" to "Author Image"
add_filter('gettext', 'str_testimonial_change_add_psot_title', 20, 3);
/*
 * Change the text in the admin for my custom post type
 * 
**/
function str_testimonial_change_add_psot_title($newContent,$oldContent) {
  
  if($_GET['post_type'] == 'str_testimonial')  {
    //make the changes to the text
       if($oldContent=='Add New Post'):
          $newContent = __( 'Add New Testimonial');
        endif;
        
       if($oldContent=='Enter title here'):
          $newContent = __( 'Enter author name here');
        endif;
        //add more items
   }
   return $newContent;
}

/*
 * 
 * Add Some Extra Meta boxes
 * 
*/
 
//Define Action for add to meta boxes

add_action( 'add_meta_boxes', 'str_testimonial_add_extra_meta_box');
function str_testimonial_add_extra_meta_box() {
		add_meta_box(
			'str_testimonial',
			__( 'Simple Testimonial Rutator Extra Information', 'str_testimonial' ),
			'str_testimonial_meta_box_callback','str_testimonial');
}
/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function str_testimonial_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'str_testimonial_meta_box', 'str_testimonial_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$author = get_post_meta( $post->ID, '_str_testimonial_author', true );
	$designation = get_post_meta( $post->ID, '_str_testimonial_designation', true );
	$url = get_post_meta( $post->ID, '_str_testimonial_url', true );
    $str_html='<table><tr><th>&nbsp;</th><td>&nbsp;</td></tr>';
	$str_html .='<tr><th align="left"><label for="str_testimonial_designation">Author Role:</label></th><td><input type="text" id="str_testimonial_designation" name="str_testimonial_designation" placeholder="enter author role" value="' . esc_attr( $designation ) . '" size="50" /></td></tr>';
	
	$str_html.='<tr><th align="left"><label for="str_testimonial_url">URL:</label></th><td><input type="text" id="str_testimonial_url" name="str_testimonial_url" value="' . esc_attr( $url ) . '" size="50" placeholder="http://"/></td></tr>';
    
    $str_html.='<tr><th>&nbsp;</th><td>&nbsp;</td></tr></table>';
    
    echo $str_html;
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function str_testimonial_save_meta_box_data( $post_id ) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['str_testimonial_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['str_testimonial_meta_box_nonce'], 'str_testimonial_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'str_testimonial' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, its safe for us to save the data now. */
	
	
	// Make sure that it is set.
	if ( ! isset( $_POST['str_testimonial_designation'] ) ) {
		return;
	}

	// Sanitize designation input.
	$my_data = sanitize_text_field( $_POST['str_testimonial_designation'] );

	// Update the designation in the database.
	update_post_meta( $post_id, '_str_testimonial_designation', $my_data );
	
	// Make sure that it is set.
	if ( ! isset( $_POST['str_testimonial_url'] ) ) {
		return;
	}
	// Sanitize url input.
	$my_data = sanitize_text_field( $_POST['str_testimonial_url'] );
	// Update the url meta field in the database.
	update_post_meta( $post_id, '_str_testimonial_url', $my_data );
}
add_action( 'save_post', 'str_testimonial_save_meta_box_data' );
?>
