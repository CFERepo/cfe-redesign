<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

function prepare_featured_content() {

  global $wp_query;
  global $post;

  if(is_home()) {
    $posts = get_field('featured_articles', 2204);

    $random = array_rand($posts);

    $post = $posts[$random];

    return $post;

  } else if(is_page() && is_page_template('template-cfe-tv.php') ) {
    // For CFE TV content pages

    $posts = get_field('featured_articles', $post->ID);

    if($posts) {

      $random = array_rand($posts);
      $post = $posts[$random];

      return $post;
    } else {
      return false;
    }

  } else if(is_single()) {

    if($post->meta->categories && $post->meta->categories->root) {

      $features = search_content_bucket($post->meta->categories->root->term_id);

      return $features;

      //$posts = get_field('featured_articles', $post->ID);

  } else {
      return false;
  }

    

    return $posts;
  } else if(is_archive()) {

    // Get categories
    $category = $wp_query->get_queried_object();

    if($category->category_parent) {
      $category_parent = get_category($category->category_parent);
    } else {
      // Category is already parent
      $category_parent = $category;
    }

    if($category_parent) {

      $features = search_content_bucket($category_parent->term_id);

      return $features;

    } else {
      return false;
    }


    //$posts = get_field('featured_articles');
  }

  return $posts;
}


// Search for content bucket with given root category ID
function search_content_bucket($term_id = false) {

  if(!$term_id) return false;

  $args = array(
    'numberposts' => 1,
    'post_type'   => 'page',
    'meta_query'  => array(
      'relation'    => 'AND',
      array(
        'key'   => 'linked_category',
        'value'   => $term_id,
        'compare' => '='
      )
    )
  );

  $q = new WP_Query( $args );
  $posts = $q->get_posts();

  if($posts) {
    $post = $posts[0];

    $features = get_field('featured_articles', $post->ID);

    if($features) {

      $random = array_rand($features);
      
      $selected = $features[$random];

      return $selected;
    } else {
      return false;
    }

  } else {
    return false;
  }

}

function cfe_redirect_special_pages() {


  if($_SERVER['REQUEST_URI'] == '/techlab-at-mcity-independent-study/') {
      wp_redirect( 'http://cfe.umich.edu/techlab/', 301);
      exit();
  }

  if($_SERVER['REQUEST_URI'] == '/coulter-translational-research-program/') {
      wp_redirect( 'http://bme.umich.edu/research/coulter/', 301);
      exit();
  }

}

add_action('init','cfe_redirect_special_pages', 1);

function modify_uploaded_file_title( $attachment_ID ) 
{

    $post = get_post($attachment_ID);

    $array = array();
    $array['ID'] = $attachment_ID;
    $array['post_title'] = $post->post_title . '-attachment';

    wp_update_post( $array );
}

add_action( 'add_attachment', 'modify_uploaded_file_title' );

function custom_excerpt_more($more) {
   global $post;

   if(is_home()) {
    return false;
   } else {
    return '... <a class="read-more" href="'. get_permalink($post->ID) . '">Read More</a>';
   }

}
add_filter('excerpt_more', 'custom_excerpt_more');

function wpdocs_custom_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

// Parse categories by post ID
function cfe_category_formatted($id) {

  $categories = get_the_category( $id );

  $list = cfe_parse_categories($categories);

  return $list;

}

function cfe_parse_categories($categories) {

  $parent = array();
  $children = array();

  foreach($categories as $category) {


    // Get first match for category
    if(!$children && $category->category_parent ) {
      $children = $category;
      $parent = get_category($category->category_parent);
    }

    if(!$category->category_parent) {
      $parent = $category;
    }

  }

  if($parent) {

    $list = new Stdclass;
    $list->root = $parent;

    if($children) {
      $list->child = $children;
    }


    return $list;

  }

}

function cfe_setup_postdata($post) {

    $post->meta = new Stdclass;

    $categories = cfe_category_formatted($post->ID);

    if($categories) {
      $post->meta->categories = $categories;
    } else {
      $post->meta->categories = false;
    }

    return $post;
}

add_action('the_post','cfe_setup_postdata');

function cfe_pre_get_posts( $query ) {
  
  // do not modify queries in the admin
  if( is_admin() ) {
    return $query;
  }

  // only modify queries for 'event' post type
  if( isset($query->query_vars['category_name']) && $query->query_vars['category_name'] == 'events' ) {

    $type = validate_event_type();

    $meta_query = array(
      'relation' => 'AND',
      array(
        'key' => 'event_date',
        'value' => date('Ymd'),
        'type' => 'DATE',
        'compare' => '>='
      )
    );

    if($type) {

      $type_query =  array(
        'key' => 'event_type',
        'value' => $type,
        'compare' => '='
      );

      array_push($meta_query, $type_query);
    }
    
    $query->set('posts_per_page', -1); 
    $query->set('orderby', 'meta_value_num'); 
    $query->set('meta_key', 'event_date');   
    $query->set('order', 'ASC'); 
    $query->set('meta_query', $meta_query);  
    
  }

  if( isset($query->query_vars['category_name']) && $query->query_vars['category_name'] == 'staff' ) {
    
    $query->set('post_type', array( 'staff', 'post' )); 
    $query->set('posts_per_page', -1); 
    
  }
  

  // return
  return $query;

}

add_action('pre_get_posts', 'cfe_pre_get_posts');

function validate_event_type() {
  $allowed = array('alumni', 'campus');

  if(isset($_GET['type']) && $_GET['type']) {
    $type = $_GET['type'];

    if(!in_array($type, $allowed)) {
      $type = false;
    }
  } else {
    $type = false;
  }

  return $type;
}