<?php // Do not delete these lines

	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))

		die ('Please do not load this page directly. Thanks!');



	if (!empty($post->post_password)) { // if there's a password

		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie

			?>



			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>



			<?php

			return;

		}

	}



	/* This variable is for alternating comment background */

	$oddcomment = 'class="alt" ';

?>



<!-- You can start editing here. -->



<?php if ($comments) : ?>

<div class="comment">

	<h3><?php comments_number('No Responses', 'One Response', '% Responses' );?> </h3>

	<ol class="commentlist">

    <?php $cni = 0; ?>

	<?php foreach ($comments as $comment) : ?>

    	<?php $cni++; ?>

           <?php if(sizeof($comments) == $cni) : ?>

                 <?php $oddcomment = 'class="alt_last" '; ?>

           <?php endif; ?>

		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">

			<div class="date">

			<span class="blue"><?php comment_author_link() ?></span> Says at

			<?php if ($comment->comment_approved == '0') : ?>

			<em>Your comment is awaiting moderation.</em>

			<?php endif; ?>

			<?php comment_date('F jS, Y') ?> at <?php comment_time() ?> <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?>:</div>

			<?php comment_text() ?>



		</li>



	<?php

		/* Changes every other comment to a different class */

		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';

	?>



	<?php endforeach; /* end for each comment */ ?>



	</ol>

</div>

 <?php else : // this is displayed if there are no comments so far ?>



	<?php if ('open' == $post->comment_status) : ?>

		<!-- If comments are open, but there are no comments. -->

		

	 <?php else : // comments are closed ?>

		<!-- If comments are closed. -->

		<p class="nocomments">Comments are closed.</p>



	<?php endif; ?>

<?php endif; ?>



<div id="commentforms">

<h3>Leave a Reply</h3>



<?php if ('open' == $post->comment_status) : ?>







    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>

    <p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

</div>

<?php else : ?>



    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

    <div id="forma">

        <?php if ( $user_ID ) : ?>



        <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>



<?php else : ?>

<div id="left">

	<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label>

	<input type="text" name="author" class="text1" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />



	<label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label>

	<input type="text" name="email" class="text1" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />



<label for="url"><small>Website</small></label>

<input type="text" name="url" class="text1" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />



</div>

<?php endif; ?>

<div id="right">

	<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

	<label for="comment"><small>Your Comment</small></label>

	<textarea name="comment" class="textarea" id="comment" cols="100%" rows="10" tabindex="4" ></textarea>

</div>

<div class="clearfloat"></div>

<div id="bottom">

	<input name="submit" class="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />

	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

</div>

<?php do_action('comment_form', $post->ID); ?>

</div>

</form>

<div class="clearfloat"></div>

</div>

<?php endif; // If registration required and not logged in ?>



<?php endif; // if you delete this the sky will fall on your head ?>

<div class="clearfloat"></div>