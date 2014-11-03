	<div id="sidebar">

		

        <?php include ( 'right-banners.php'); ?>

        <h2>Recent entries</h2>

        <ul>

        <?php wp_get_archives('title_li=&type=postbypost&limit=10'); ?>

        </ul>

        <h2>Archives</h2>

        <ul>

        <?php wp_get_archives('type=yearly'); ?>

        </ul>

        

        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("pagesidebar") ) : ?>

		<?php endif; ?>

	</div>



