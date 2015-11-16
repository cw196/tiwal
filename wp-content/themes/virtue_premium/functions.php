<?php
/*-----------------------------------------------------------------------------------*/
/* Include Theme Functions */
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain('virtue', get_template_directory() . '/languages');
require_once locate_template('/themeoptions/options/virtue_extension.php'); // Options framework extension
require_once locate_template('/themeoptions/framework.php');          		// Options framework
require_once locate_template('/themeoptions/options.php');          		// Options framework
require_once locate_template('/lib/utils.php');           		// Utility functions
require_once locate_template('/lib/init.php');            		// Initial theme setup and constants
require_once locate_template('/lib/sidebar.php');         		// Sidebar class
require_once locate_template('/lib/config.php');          		// Configuration
require_once locate_template('/lib/cleanup.php');        		// Cleanup
require_once locate_template('/lib/custom-nav.php');        	// Nav Options
require_once locate_template('/lib/nav.php');            		// Custom nav modifications
require_once locate_template('/lib/metaboxes.php');     		// Custom metaboxes
require_once locate_template('/lib/gallery_metabox.php');     	// Custom metaboxes
require_once locate_template('/lib/comments.php');        		// Custom comments modifications
require_once locate_template('/lib/shortcodes.php');      		// Shortcodes
require_once locate_template('/lib/carousel_shortcodes.php');   // Carousel Shortcodes
require_once locate_template('/lib/custom_carousel_shortcodes.php');   // Carousel Shortcodes
require_once locate_template('/lib/testimonial_shortcodes.php');   // Carousel Shortcodes
require_once locate_template('/lib/testimonial_form_shortcode.php');   // Carousel Shortcodes
require_once locate_template('/lib/blog_shortcodes.php');   	// Blog Shortcodes
require_once locate_template('/lib/image_menu_shortcodes.php'); // image menu Shortcodes
require_once locate_template('/lib/google_map_shortcode.php');  // Map Shortcodes
require_once locate_template('/lib/portfolio_shortcodes.php'); 	// Portfolio Shortcodes
require_once locate_template('/lib/portfolio_type_shortcodes.php'); 	// Portfolio Shortcodes
require_once locate_template('/lib/staff_shortcodes.php'); 			// Staff Shortcodes
require_once locate_template('/lib/gallery.php');      			// Gallery Shortcode
require_once locate_template('/lib/post-types.php');      		// Post Types
require_once locate_template('/lib/premium_widgets.php'); 		// Gallery Widget
require_once locate_template('/lib/widgets.php');         		// Sidebars and widgets
require_once locate_template('/lib/Mobile_Detect.php');        	// Mobile Detect
require_once locate_template('/lib/aq_resizer.php');      		// Resize on the fly
require_once locate_template('/lib/revslider-activate.php');   	// Plugin Activation
require_once locate_template('/lib/taxonomy-meta-class.php');   // Taxonomy meta boxes
require_once locate_template('/lib/taxonomy-meta.php');         // Taxonomy meta boxes
require_once locate_template('/lib/scripts.php');        		// Scripts and stylesheets
require_once locate_template('/lib/custom.php');          		// Custom functions
require_once locate_template('/lib/admin_scripts.php');    // Icon functions
require_once locate_template('/lib/authorbox.php');         	// Author box
require_once locate_template('/lib/breadcrumbs.php');         	// Breadcrumbs
require_once locate_template('/lib/custom-woocommerce.php'); 	// Woocommerce functions
require_once locate_template('/lib/custom_css.php'); 			// Fontend Custom CSS
require_once locate_template('/lib/wp-updates-theme.php');
new WPUpdatesThemeUpdater_647( 'http://wp-updates.com/api/2/theme', basename( get_template_directory() ) );

function virtue_shortcode_init() {
	if(is_admin()){ if(kad_is_edit_page()){require_once locate_template('/lib/kad_shortcodes.php');	}}
}
add_action('init', 'virtue_shortcode_init');

