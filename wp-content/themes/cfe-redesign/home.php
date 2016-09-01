<?php


$post = prepare_featured_content();

if($post) {
	setup_postdata($post);

	$excerpt = get_the_excerpt( $post );

	if(!$post->post_excerpt) {
		$excerpt .= '...';
	}

	$image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'landing' );

	if($image[0]) {
		$image = $image[0];
	} else {
		$image = false;
	}

?>

<ul class="background">
  <li style="opacity: 1; background-image: url('<?php echo $image  ?>');"></li>
</ul>
<div class="landing">
	<h2><?php the_title(); ?></h2>
	<p><?php echo $excerpt; ?></p>
	<a class="read-more" href="<?php echo get_permalink( $post->ID ); ?>">
		Read more
		<div class="arrow">
			<div>
				<span class="right"></span>
			</div>
		</div>
	</a>
</div>

<?php } ?>