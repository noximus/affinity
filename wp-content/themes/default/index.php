<?php get_header(); ?>



<div id="content" class="widecolumn">

				

    <?php include ( 'popular.php'); ?>

    

    <div id="flow">

    <h2>Latest blog entries</h2>

     

    <?php 

    $paged = (get_query_var('paged')) ? get_query_var('paged'):1;

    query_posts('paged=' . $paged . '&posts_per_page=6');

    if (have_posts()) : ?><?php while (have_posts()) : the_post(); ?>

    

    

    <div class="post">

    



       	<?php images('1', '', '150', 'alignleft', true); ?>



        	<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title();?> </a></h3>

            <div class="date">

            	Posted by <span class="blue"><?php the_author_posts_link(); ?></span>

						<?php /* This is commented, because it requires a little adjusting sometimes.

							You'll need to download this plugin, and follow the instructions:

							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */

							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>

						on <?php the_time('F jS, Y') ?>

            </div>

        	<?php $excerpt = get_the_excerpt(); echo string_limit_words($excerpt,20); ?>...

            <br/><span class="readmore"><a href="<?php the_permalink(); ?>">Read More</a></span>

			<div class="clearfloat"></div>

    </div>

    

    <?php endwhile; ?><?php endif; ?>

    

    </div>

	<div id="wp-pagenavi"><?php posts_nav_link('&nbsp;','Next','Previous'); ?></div>

</div>



<?php get_sidebar(); ?>

<?php get_footer(); ?>

