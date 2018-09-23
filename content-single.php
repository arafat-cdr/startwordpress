<div class="blog-post">
	<h2 class="blog-post-title"> <?php the_title(); ?> </h2>
	<p class="blog-post-meta"> <?php the_date(); ?> by <a href="#"> <?php the_author(); ?> </a></p>

	<p>
		<?php

		if( has_post_thumbnail() ):
			
			the_post_thumbnail();

		endif;

		?>
	</p>

	<p> <?php the_content(); ?> </p>
	<hr>

<!-- the rest of the content -->

</div><!-- /.blog-post -->