<div class="col-sm-8">
<article <?php post_class(); ?>>
  <header>
    <h1 class="entry-title"><?php the_title(); ?></h1>

    <div class="category-list">
        <div class="category category-parent">
          <a href="/category/cfe-on-demand/">CFE On Demand</a>
        </div>

        <div class="category category-child">
          <a href="/cfe-tv/">CFE TV</a>
        </div>
    </div>

  </header>

  <?php

  if( have_rows('videos') ):

       // loop through the rows of data
      while ( have_rows('videos') ) : the_row();

          ?> 

          <div class="col-md-6 col-sm-6">

            <?php the_sub_field('video_link'); ?>

            <h2 class="entry-title">
              <?php the_sub_field('video_title'); ?>
            </h2>

            <div class="entry-content">

            <?php the_sub_field('video_description'); ?>

            </div>

          </div>

          <?php



      endwhile;

  else :

      // no layouts found

  endif;

  ?>

  <div class="entry-content">
    <?php the_content(); ?>
  </div>
</article>
</div>

<?php if (!get_field('hide_sidebar')) : ?>
  <?php get_template_part('templates/sidebar', 'default'); ?>
<?php endif; ?>