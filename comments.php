<?php

if( post_password_required() ) {

	return false;
}

?>
<div id="comments" class="comments-area">
	
	<?php if( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
			printf( _nx( 'One comment on “%2$s”', '%1$s comments on “%2$s”', get_comments_number(), 'comments title'),number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h3>
		<ul class="comment-list">
			<?php
				wp_list_comments(array(
					'short_ping'	=>	true,
					'avater_size'	=>  50
				));
			?>
		</ul>
	<?php endif; ?>
	
	<?php

	$condition = ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' );
	if( $condition ) :

	?>

	<p class="no-comments">

		<?php _e( 'comments are closed' ); ?>
	</p>

	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- end comments-area -->