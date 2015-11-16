<?php 
/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce */
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
  add_theme_support( 'woocommerce' );
}
/*-----------------------------------------------------------------------------------*/
/* WooCommerce Functions */
/*-----------------------------------------------------------------------------------*/

if (class_exists('woocommerce')) {
  add_filter( 'woocommerce_enqueue_styles', '__return_false' );
  // Disable WooCommerce Lightbox
  update_option( 'woocommerce_enable_lightbox', false );

  // Makes the product finder plugin work.
    remove_action( 'template_redirect' , array( 'WooCommerce_Product_finder' , 'load_template' ) );

}
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
 
// Set the number of columns to 3
function kad_woocommerce_cross_sells_columns( $columns ) {
  return 3;
}
add_filter( 'woocommerce_cross_sells_columns', 'kad_woocommerce_cross_sells_columns', 10, 1 );

// Limit the number of cross sells displayed to a maximum of 3
function kad_woocommerce_cross_sells_total( $limit ) {
  return 3;
}
add_filter( 'woocommerce_cross_sells_total', 'kad_woocommerce_cross_sells_total', 10, 1 );
// Redefine woocommerce_output_related_products()

function kad_woo_related_products_limit() {
  global $product, $woocommerce;
  $related = $product->get_related();
  $args = array(
    'post_type'           => 'product',
    'no_found_rows'       => 1,
    'posts_per_page'      => 8,
    'ignore_sticky_posts'   => 1,
    //'orderby'               => $orderby,
    'post__in'              => $related,
    'post__not_in'          => array($product->id)
  );
  return $args;
}
add_filter( 'woocommerce_related_products_args', 'kad_woo_related_products_limit' );
add_filter( 'woocommerce_product_tabs', 'kad_product_video_tab');
function kad_product_video_tab_content() {
  global $post,$virtue_premium; if($videocode = get_post_meta( $post->ID, '_kad_product_video', true )) {
  if(!empty($virtue_premium['video_title_text'])) {$product_video_title = $virtue_premium['video_title_text'];} else {$product_video_title = __('Product Video', 'virtue');}
 echo '<h3>'.$product_video_title.'</h3>';
 echo '<div class="videofit product_video_case">'.$videocode.'</div>';
}
}
function kad_product_video_tab($tabs) {
  global $post, $virtue_premium; if($videocode = get_post_meta( $post->ID, '_kad_product_video', true )) {
    if(!empty($virtue_premium['video_tab_text'])) {$product_video_title = $virtue_premium['video_tab_text'];} else {$product_video_title = __('Product Video', 'virtue');}
 $tabs['video_tab'] = array(
 'title' => $product_video_title,
 'priority' => 50,
 'callback' => 'kad_product_video_tab_content'
 );
}

 return $tabs;
}
// Number of products per page
add_filter('loop_shop_per_page', 'wooframework_products_per_page');
if (!function_exists('wooframework_products_per_page')) {
  function wooframework_products_per_page() {
    global $virtue_premium;
    if ( isset( $virtue_premium['products_per_page'] ) ) {
      return $virtue_premium['products_per_page'];
    }
  }
}

// Display product tabs?
add_action('wp_head','wooframework_tab_check');
if ( ! function_exists( 'wooframework_tab_check' ) ) {
  function wooframework_tab_check() {
    global $virtue_premium;
    if ( isset( $virtue_premium[ 'product_tabs' ] ) && $virtue_premium[ 'product_tabs' ] == "0" ) {
      remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    }
  }
}

// Display related products?
add_action('wp_head','wooframework_related_products');
if ( ! function_exists( 'wooframework_related_products' ) ) {
  function wooframework_related_products() {
    global $virtue_premium;
    if ( isset( $virtue_premium[ 'related_products' ] ) && $virtue_premium[ 'related_products' ] == "0" ) {
      remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    }
  }
}

// Change the tab title
add_filter( 'woocommerce_product_tabs', 'kad_woo_rename_tabs', 98 );
function kad_woo_rename_tabs( $tabs ) {
 global $virtue_premium; 
  if(!empty($virtue_premium['description_tab_text']) && !empty($tabs['description']['title'])) {$tabs['description']['title'] = $virtue_premium['description_tab_text'];}
  if(!empty($virtue_premium['additional_information_tab_text']) && !empty($tabs['additional_information']['title'])) {$tabs['additional_information']['title'] = $virtue_premium['additional_information_tab_text'];}
  if(!empty($virtue_premium['reviews_tab_text']) && !empty($tabs['reviews']['title'])) {$tabs['reviews']['title'] = $virtue_premium['reviews_tab_text'];}
 
  return $tabs;
}
// Change the tab description heading
add_filter( 'woocommerce_product_description_heading', 'kad_description_tab_heading', 10, 1 );
function kad_description_tab_heading( $title ) {
  global $virtue_premium; 
  if(!empty($virtue_premium['description_header_text'])) {$title = $virtue_premium['description_header_text'];}
  return $title;
}
// Change the tab aditional info heading
add_filter( 'woocommerce_product_additional_information_heading', 'kad_additional_information_tab_heading', 10, 1 );
function kad_additional_information_tab_heading( $title ) {
  global $virtue_premium; 
  if(!empty($virtue_premium['additional_information_header_text'])) {$title = $virtue_premium['additional_information_header_text'];}
  return $title;
}

