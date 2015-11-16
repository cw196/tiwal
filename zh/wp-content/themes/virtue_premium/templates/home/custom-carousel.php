<div class="home-custom-carousel-wrap home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
    <?php global $virtue_premium; 
    $cc_items = $virtue_premium['home_custom_carousel_items'];
    if(!empty($virtue_premium['custom_carousel_title'])) {$cctitle = $virtue_premium['custom_carousel_title']; } else { $cctitle = __('Featured News', 'virtue'); }
    if(!empty($virtue_premium['home_custom_speed'])) {$hc_speed = $virtue_premium['home_custom_speed'].'000';} else {$hc_speed = '9000';}
    if(isset($virtue_premium['home_custom_carousel_scroll']) && $virtue_premium['home_custom_carousel_scroll'] == 'all' ) {$hc_scroll = '';} else {$hc_scroll = 'items:1,';}
    if(!empty($virtue_premium['home_custom_carousel_column'])) {$custom_column = $virtue_premium['home_custom_carousel_column'];} else {$custom_column = 4;} 
    if ($custom_column == '2') {$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 559; $slideheight = 559; $md = 2; $sm = 2; $xs = 1; $ss = 1;} 
                    else if ($custom_column == '3'){ $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 366; $slideheight = 366; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
                    else if ($custom_column == '6'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
                    else if ($custom_column == '5'){ $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
                    else {$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 267; $slideheight = 267; $md = 4; $sm = 3; $xs = 2; $ss = 1;} ?>

    <div class="clearfix"><h3 class="hometitle"><?php echo $cctitle; ?></h3></div>
  <div class=" fredcarousel">
    <div id="carouselcontainer" class="rowtight">
    <div id="home-custom-carousel" class="clearfix caroufedselclass">
      <?php foreach ($cc_items as $c_item) : 
              if(!empty($c_item['target']) && $c_item['target'] == 1) {$target = '_blank';} else {$target = '_self';} 
                    $image = aq_resize($c_item['url'], $slidewidth, $slideheight, true);
                    if(empty($image)) {$image = $c_item['url'];} ?>
                    <div class="<?php echo $itemsize; ?> kad_customcarousel_item">
                    <div class="grid_item product product_item custom_carousel_item all postclass">
                        <a href="<?php if($c_item['link']) echo $c_item['link']; ?>" class="custom_carousel_item_link" target="<?php echo $target; ?>">
                          <img src="<?php echo $image; ?>" alt="<?php if($c_item['title']) echo esc_attr($c_item['title']);?>" />
                        </a>
                          <div class="custom_carousel_details">
                            <a href="<?php if($c_item['link']) echo $c_item['link']; ?>">
                             <?php if ($c_item['title']) echo '<h5>'.$c_item['title'].'</h5>'; ?>
                            </a>
                          <div class="ccarousel_excerpt"><?php if($c_item['description']) echo $c_item['description'];?></div>
                          </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
      </div>
    <div class="clearfix"></div>
                <a id="prevport_custom_carousel" class="prev_carousel icon-arrow-left" href="#"></a>
          <a id="nextport_custom_carousel" class="next_carousel icon-arrow-right" href="#"></a>
    </div>
  </div>
  </div>
<script type="text/javascript">
   jQuery( window ).load(function () {
    var $wcontainer = jQuery('#carouselcontainer');
    var $container = jQuery('#home-custom-carousel');
        function initCarousel_custom() {
          $container.carouFredSel({
              scroll: {<?php echo $hc_scroll ?> easing: "swing", duration: 700, pauseOnHover : true},
              auto: {play: true, timeoutDuration: <?php echo $hc_speed; ?>},
              prev: '#prevport_custom_carousel',
              next: '#nextport_custom_carousel',
              pagination: false,
              swipe: true,
                items: {visible: null
                }
            });
        }
        setWidths();
        initCarousel_custom();
        jQuery(window).on("debouncedresize", function( event ) {
            $container.trigger("destroy");
            setWidths();
            initCarousel_custom(); 
            });
          function getUnitWidth() {
          var width;
          if(jQuery(window).width() <= 480) {
          width = $wcontainer.width() / <?php echo $ss;?>;
          } else if(jQuery(window).width() <= 767) {
          width = $wcontainer.width() / <?php echo $xs;?>;
          } else if(jQuery(window).width() <= 990) {
          width = $wcontainer.width() / <?php echo $sm;?>;
          } else {
          width = $wcontainer.width() / <?php echo $md;?>;
          }
          return width;
          }
          function setWidths() {
          var unitWidth = getUnitWidth() -1;
          $container.children().css({ width: unitWidth });
          }

});
</script> 