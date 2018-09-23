<?php

//set up 
//can i use these as a pluging ? i will test it by a plugin n remove it 

#dynamically supply title for page or post
add_theme_support( 'title-tag' );

#support feature images
add_theme_support( 'post-thumbnails' );



//includes



//action and filter hooks
function startwordpress_scripts() {

	#$name, $src, array $dependence = array(), $version = false, $media_used_for = 'all'
	wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/assests/css/bootstrap.min.css', array(), '3.3.6' );

	wp_enqueue_style( 'blog', get_template_directory_uri().'/assests/css/blog.css' );

	#$name, $src, array $dependence = array(), $version = false, $in_footer = false
	wp_enqueue_script( 'bootstrap', get_template_directory_uri().'/assests/js/bootstrap.min.js', array('jquery'), '3.3.6', true );
}
add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );



function startwordpres_google_font() {

	wp_register_style( 'OpenSans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' );
	wp_enqueue_style( 'OpenSans' );
}
//add google fonts....
add_action( 'wp_enqueue_scripts', 'startwordpres_google_font' );

#------------------------------------------------------------------------
# 			Code for theme options
#------------------------------------------------------------------------

# 02
function custom_setting_add_menu() {
	#$page_title, $menu_title, $capability, $menu_slug, $function, $icon_url = '', $position = null
	add_menu_page( 'Custom Settings', 'Custom Settings', 'manage_options', 'custom-settings', 'custom_settings_page', null, 99 );
	#now we code  the custom_settings_page function for our menu page....
}


# 03 a function of add_menu_page and root is admin_menu->custom_setting_add_menu
function custom_settings_page() { ?>

	<div class="wrap">
	    <h1>Custom Settings</h1>
		
		<form method="post" action="options.php">
			<?php

				#this will create some hidden input field in html form page.
				settings_fields( 'section' );
				#Prints out all settings sections added to a particular settings page
				do_settings_sections( 'theme-options' );
				#create a submit button...
				submit_button();

			?>
		</form>

	</div>

<?php }

# 06 ............................................
//twitter
function setting_twitter(){ ?>

	<input type="text" name="twitter" id="twitter" value="<?php echo get_option( 'twitter' ); ?>">

<?php }

//07 github
function setting_github(){ ?>

	<input type="text" name="github" id="github" value="<?php echo get_option( 'github' ); ?>">

<?php }

//08 github
function setting_fb(){ ?>

	<input type="text" name="fb" id="fb" value="<?php echo get_option( 'fb' ); ?>">

<?php }

# 05 ADMIN_INIT HOOK FUNCTION
function custom_settings_page_setup(){

	#$id, $title, $callback, $page
	add_settings_section( 'section', 'All Settings', null, 'theme-options' );


	//Now the form field we want to show that pages.......

	//01 -->twitter

	#$id, $title, $callback, $page, $section = 'default', $args = array()
	add_settings_field( 'twitter', 'Twitter Url', 'setting_twitter', 'theme-options', 'section' );

	#$option_group, $option_name, $args = array()
	register_setting( 'section', 'twitter' );

	//02 github account

	add_settings_field( 'github', 'GitHub Url', 'setting_github', 'theme-options', 'section' );
	register_setting( 'section', 'github' );

	//03 facebook account

	add_settings_field( 'fb', 'Facebook Url', 'setting_fb', 'theme-options', 'section' );
	register_setting( 'section', 'fb' );
}


# 04 Need another Hokk to work admin_menu (01) Hook Working.....
add_action( 'admin_init', 'custom_settings_page_setup' );

# 01 add a menu in admin and a page in admin panel
add_action( 'admin_menu', 'custom_setting_add_menu' );


#------------------------------------------------------------------------
# 			Code for theme options ends
#------------------------------------------------------------------------

function create_my_custom_post() {

	#$post_type, $args = array()
	register_post_type( 'my-custom-post',
		array(

			'labels'	=>	array(
				'name'			=>	__('My Custom Post'),
				'singular_name'	=>	__('My Custom Post')
			),
			'public'		=>	true,
			'has_archive'	=>	true,
			'supports'		=>	array(
			'title',
			'editor',
			'thumbnail',
			'custom-fields'
			)

	));
}
add_action( 'init', 'create_my_custom_post' );

//new example of custom post type..
function create_post_your_post(){
	register_post_type( 'your_post',
		array(
			'labels'	=> array(
				'name'	=>	__( 'Your Post' ),
			),
			'public'		=> 	true,
			'hierarchical'	=>	true,
			'has_archive'	=>	true,
			'supports'		=>	array(
				'title',
				'editor',
				'excerpt',
				'thumbnail'
			),
			'taxonomics'	=>	array(
				'post_tag',
				'category'
			)
	));

	register_taxonomy_for_object_type( 'category', 'your_post' );
	register_taxonomy_for_object_type( 'post_tag', 'your_post' );
}
add_action( 'init', 'create_post_your_post' );

