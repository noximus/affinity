	<div class="sidebar_frames" id="sidebar_f">
<?php
  if($post->post_parent) {
  $children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
  $titlenamer = get_the_title($post->post_parent);
  }

  else {
  $children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
  $titlenamer = get_the_title($post->ID);
  }
  if ($children) { ?>

  <h2><a class="black" href="/">HOME</a><span class="black">&nbsp;/&nbsp;</span> <a class="blu" href="/<? echo ''.$titlenamer ?>"><? echo ''.$titlenamer ?></a></h2>
  <ul class="fra">
  <?php echo $children; ?>
  </ul>

<?php } ?>        
</div>

