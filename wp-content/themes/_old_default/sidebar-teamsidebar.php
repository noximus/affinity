	<div class="sidebar_team" id="sidebar_f">
<?php
if(!$post->post_parent){
	$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
}else{
	if($post->ancestors)
	{
		$ancestors = end($post->ancestors);
		$children = wp_list_pages("title_li=&child_of=".$ancestors."&echo=0");
	}
}
  if ($children) { ?>
  <h2><a class="black" href="/">HOME</a><span class="black">&nbsp;/&nbsp;</span> <a class="blu" href="/team">TEAM</a></h2>
  <ul class="first">
  <?php echo $children; ?>
  </ul>

<?php } ?>        
</div>

