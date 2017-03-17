<div class="col-sm-8 staff-listing">

  <?php

  $current_category = get_category( get_query_var( 'cat' ) );

  $terms = array();

  if($current_category->slug == 'faculty') {
    $terms[] = get_term_by('id', 104, 'role');
  } else {
    $terms = get_terms( 'role', array(
        'hide_empty' => true,
        'orderby' => 'menu_order',
        'exclude' => array(104)
    ));
  }


  foreach($terms as $term) {

    $priority = array();
    $other = array();

    $query = array(
      'post_type'     => 'staff',
      'posts_per_page'  => -1,
      'meta_query' => array(
          'relation' => 'OR',
          array(
              'key' => 'staff_priority',
              'compare' => 'NOT EXISTS',
          ),
          array(
              'key' => 'staff_priority',
              'value' => 0,
              'compare' => '>',
          ),
      ),
      'orderby' => array(
          'date' => 'ASC',
          'meta_value_num' => 'ASC',
      ),
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'role',
          'field'    => 'term_id',
          'terms'    => array( $term->term_id ),
          'operator' => 'IN',
        )
      )
    );

    // Faculty alpha sort
    if($term->term_id == 104) {
      $query['orderby'] = array(
          'title' => 'ASC'
      );
    }

    $staff = new WP_Query($query);

    if($staff->have_posts()) {
        while($staff->have_posts()) {
           $staff->the_post();

           if( $post->ID == 27 && $current_category->slug != 'faculty') {
              $priority[] = $staff->post;
           } else {
              $other[] = $staff->post;
           }
         }
    }

    wp_reset_postdata();

    $staff = array_merge($priority, $other);

  ?>

    <?php foreach ($staff as $key => $post) { ?>

      <?php global $post; setup_postdata($post); ?>

      <?php if( $key == 0 ) { ?>

          <div class="col-sm-12">
            <h2 class="entry-title role-title"><?php echo $term->name ?></h2>
          </div>

          <div class="clearfix"></div>
      <?php } ?>

      <div class="col-sm-6">
        <article <?php post_class(); ?>>

          <div class="image-preview">
              <?php the_post_thumbnail( 'article-preview-medium' ); ?>
          </div>

          <header class="<?php echo ($key ? 'standard' : 'pinned') ?>">
            <h2 class="entry-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>
            <div class="meta">
              <?php echo get_field('job_title'); ?>
            </div>
          </header>

          <footer>
            <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
          </footer>

          <?php //comments_template('/templates/comments.php'); ?>
        </article>
      </div>

      <?php if( $key % 2 === 1 ) { ?>
        <div class="clearfix"></div>
      <?php } ?>
    <?php } ?>

  <?php } ?>

  <?php wp_reset_query();  // Restore global post data stomped by the_post(). ?>

  <div class="clear: both"></div>
</div>

<?php get_template_part('templates/sidebar', 'default'); ?>
