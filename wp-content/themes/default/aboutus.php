<?php

/*

Template Name: Aboutus

*/

?>



<?php get_header(); ?>



	<div id="content" class="narrowcolumn">



		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

		<h2><a class="black" href="/">HOME</a><span class="black">&nbsp;/&nbsp;</span><a class="black" href="/contact">CONTACT</a><span class="black">&nbsp;/&nbsp;</span> <a class="blu" href="/<?php the_title(); ?>"><?php the_title(); ?></a></h2>

			<div class="pages">

				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>



				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>



			</div>

		</div>

		<?php endwhile; endif; ?>

	

	</div>



<div id="sidebar">

	<?php get_sidebar('Top-Sidebar-Banners'); ?>

</div>

<?php include ( 'bottom.php'); ?>

<?php get_footer(); ?>

