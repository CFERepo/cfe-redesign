<?php 

  $current_header = false;

  $type = validate_event_type();

?>

<div class="col-sm-8">

  <div class="events-filter">

    <span>Filter by Event Type:</span>
    <div class="checkbox-custom">
      <input id="campus" name="campus" data-group="campus" data-term-name="campus" class="checkbox-input campus" type="checkbox" value="" <?php echo ($type == 'campus' ? 'checked="checked"' : '') ?>>
      <label for="campus" class="checkbox-custom-label"><span>Campus</span></label>
    </div>

    <div class="checkbox-custom">
      <input id="alumni" name="alumni" data-group="alumni" data-term-name="alumni" class="checkbox-input alumni" type="checkbox" value="" <?php echo ($type == 'alumni' ? 'checked="checked"' : '') ?>>
      <label for="alumni" class="checkbox-custom-label"><span>Alumni</span></label>
    </div>
  </div>

  <?php get_template_part('templates/archive', 'header'); ?>

  <?php while (have_posts()) : the_post(); ?>

  	<?php 

  		$date = get_field('event_date');
  		$location = get_field('event_location');
  		$time = get_field('event_time');

  		// Skip over elements with no date set
  		if(!$date) {
  			continue;
  		}

  		$temp_header = date_i18n('F Y', strtotime($date)); 

		if ( $temp_header != $current_header ) {
			$current_header = $temp_header;
			echo '<h2 class="date-header">' . $current_header . '</h2>';
		}

  	?>

    <article <?php post_class(); ?>>

      <div class="date-day">
      	<span><?php echo date('d', strtotime($date)); ?></span>
      </div>

      <header class="<?php echo ($wp_query->current_post ? 'standard' : 'pinned') ?>">
        <h2 class="entry-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>

        <div class="meta">
        	<?php echo $date; ?>
	        <?php if($time) { ?><? echo ' | ' . $time; ?><?php } ?>
	        <?php if($location) { ?><? echo ' | ' . $location; ?><?php } ?>
        </div>
      </header>

      <div class="entry-content">
        <?php the_excerpt(); ?>
      </div>


      <footer>
        <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
      </footer>

      <?php //comments_template('/templates/comments.php'); ?>

      <div style="clear: both;"></div>
    </article>
  <?php endwhile; ?>

  <?php

  $big = 999999999; // need an unlikely integer

  echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big )) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $wp_query->max_num_pages,
        'prev_next' => false,
        'prev_next'   => TRUE,
        'prev_text'    => '«',
        'next_text'    => '»',
        'mid_size' => 3,
        'end_size' => 5,
        'before_page_number' => '',
        'after_page_number'  => '',
        'show_all'           => false,
        'add_fragment'       => ''
        ));
  ?>
</div>

<?php get_template_part('templates/sidebar', 'default'); ?>
