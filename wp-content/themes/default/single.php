<?php get_header(); ?>

	<div id="content" class="widecolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <?php if(is_author()): ?>
          <?php $isautor = true; ?>
        <?php endif; ?>
		<div class="single" id="post-<?php the_ID(); ?>">
			<h2 class="title"><?php the_title(); ?></h2>
            <div class="date">
            	Posted by <span class="blue"><?php the_author_posts_link(); ?></span>
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
						on <?php the_time('F jS, Y') ?>
            </div>

			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

	</div>
	
	<div id="sidebar">

	<?php
		if($isautor == true) {
			get_sidebar('authorsidebar');
		}
 
		else {
   			get_sidebar('Top-Sidebar-Banners');
			get_sidebar('Archives-Sidebar');
		}
	?>
	</div>
	
	<?php get_footer(); ?>
