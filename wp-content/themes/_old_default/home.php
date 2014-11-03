<?php 

$paged = (get_query_var('paged')) ? get_query_var('paged'):1;

if($paged <= 1){

  get_header();

  include ( 'banners.php');

} else {

  get_header();

}

?>



<div id="content" class="widecolumn">

    <?php

      if($paged <= 1){

      	echo ('<div id="mostpopular"><h2>NEW ARRIVALS</h2>');  

		getSeed('http://affinitycycles.simple-helix.net/store/rss/catalog/new/store_id/1/');

        /* getMostViewed('http://affinitycycles.simple-helix.net/store/');*/

        echo('</div>');

      }

    ?>

 

    <?php 

    	if($paged > 1){

    

    		echo '<div id="blog-banner-top">';

    		if(function_exists("useful_banner_manager_banners")){ useful_banner_manager_banners("8",1); };

    		echo '</div>';

    	}

    ?>

       



    <?php

      if($paged <= 1){

        echo '<h2>Latest blog entries</h2>';

      }else{

        echo '<h2 style="width:380px; float:left">Latest blog entries</h2><div class="clearfloat"></div>';

      }

    ?>

    

    

    <div id="flow">

    <?php 

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

	if($paged <= 1){

		get_sidebar('Top-Sidebar-Banners');

		get_sidebar('Bottom-Sidebar-Banners'); 

	} else {

		get_sidebar('Top-Sidebar-Banners');

		get_sidebar('Archives-Sidebar');

	} 

?>

</div>



<?php get_footer(); ?>