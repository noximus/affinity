<?php /*Template Name: Team*/?>
<?php get_header(); ?>
<?php get_sidebar('teamsidebar'); ?>

<div id="content_frames" class="column_team">
    <div id="flow2">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
            <div class="entry">
                <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
                <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
            </div>
        </div>
        <?php endwhile; endif; ?>
    </div>
    <div id="flow">
        <h2> Latest Team blog entries </h2>
        <?php query_posts('author=4,23,24&posts_per_page=4');

    	if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        <div class="post">
        	<?php images('1', '', '150', 'alignleft', true); ?>
            <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
                <?php the_title();?>
                </a></h3>
            <div class="date"> Posted by <span class="blue">
                <?php the_author_posts_link(); ?>
                </span>
                <?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
                on
                <?php the_time('F jS, Y') ?>
            </div>
            <?php $excerpt = get_the_excerpt(); echo string_limit_words($excerpt,40); ?>... <br/>
            <span class="readmore"><a href="<?php the_permalink(); ?>">Read More</a></span>
            </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>
    <div class="blog-button">
        <div><a href="http://affinitycycles.com/author/racing-team/" class="next">View Racing Team Blog</a></div>
    </div>
</div>
<?php include ( 'bottom.php'); ?>
<?php get_footer(); ?>