//meta box code start
function add_your_fields_metabox(){
	add_meta_box(
		'your_fields_meta_box', //id
		'Your Fields', //title
		'show_your_fields_meta_box', //callback
		'your_post', //screen
		'normal', //context
		'high' //priority
	);
}

function show_your_fields_meta_box(){
	global $post;
	$meta = get_post_meta( $post->ID, 'your_fields', true );
?>
	<input type="hidden" name="your_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">
	
	<!-- All fields will go here -->
	<p>
		<label for="your_fields[text]">Input Text</label>
		<br>
		<input type="text" name="your_fields[text]" id="your_fields[text]" class="regulat-text" value="<?php echo @$meta['text']; ?>">
	</p>
	<p>
		<label for="your_fields[textarea]">Textarea</label>
		<br>
		<textarea name="your_fields[textarea]" id="your_fields[textarea]" rows="5" cols="30" style="width:500px;"><?php echo @$meta['textarea']; ?></textarea>
	</p>
	<p>
		<label for="your_fields[checkbox]">Checkbox
			<input type="checkbox" name="your_fields[checkbox]" value="checkbox" <?php if ( @$meta['checkbox'] === 'checkbox' ) echo 'checked'; ?>>
		</label>
	</p>
	<p>
		<label for="your_fields[select]">Select Menu</label>
		<br>
		<select name="your_fields[select]" id="your_fields[select]">
				<option value="option-one" <?php selected( @$meta['select'], 'option-one' ); ?>>Option One</option>
				<option value="option-two" <?php selected( @$meta['select'], 'option-two' ); ?>>Option Two</option>
		</select>
	</p>

	<p>
		<label for="your_fields[image]">Image Upload</label><br>
		<input type="text" name="your_fields[image]" id="your_fields[image]" class="meta-image regular-text" value="<?php echo @$meta['image']; ?>">
		<input type="button" class="button image-upload" value="Browse">
	</p>
	<div class="image-preview"><img src="<?php echo @$meta['image']; ?>" style="max-width: 250px;"></div>

	<script>
	    jQuery(document).ready(function ($) {
	      // Instantiates the variable that holds the media library frame.
	      var meta_image_frame;
	      // Runs when the image button is clicked.
	      $('.image-upload').click(function (e) {
	        // Get preview pane
	        var meta_image_preview = $(this).parent().parent().children('.image-preview');
	        // Prevents the default action from occuring.
	        e.preventDefault();
	        var meta_image = $(this).parent().children('.meta-image');
	        // If the frame already exists, re-open it.
	        if (meta_image_frame) {
	          meta_image_frame.open();
	          return;
	        }
	        // Sets up the media library frame
	        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
	          title: meta_image.title,
	          button: {
	            text: meta_image.button
	          }
	        });
	        // Runs when an image is selected.
	        meta_image_frame.on('select', function () {
	          // Grabs the attachment selection and creates a JSON representation of the model.
	          var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	          // Sends the attachment URL to our custom image input field.
	          meta_image.val(media_attachment.url);
	          meta_image_preview.children('img').attr('src', media_attachment.url);
	        });
	        // Opens the media library frame.
	        meta_image_frame.open();
	      });
	    });
	  </script>


<?php

}
//01
add_action( 'add_meta_boxes', 'add_your_fields_metabox' );

function save_your_fields_meta($post_id){
	//verify nonce
	if( !wp_verify_nonce( @$_POST['your_meta_box_nonce'], basename( __FILE__ ) ) ){
		return $post_id;
	}
	// check autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return $post_id;
	}
	//check permission
	if( 'page' === $_POST['page_type'] ) {
		if( !current_user_can( 'edit_page', $post_id ) ){
			return $post_id;
		}
		elseif( !current_user_can( 'edit_post', $post_id ) ){
			return $post_id;
		}
	}

	$old	=	get_post_meta( $post_id, 'your_fields', true);
	$new	=	$_POST['your_fields'];

	if( $new && ( $new !== $old ) ) {
		update_post_meta( $post_id, 'your_fields', $new );
	}elseif( ( '' === $new ) && $old ){
		delete_post_meta( $post_id, 'your_fields', $old );
	}

}

//02 save all meta box fields...
add_action( 'save_post', 'save_your_fields_meta' );


//metabox code end..



//shortcodes

