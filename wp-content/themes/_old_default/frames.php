<?php

/*

Template Name: Frames

*/

?>



<?php get_header(); ?>

<?php get_sidebar('framessidebar'); ?>

	<div id="content_frames" class="column_frames">



		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

		

			<div class="entry">

				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>



				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>



			</div>

		</div>

		<?php endwhile; endif; ?>

	

	</div>





<?php include ( 'bottom.php'); ?>

<?php get_footer(); ?>

