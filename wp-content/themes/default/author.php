<?php 

if(isset($_GET['author_name'])) :

    $curauth = get_userdatabylogin($author_name);

else :

    $curauth = get_userdata(intval($author));

endif;

$paged = (get_query_var('paged')) ? get_query_var('paged'):1;

get_header();

?>



<div id="content" class="widecolumn">

    <h2 style="width:380px; float:left"><?php echo $curauth->first_name; echo ' '; echo $curauth->last_name;?> blog</h2><h6 id="all_blog"><a>View all blogs</a></h6><div class="clearfloat"></div>

    <div id="bloggers" style="display: none;">

       <ul>

          <?php fb_list_authors(); ?>

       </ul>

    </div>

    <div id="flow">

    <?php 

     if($curauth->ID == 24){

        query_posts('paged=' . $paged . '&posts_per_page=6' . '&author=24,23,4');

     } else {

        query_posts('paged=' . $paged . '&posts_per_page=6' . '&author=' . $curauth->ID);

     }

     if (have_posts()) : ?><?php while (have_posts()) : the_post(); ?>

    

    <div class="post">

       	<?php images('1', '', '150', 'alignleft', true); ?>

			

        	<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title();?> </a></h3>

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

</div>



<div id="sidebar">

    <?php       

       if(isset($_GET['author_name'])) :

         $curauth = get_userdatabylogin($author_name);

       else :

          $curauth = get_userdata(intval($author));

       endif;

    ?>

    <h2><?php echo $curauth->first_name; echo ' '; echo $curauth->last_name;?></h2>

    <div class="profile">

    	<img src="/avatars/<?php echo $curauth->nickname; ?>.jpg" width=90 height=80 alt="Avatar"/>

     

    	<?php echo $curauth->description; ?>

    </div>

    

    <h2>Recent entries</h2>

    <ul><?php wp_get_archives('title_li=&type=postbypost&limit=10'); ?></ul>



	<?php get_sidebar('Top-Sidebar-Banners'); ?>



    <h2>Archives</h2>

    <ul><?php wp_get_archives('type=yearly'); ?></ul>

</div>

<?php get_footer(); ?>

