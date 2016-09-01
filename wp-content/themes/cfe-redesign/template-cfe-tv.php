<?php
/**
 * Template Name: CFE TV
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'cfe-tv'); ?>
<?php endwhile; ?>
