<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product, $virtue_premium;
if(isset($virtue_premium['product_simg_resize']) && $virtue_premium['product_simg_resize'] == 0) {
	$presizeimage = 0;
} else {
	$presizeimage = 1;
		if(isset($virtue_premium['shop_img_ratio'])) { $img_ratio = $virtue_premium['shop_img_ratio']; } else { $img_ratio = 'square';}
		if(isset($virtue_premium['singleproduct_layout']) && $virtue_premium['singleproduct_layout'] == 'largeimg') { 
				if(kadence_display_sidebar()) {$productimgwidth = 407; } else { $productimgwidth = 748;}
		} else {
			if(kadence_display_sidebar()) { $productimgwidth = 334; } else { $productimgwidth = 456; }
		}
		if($img_ratio == 'portrait') {
					$tempproductimgheight = $productimgwidth * 1.35;
					$productimgheight = floor($tempproductimgheight);
		} else if($img_ratio == 'landscape') {
					$tempproductimgheight = $productimgwidth / 1.35;
					$productimgheight = floor($tempproductimgheight);
		} else if($img_ratio == 'widelandscape') {
					$tempproductimgheight = $productimgwidth / 2;
					$productimgheight = floor($tempproductimgheight);
		} else {
					$productimgheight = $productimgwidth;
		}		

}


?>
<div class="images kad-light-gallery">
	<div class="product_image">
	<?php
		if ( has_post_thumbnail() ) {
			$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );

			if($presizeimage == 1){
					$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
					$product_image_url = $product_image[0]; 
					$image_url = aq_resize($product_image_url, $productimgwidth, $productimgheight, true);
					if(empty($image_url)) {$image_url = $product_image_url;} 
					$image = '<img width="'.$productimgwidth.'" height="'.$productimgheight.'" src="'.$image_url.'" class="attachment-shop_single wp-post-image" title="'.esc_attr( get_the_title( get_post_thumbnail_id() ) ).'">';
			} else {
			$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title' => $image_title
				) );
			}
			$attachment_count   = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s"  rel="lightbox' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID );

		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID );

		}
	?>
	</div>
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