add_filter( 'woocommerce_product_tabs', 'kad_woo_reorder_tabs', 98 );
function kad_woo_reorder_tabs( $tabs ) {
  global $virtue_premium; 
  if(isset($virtue_premium['ptab_description'])) {$dpriority = $virtue_premium['ptab_description'];} else {$dpriority = 10;}
  if(isset($virtue_premium['ptab_additional'])) {$apriority = $virtue_premium['ptab_additional'];} else {$apriority = 20;}
  if(isset($virtue_premium['ptab_reviews'])) {$rpriority = $virtue_premium['ptab_reviews'];} else {$rpriority = 30;}
  if(isset($virtue_premium['ptab_video'])) {$vpriority = $virtue_premium['ptab_video'];} else {$vpriority = 40;}
 
  if(!empty($tabs['description'])) $tabs['description']['priority'] = $dpriority;      // Description
  if(!empty($tabs['additional_information'])) $tabs['additional_information']['priority'] = $apriority; // Additional information 
  if(!empty($tabs['reviews'])) $tabs['reviews']['priority'] = $rpriority;     // Reviews 
  if(!empty($tabs['video_tab'])) $tabs['video_tab']['priority'] = $vpriority;      // Video second
 
  return $tabs;
}

add_filter('loop_shop_columns', 'kad_loop_columns');
  function kad_loop_columns() {
    global $virtue_premium;
    if(isset($virtue_premium['product_shop_layout'])) {
      return $virtue_premium['product_shop_layout'];
    } else {
      return 4;}
}
// Turning off for the time being, causing issues with cart widget
add_filter('add_to_cart_fragments', 'kad_woocommerce_header_add_to_cart_fragment');
function kad_woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce, $virtue_premium;
    ob_start(); ?>
    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'virtue'); ?>">
        <i class="icon-basket" style="padding-right:5px;"></i> <?php if(!empty($virtue_premium['cart_placeholder_text'])) {echo $virtue_premium['cart_placeholder_text'];} else {echo __('Your Cart', 'virtue');}  ?> <span class="kad-cart-dash">-</span> <?php echo $woocommerce->cart->get_cart_total(); ?>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}


// Add the custom tabs

function kad_custom_tab_01($tabs) {
  global $post; if($tab_content = get_post_meta( $post->ID, '_kad_tab_content_01', true )) {
    $tab_title = get_post_meta( $post->ID, '_kad_tab_title_01', true );
    $tab_priority = get_post_meta( $post->ID, '_kad_tab_priority_01', true ); 
    if(!empty($tab_title)) {$product_tab_title = $tab_title;} else {$product_tab_title = __('Custom Tab', 'virtue');}
    if(!empty($tab_priority)) {$product_tab_priority = esc_attr($tab_priority);} else {$product_tab_priority = 45;}
   $tabs['kad_custom_tab_01'] = array(
   'title' => $product_tab_title,
   'priority' => $product_tab_priority,
   'callback' => 'kad_product_custom_tab_content_01'
   );
  }

 return $tabs;
}
function kad_product_custom_tab_content_01() {
   global $post; $tab_content_01 = get_post_meta( $post->ID, '_kad_tab_content_01', true );
   echo do_shortcode('<div class="product_custom_content_case">'.__($tab_content_01).'</div>');
}
function kad_custom_tab_02($tabs) {
  global $post; if($tab_content = get_post_meta( $post->ID, '_kad_tab_content_02', true )) {
    $tab_title = get_post_meta( $post->ID, '_kad_tab_title_02', true );
    $tab_priority = get_post_meta( $post->ID, '_kad_tab_priority_02', true ); 
    if(!empty($tab_title)) {$product_tab_title = $tab_title;} else {$product_tab_title = __('Custom Tab', 'virtue');}
    if(!empty($tab_priority)) {$product_tab_priority = esc_attr($tab_priority);} else {$product_tab_priority = 50;}
   $tabs['kad_custom_tab_02'] = array(
   'title' => $product_tab_title,
   'priority' => $product_tab_priority,
   'callback' => 'kad_product_custom_tab_content_02'
   );
  }

 return $tabs;
}
function kad_product_custom_tab_content_02() {
   global $post; $tab_content_02 = wpautop(get_post_meta( $post->ID, '_kad_tab_content_02', true ));
   echo do_shortcode('<div class="product_custom_content_case">'.__($tab_content_02).'</div>');

}
function kad_custom_tab_03($tabs) {
  global $post; if($tab_content = get_post_meta( $post->ID, '_kad_tab_content_03', true )) {
    $tab_title = get_post_meta( $post->ID, '_kad_tab_title_03', true );
    $tab_priority = get_post_meta( $post->ID, '_kad_tab_priority_03', true ); 
    if(!empty($tab_title)) {$product_tab_title = $tab_title;} else {$product_tab_title = __('Custom Tab', 'virtue');}
    if(!empty($tab_priority)) {$product_tab_priority = esc_attr($tab_priority);} else {$product_tab_priority = 55;}
   $tabs['kad_custom_tab_03'] = array(
   'title' => $product_tab_title,
   'priority' => $product_tab_priority,
   'callback' => 'kad_product_custom_tab_content_03'
   );
  }

 return $tabs;
}
function kad_product_custom_tab_content_03() {
   global $post; $tab_content_03 = wpautop(get_post_meta( $post->ID, '_kad_tab_content_03', true ));
   echo do_shortcode('<div class="product_custom_content_case">'.__($tab_content_03).'</div>');
}
global $virtue_premium;
 if ( isset( $virtue_premium['custom_tab_01'] ) && $virtue_premium['custom_tab_01'] == 1 ) {
add_filter( 'woocommerce_product_tabs', 'kad_custom_tab_01');
}
if ( isset( $virtue_premium['custom_tab_02'] ) && $virtue_premium['custom_tab_02'] == 1 ) {
add_filter( 'woocommerce_product_tabs', 'kad_custom_tab_02');
}
if ( isset( $virtue_premium['custom_tab_03'] ) && $virtue_premium['custom_tab_03'] == 1 ) {
add_filter( 'woocommerce_product_tabs', 'kad_custom_tab_03');
}


// Shop Pages

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );


