  <div id="pageheader" class="titleclass">
    <div class="container">
      <?php get_template_part('templates/page', 'header'); ?>
    </div><!--container-->
  </div><!--titleclass-->
 <?php if(kadence_display_sidebar()) {$display_sidebar = true; $fullclass = '';} else {$display_sidebar = false; $fullclass = 'fullwidth';}
   global $virtue_premium; 
   if(isset($virtue_premium['category_post_summary']) && $virtue_premium['category_post_summary'] == 'full') {
    $summary = 'full'; $postclass = "single-article fullpost";
  } else if (isset($virtue_premium['category_post_summary']) && $virtue_premium['category_post_summary'] == 'grid'){
      $summary = 'grid'; $postclass = "grid-postlist";
  }else {$summary = 'normal'; $postclass = 'postlist';} 
  if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;} 
  if(isset($virtue_premium['category_post_grid_column'])) {$blog_grid_column = $virtue_premium['category_post_grid_column'];} else {$blog_grid_column = '3';} 
  if ($blog_grid_column == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 559; $slideheight = 559;} 
        else if ($blog_grid_column == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 366; $slideheight = 366;} 
        else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 269; $slideheight = 269;}?>
    <div id="content" class="container">
      <div class="row">
      <div class="main <?php echo kadence_main_class(); ?>  <?php echo $postclass .' '. $fullclass; ?>" role="main">

<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'virtue'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>
<?php if($summary == 'full'){
            if($display_sidebar){
               while (have_posts()) : the_post();
                get_template_part('templates/content', 'fullpost'); 
               endwhile;
             } else {
                while (have_posts()) : the_post(); 
                get_template_part('templates/content', 'fullpostfull');
                endwhile;
             }
      } else if($summary == 'grid') { ?>
      <div id="kad-blog-grid" class="rowtight" data-fade-in="<?php echo $animate;?>">
        <?php while (have_posts()) : the_post(); ?>
            <?php if($blog_grid_column == '2') { ?>
              <div class="<?php echo $itemsize;?> b_item kad_blog_item">
              <?php get_template_part('templates/content', 'twogrid'); ?>
              </div>
            <?php } else {?>
              <div class="<?php echo $itemsize;?> b_item kad_blog_item">
              <?php get_template_part('templates/content', 'fourgrid');?>
              </div>
            <?php } ?>
          <?php endwhile; ?>
      </div>
                <script type="text/javascript">jQuery(document).ready(function ($) {var $container = $('#kad-blog-grid');$container.imagesLoadedn( function(){$container.isotopeb({masonry: {columnWidth: ".b_item"}, transitionDuration: "0.8s"});if($('#kad-blog-grid').attr('data-fade-in') == 1) {$('#kad-blog-grid .kad_blog_fade_in').css('opacity',0); $('#kad-blog-grid .kad_blog_fade_in').each(function(i){$(this).delay(i*150).animate({'opacity':1},350);});}});});</script>
                <?php
      } else {
          if($display_sidebar){
               while (have_posts()) : the_post();
                    get_template_part('templates/content', get_post_format());
               endwhile;
             } else {
                while (have_posts()) : the_post(); 
                    get_template_part('templates/content', 'fullwidth');
                endwhile;
             }
      }

    if ($wp_query->max_num_pages > 1) : ?>
        <?php if(function_exists('kad_wp_pagenavi')) { ?>
              <?php kad_wp_pagenavi(); ?>   
            <?php } else { ?>      
              <nav class="post-nav">
                <ul class="pager">
                  <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'virtue')); ?></li>
                  <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'virtue')); ?></li>
                </ul>
              </nav>
            <?php } ?> 
        <?php endif; ?>

</div><!-- /.main -->
