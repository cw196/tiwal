<?php
/*
Template Name: Sidebar
*/
?>

	<div id="pageheader" class="titleclass">
		<div class="container">
			<?php get_template_part('templates/page', 'header'); ?>
		</div><!--container-->
	</div><!--titleclass-->
	
    <div id="content" class="container">
   		<div class="row">
     		<div class="main <?php echo kadence_main_class(); ?>" id="ktmain" role="main">
				<?php get_template_part('templates/content', 'page');
				global $virtue_premium; if(isset($virtue_premium['page_comments']) && $virtue_premium['page_comments'] == '1') { 
					comments_template('/templates/comments.php');
				} ?>
			</div><!-- /.main -->