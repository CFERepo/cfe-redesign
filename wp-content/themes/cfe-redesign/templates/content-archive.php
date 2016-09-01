<?php 
  global $featured;
  $featured = prepare_featured_content(); 

  $category = $wp_query->get_queried_object();
?>

<div class="col-sm-8">

  <?php get_template_part('templates/archive', 'header'); ?>

  <?php 

    while (have_posts()) : the_post(); 

    // Skip over item already in featured array
    if($featured && $featured->ID == $post->ID) {
      continue;
    }

    $type = $wp_query->current_post ? 'standard' : 'pinned'; 

  ?>

    <article <?php post_class($type); ?>>

      <?php 

        if( !is_paged() && $wp_query->current_post == 0 && $category->term_id != 3 && $category->term_id != 7 ) {

            $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'featured-article-top' );

            if($image[0]) {
              $image = $image[0];
            } else {
              $image = false;
            }

        } else {
          $image = false;
        }

      ?>

      <?php if($image) { ?>
        <div class="image">
          <img src="<?php echo $image; ?>" />
        </div>
      <?php } else { ?>
        <div class="image-preview">
            <?php the_post_thumbnail( 'article-preview-small' ); ?>
        </div>
      <?php } ?>

      <header>
        <h2 class="entry-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>
        <time class="updated" datetime="<?= get_post_time('c', true); ?>"><?= get_the_date(); ?></time>
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

  <div class="paginate">
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
          'mid_size' => 1,
          'end_size' => 2,
          'before_page_number' => '',
          'after_page_number'  => '',
          'show_all'           => false,
          'add_fragment'       => ''
          ));
    ?>
  </div>
</div>

<?php get_template_part('templates/sidebar', 'default'); ?>
