<?php get_header(); 
$paged = (get_query_var('paged')) ? get_query_var('paged'):1;
?>

	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>


    <div id="flow">
    <?php 
     query_posts('paged=' . $paged . '&posts_per_page=6&year='.get_the_time('Y'));
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
        	<?php $excerpt = get_the_excerpt(); echo string_limit_words($excerpt,50); ?>...
            <br/><span class="readmore"><a href="<?php the_permalink(); ?>">Read More</a></span>
			<div class="clearfloat"></div>
    </div>
    
    <?php endwhile; ?><?php endif; ?>

    </div>
	<div id="wp-pagenavi">
      <div class="previous"><?php next_posts_link('Previous'); ?></div>
      <div class="next"><?php previous_posts_link('Next'); ?></div>
    </div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>

<div id="sidebar">

<?php
		get_sidebar('Top-Sidebar-Banners');
		get_sidebar('Archives-Sidebar');
?>
</div>

<?php get_footer(); ?>
