 <div id="pageheader" class="titleclass">
    <div class="container">
      <?php get_template_part('templates/page', 'header');  ?>
    </div><!--container-->
  </div><!--titleclass-->
  
    <div id="content" class="container">
      <div class="row">
      <div class="main <?php echo kadence_main_class(); ?>  postlist" id="ktmain" role="main">

<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'virtue'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>
<div id="kad-blog-grid" class="clearfix">
  <?php $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $md = 3; $sm = 3; $xs = 2; $ss = 1; ?>
<?php while (have_posts()) : the_post(); ?>
  <div class="<?php echo $itemsize;?> search_item">
  <?php get_template_part('templates/content', 'searchresults'); ?>
  </div>
<?php endwhile; ?>
</div> <!-- Blog Grid -->
<?php if ($wp_query->max_num_pages > 1) : ?>
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
<script type="text/javascript">jQuery(document).ready(function ($) {var $container = $('#kad-blog-grid');$container.imagesLoadedn( function(){$container.isotopeb({masonry: {columnWidth: ".search_item"}, transitionDuration: "0.8s"});}); });
</script>
</div><!-- /.main -->
