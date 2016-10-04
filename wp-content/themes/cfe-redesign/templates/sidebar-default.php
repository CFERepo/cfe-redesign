<?php

global $featured;

$category = $wp_query->get_queried_object();

$meta_query = array(
	array(
		'key' => 'event_date',
		'value' => date('Ymd'),
		'type' => 'DATE',
		'compare' => '>='
	)
);

$events = new WP_Query(array(
	'post_type'			=> 'post',
	'posts_per_page'	=> 5,
	'meta_key'			=> 'event_date',
	'orderby'			=> 'meta_value_num',
	'order'				=> 'ASC',
	'meta_query' => $meta_query
));

?>

<div class="col-sm-4">

	<div class="sidebar-wrapper">

		<?php

		if(!isset($featured)) {
			$post = prepare_featured_content();
		} else {
			$post = $featured;
		}
		
		if($post) {
			setup_postdata($post);

			$excerpt = get_the_excerpt( $post );

			$image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'featured-article-right' );

			if($image[0]) {
				$image = $image[0];
			} else {
				$image = false;
			}

		?>

		<div class="sidebar-block featured-article">
			<div class="image">
				<img src="<?php echo $image; ?>" />
			</div>

			<h2 class="entry-title">
				<a href="<?php echo get_permalink( $post->ID ); ?>"><?php the_title(); ?></a>
			</h2>

			<div class="excerpt entry-content">
				<p><?php echo $excerpt; ?></p>
			</div>
		</div>

		<?php } ?>


		<?php if( $events->have_posts() ): ?>
			<div class="sidebar-block events">

				<h2 class="entry-title">
					<a href="/category/whats-cfe/events/">Events</a>
				</h2>

				<ul>
				<?php while( $events->have_posts() ) : $events->the_post(); 
					
					?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<time class="event""><?= get_field('event_date'); ?></time>
					</li>
				<?php endwhile; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>

		<?php if($category->term_id == 105) { ?>
			<?php dynamic_sidebar('sidebar-tom'); ?>
		<?php } ?>

	</div>


</div>