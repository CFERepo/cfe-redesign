<?php

if(in_category('events')) {
  $date = get_field('event_date');
  $location = get_field('event_location');
  $time = get_field('event_time');
}

?>

<div class="col-sm-8">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
      <header>
        <h1 class="entry-title"><?php the_title(); ?></h1>

        <?php if($post->meta->categories) { ?>

          <div class="category-list">

            <?php foreach($post->meta->categories as $category) { ?>

              <div class="category category-<?php echo ($category->category_parent ? 'child' : 'parent') ?> <?php echo $category->slug; ?>">
                <a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name ?></a>
              </div>

            <?php } ?>

          </div>

        <?php } ?>

      <?php 

          $post_type = get_post_type( get_the_ID() );

          if($post_type == 'staff') {
            $size = 'square-large';
          } else {
            $size = 'featured-article-top';
          }

          $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $size );

          if($image[0]) {

            if($post_type == 'staff') {
              $style = 'inset';
            } else {
              $style = 'featured-article-top';

              $ratio = 1;

              if($image[2] > $image[1]) {
                $ratio = ($image[2] / $image[1]);
              } else {
                $ratio = ($image[1] / $image[2]);
              }

              $threshold = 0.1; // 10% out.

              if($image[2] > $image[1] || $image[2] == $image[1] || $post->ID == 1675 || ($ratio-1) < $threshold) {
                $style = 'inset-left';
              }
            }

            $image = $image[0];
          } else {
            $image = false;
          }

      ?>

      <?php if($image) { ?>
        <div class="image <?php echo $style; ?>">
          <img src="<?php echo $image; ?>" />
        </div>
      <?php } ?>

        <?php if($date) { ?>
        <div class="meta">
          <?php echo $date; ?>
          <?php if($time) { ?><? echo ' | ' . $time; ?><?php } ?>
          <?php if($location) { ?><? echo ' | ' . $location; ?><?php } ?>
        </div>
        <?php } ?>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
        <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
      </footer>
      <?php comments_template('/templates/comments.php'); ?>
    </article>
  <?php endwhile; ?>
</div>


<?php if (!get_field('hide_sidebar')) : ?>
  <?php get_template_part('templates/sidebar', 'default'); ?>
<?php endif; ?>