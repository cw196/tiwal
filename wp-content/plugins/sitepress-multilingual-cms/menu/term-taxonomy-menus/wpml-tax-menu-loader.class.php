<?php

class WPML_Tax_Menu_Loader{

	private $taxonomy;

	public function __construct( $taxonomy ) {
		$this->taxonomy = $taxonomy;
		add_action ( 'init', array( $this, 'init' ) );
		add_action( 'after-category-table', array( $this, 'category_display_action' ), 1, 0 );
	}

	public function init(){
		global $sitepress;

		require ICL_PLUGIN_PATH . '/menu/term-taxonomy-menus/wpml-term-language-filter.class.php';
		if ( ($trid = filter_input(INPUT_GET, 'trid')) && ($source_lang = filter_input(INPUT_GET, 'source_lang')) ) {
			$translations = $sitepress->get_element_translations ( $trid, 'tax_' . $this->taxonomy );
			if ( isset( $translations[ $_GET[ 'lang' ] ] ) ) {
				wp_redirect ( get_edit_term_link ( $translations[ $_GET[ 'lang' ] ]->term_id, $_GET[ 'taxonomy' ] ) );
				exit;
			} else {
				add_action ( 'admin_notices', array( $this, '_tax_adding' ) );
			}
		}
		$term_lang_filter = new WPML_Term_Language_Filter( icl_get_setting ( 'default_language' ) );
		if ( $this->taxonomy === 'category' ) {
			add_action ( 'edit_category_form', array( $this, 'wpml_edit_term_form' ) );
		} else {
			add_action ( 'add_tag_form', array( $this, 'wpml_edit_term_form' ) );
			add_action ( 'edit_tag_form', array( $this, 'wpml_edit_term_form' ) );
		}
		add_action ( 'admin_print_scripts-edit-tags.php', array( $this, 'js_scripts_tags' ) );
		add_filter ( 'wp_dropdown_cats', array( $this, 'wp_dropdown_cats_select_parent' ), 10, 2 );
		add_action ( 'admin_footer', array( $term_lang_filter, 'terms_language_filter' ) );
	}

	/**
	 * Filters the display of the categories list in order to prevent the default category from being delete-able.
	 * This is done by printing a hidden div containing a JSON encoded array with all category id's, the checkboxes of which are to be removed.
	 *
	 */
	public function category_display_action() {
		/** @var WPML_Term_Translation $wpml_term_translations */
		global $wpml_term_translations;

		if ( ( $default_category_id = get_option ( 'default_category' ) ) ) {
			$default_cat_ids = array();

			$translations = $wpml_term_translations->get_element_translations ( $default_category_id );
			foreach ( $translations as $lang => $translation ) {
				$default_cat_ids [ ] = $wpml_term_translations->term_id_in ( $default_category_id, $lang );
			}
			echo '<div id="icl-default-category-ids" style="display: none;">'
			     . wp_json_encode ( $default_cat_ids ) . '</div>';
		}
	}

	public function js_scripts_tags() {
		wp_enqueue_script( 'sitepress-tags', ICL_PLUGIN_URL . '/res/js/tags.js', array(), ICL_SITEPRESS_VERSION );
	}

	function wp_dropdown_cats_select_parent( $html, $args ) {
		global $wpdb, $sitepress;
		if ( ( $trid = filter_input( INPUT_GET, 'trid', FILTER_SANITIZE_NUMBER_INT ) ) ) {
			$element_type     = $taxonomy = isset( $args[ 'taxonomy' ] ) ? $args[ 'taxonomy' ] : 'post_tag';
			$icl_element_type = 'tax_' . $element_type;
			$source_lang      = isset( $_GET[ 'source_lang' ] ) ? $_GET[ 'source_lang' ] : $sitepress->get_default_language();
			$parent           = $wpdb->get_var( $wpdb->prepare("
				SELECT parent
				FROM {$wpdb->term_taxonomy} tt
					JOIN {$wpdb->prefix}icl_translations tr ON tr.element_id=tt.term_taxonomy_id
                    AND tr.element_type=%s AND tt.taxonomy=%s
				WHERE trid=%d AND tr.language_code=%s
			", $icl_element_type, $taxonomy, $trid, $source_lang ) );
			if ( $parent ) {
				$parent = (int)icl_object_id( $parent, $element_type );
				$html   = str_replace( 'value="' . $parent . '"', 'value="' . $parent . '" selected="selected"', $html );
			}
		}

		return $html;
	}

	/**
	 * @param Object $term
	 */
	public function wpml_edit_term_form( $term ) {
		include ICL_PLUGIN_PATH . '/menu/taxonomy-menu.php';
	}

	function _tax_adding() {
		global $sitepress;

		$trid         = filter_input ( INPUT_GET, 'trid', FILTER_SANITIZE_NUMBER_INT );
		$taxonomy     = filter_input ( INPUT_GET, 'taxonomy' );
		$translations = $trid && $taxonomy ?
			$sitepress->get_element_translations ( $trid, 'tax_' . $taxonomy ) : array();
		$name         = isset( $translations[ $_GET[ 'source_lang' ] ] ) ? $translations[ $_GET[ 'source_lang' ] ]
			: false;
		$name         = isset( $name->name ) ? $name->name : false;
		if ( $name !== false ) {
			$tax_name = apply_filters ( 'the_category', $name );
			echo '<div id="icl_tax_adding_notice" class="updated fade"><p>'
			     . sprintf ( __ ( 'Adding translation for: %s.', 'sitepress' ), $tax_name )
			     . '</p></div>';
		}
	}

}