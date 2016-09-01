<?php 

  // Get categories
  $category = $wp_query->get_queried_object();
  $category_parent = get_category($category->category_parent);

  $categories = array('parent' => $category_parent, 'child' => $category);

?>

<header>
  <h1 class="entry-title"><?php echo $category->name; ?></h1>

  <?php if($categories) { ?>

    <div class="category-list">

      <?php foreach($categories as $category) { ?>

        <div class="category category-<?php echo ($category->category_parent ? 'child' : 'parent') ?>">
          <a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name ?></a>
        </div>

      <?php } ?>

    </div>

  <?php } ?>
</header>