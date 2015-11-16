<?php	
		if(is_front_page()) {
				global $virtue_premium; $sidebar = $virtue_premium['home_sidebar'];
				if (!empty($sidebar)) {
					dynamic_sidebar($sidebar);
					}
				else  {
					dynamic_sidebar('sidebar-primary');
				} 
		} elseif( class_exists('woocommerce') and (is_shop())) {
			
				global $virtue_premium; $sidebar = $virtue_premium['shop_sidebar'];
	 			if (!empty($sidebar)) {
					dynamic_sidebar($sidebar);
					}
				else  {
					dynamic_sidebar('sidebar-primary');
				} 
		} elseif( class_exists('woocommerce') and (is_product_category() || is_product_tag())) {
				global $virtue_premium; $sidebar = $virtue_premium['shop_cat_sidebar'];
	 			if (!empty($sidebar)) {
					dynamic_sidebar($sidebar);
					}
				else  {
					dynamic_sidebar('sidebar-primary');
				} 
		} elseif (class_exists('woocommerce') and is_product()) {
			global $post; $sidebar = get_post_meta( $post->ID, '_kad_sidebar_choice', true ); 
	 		if (empty($sidebar) || $sidebar == 'default') {
	 				global $virtue_premium; $psidebar = $virtue_premium['product_sidebar_default_sidebar'];
	 				if(!empty($psidebar)) {
						dynamic_sidebar($psidebar);
					} else {
						dynamic_sidebar('sidebar-primary');
					}
			} else if(!empty($sidebar)) {
				dynamic_sidebar($sidebar);
			} else {
					dynamic_sidebar('sidebar-primary');
				}
		} elseif( class_exists('woocommerce') and (is_account_page())) {
				    get_template_part('templates/account', 'sidebar');
		} elseif( is_page_template('page-blog.php') || is_page_template('page-blog-grid.php') || is_page_template('page-sidebar.php') || is_page_template('page-feature-sidebar.php') || is_single() || is_singular('staff') ) {
		global $post; $sidebar = get_post_meta( $post->ID, '_kad_sidebar_choice', true ); 
	 		if (!empty($sidebar)) {
					dynamic_sidebar($sidebar);
				}
			else  {
					dynamic_sidebar('sidebar-primary');
				} 
		} elseif (is_archive()) {
				global $virtue_premium; 
				if(isset($virtue_premium['blog_cat_sidebar'])) {
					dynamic_sidebar($virtue_premium['blog_cat_sidebar']);
					} else  {
					dynamic_sidebar('sidebar-primary');
				} 
		}
		elseif(is_category()) {
			global $virtue_premium; 
				if(isset($virtue_premium['blog_cat_sidebar'])) {
					dynamic_sidebar($virtue_premium['blog_cat_sidebar']);
					} else  {
					dynamic_sidebar('sidebar-primary');
				} 
		}
		elseif (is_tag()) {
			dynamic_sidebar('sidebar-primary');
		}
		elseif (is_post_type_archive()) {
			dynamic_sidebar('sidebar-primary');
		}
		 elseif (is_day()) {
			 dynamic_sidebar('sidebar-primary');
		 }
		 elseif (is_month()) {
			 dynamic_sidebar('sidebar-primary');
		 }
		 elseif (is_year()) {
			 dynamic_sidebar('sidebar-primary');
		 }
		 elseif (is_author()) {
			 dynamic_sidebar('sidebar-primary');
		}
		elseif (is_search()) {
				global $virtue_premium; 
				if(isset($virtue_premium['search_sidebar'])) {
					dynamic_sidebar($virtue_premium['search_sidebar']);
					} else  {
					dynamic_sidebar('sidebar-primary');
				} 
		}
		else {
		dynamic_sidebar('sidebar-primary');
	}
?>