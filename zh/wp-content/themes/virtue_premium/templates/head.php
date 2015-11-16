<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="utf-8">
   <?php if(kadence_seo_switch()) {
    ?> <title><?php global $virtue_premium; global $post; if ( get_post_meta( get_the_ID(), '_kad_seo_title', true )) { $title = get_post_meta( get_the_ID(), '_kad_seo_title', true ); }
    if(!empty($title)) { echo $title; } 
    else if(!empty($virtue_premium['seo_sitetitle'])) { echo $virtue_premium['seo_sitetitle'];} 
    else {wp_title('|', true, 'right'); }?>
  </title>
   <meta name="description" content="<?php global $virtue_premium; global $post; if ( get_post_meta( get_the_ID(), '_kad_seo_description', true )) { echo get_post_meta( get_the_ID(), '_kad_seo_description', true ); } 
  else if (!empty($virtue_premium['seo_sitedescription'])) echo $virtue_premium['seo_sitedescription']; 
  else bloginfo('description'); ?>" />
  <?php } else { ?>
   <title><?php wp_title( '|', true, 'right' ); ?></title>
  <?php }?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php global $virtue_premium; if(isset($virtue_premium['virtue_custom_favicon']['url'])) echo $virtue_premium['virtue_custom_favicon']['url']; ?>" />
  <?php wp_head(); ?>
  <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri() . '/assets/js/vendor/respond.min.js';?>"></script>
    <![endif]-->
</head>
