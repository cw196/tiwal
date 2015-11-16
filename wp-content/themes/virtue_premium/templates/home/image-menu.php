
  <?php global $virtue_premium; if(!empty($virtue_premium['img_menu_height'])) {$img_height = $virtue_premium['img_menu_height'];} else {$img_height = 110;} 
                $slides = $virtue_premium['home_image_menu'];
                if(!empty($virtue_premium['home_image_menu_column'])) {$columnsize = $virtue_premium['home_image_menu_column'];} else {$columnsize = 3;}
                        if ($columnsize == '2') {$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $img_width = 559;} 
                        else if ($columnsize == '3'){ $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';  $img_width = 367;} 
                        else if ($columnsize == '6'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $img_width = 240;} 
                        else if ($columnsize == '5'){ $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $img_width = 240;} 
                        else {$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $img_width = 270;}             
                	?>
<div class="home-padding home-margin">
<div class="rowtight homepromo">
	<?php $counter = 1;?>
	<?php foreach ($slides as $slide) :
	if(!empty($slide['target']) && $slide['target'] == 1) {$target = '_blank';} else {$target = '_self';}
		$the_image = aq_resize($slide['url'], $img_width, $img_height, true); 
		if(empty($the_image)) {$the_image = $slide['url'];}?>
		        <div class="<?php echo $itemsize;?> kad-animation <?php echo 'homeitemcount'.$counter;?>" data-animation="fade-in" data-delay="<?php echo $counter*150;?>">
		        	<?php if($slide['link'] != '') echo '<a href="'.$slide['link'].'" class="homepromolink" target="'.$target.'">'; ?>
				        <div class="infobanner" style="background: url(<?php echo $the_image; ?>) center center no-repeat; height:<?php echo $img_height ?>px;">
				        	<div class="home-message" style="height:<?php echo $img_height ?>px;">
				        		<?php if ($slide['title'] != '') echo '<h4>'.$slide['title'].'</h4>'; ?>
				            	<?php if ($slide['description'] != '') echo '<h5>'.$slide['description'].'</h5>';?>
				            </div>
				        </div>
		        	<?php if($slide['link'] != '') echo '</a>'; ?>
		        </div>
		        <?php $counter ++ ?>
	<?php endforeach; ?>
</div>
</div> <!--homepromo -->