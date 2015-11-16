<div class="sliderclass carousel_outerrim">
  <?php  global $virtue_premium; 
         if(isset($virtue_premium['slider_size'])) {$slideheight = $virtue_premium['slider_size'];} else { $slideheight = 400; }
         if(isset($virtue_premium['slider_size_width'])) {$slidewidth = $virtue_premium['slider_size_width'];} else { $slidewidth = 400; }
        if(isset($virtue_premium['home_slider'])) {$slides = $virtue_premium['home_slider']; } else {$slides = '';}
        if(isset($virtue_premium['slider_autoplay']) && $virtue_premium['slider_autoplay'] == "1" ) {$autoplay ='true';} else {$autoplay = 'false';}
        if(isset($virtue_premium['slider_pausetime'])) {$pausetime = $virtue_premium['slider_pausetime'];} else {$pausetime = '7000';}
                ?>
    <div id="img-carousel-gallery" class="fredcarousel fadein-carousel" style="overflow:hidden; height: <?php echo $slideheight;?>px">
                <div class="gallery-carousel">
                  <?php foreach ($slides as $slide) {
                                $image = aq_resize($slide['url'], null, $slideheight, false, false);
                                  if(empty($image)) {$image = $slide['url'];}
                                  echo '<div class="carousel_gallery_item" style="float:left; margin: 0 5px; width:'.$image[1].'px; height:'.$image[2].'px;"><img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" /></div>';
                                }?>   
                <script type="text/javascript">
                jQuery( window ).load(function () {
                    var $wcontainer = jQuery('#img-carousel-gallery');
                    var $container = jQuery('#img-carousel-gallery .gallery-carousel');
                      var align = false;
                      var carheight = <?php echo $slideheight; ?>;
                          setWidths();
                          $container.carouFredSel({
                              width: '100%',
                              height: carheight,
                              align: align,
                               auto: {play: <?php echo $autoplay; ?>, timeoutDuration: <?php echo $pausetime; ?>},
                              scroll: {
                                items : 1,
                                easing: 'quadratic'
                              },
                              items: {
                                visible: 1,
                                width: 'variable'
                              },
                              prev: '#img-carousel-gallery .prev_carousel',
                              next: '#img-carousel-gallery .next_carousel',
                              swipe: {
                                onMouse: true,
                                onTouch: true
                              },
                              onCreate: function() {
                                jQuery('.gallery-carousel').css('positon','static');
                              }
                            });
                             jQuery(window).on("debouncedresize", function( event ) {
                            // set the widths on resize
                            setWidths();
                              $container.trigger("updateSizes");
                            });
                          $wcontainer.animate({'opacity' : 1});
                          $wcontainer.css({ height: 'auto' });
                          $wcontainer.parent().removeClass('loading');
                          // set all the widths to the elements
                          function setWidths() {
                            if(jQuery(window).width() <= 768) {
                              align = 'center';
                            carheight = null;
                            var unitWidth = jQuery(window).width() -10;
                            $container.children().css({ width: unitWidth });
                            $container.children().css({ height: 'auto' });
                          }
                        }

                });
                
              </script>         
              </div> <!--post gallery carousel-->
            <div class="clearfix"></div>
              <a id="prevport_imgcarousel" class="prev_carousel icon-arrow-left" href="#"></a>
              <a id="nextport_imgcarousel" class="next_carousel icon-arrow-right" href="#"></a>
          </div> <!--fredcarousel-->
          </div> <!--sliderclass -->