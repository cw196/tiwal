<?php

class GFML_TM_API extends Gravity_Forms_Multilingual {

	public function __construct() {
		parent::__construct();

		$migrated = get_option ( 'gfml_pt_migr_comp' );
		if ( !$migrated ) {
			global $wpdb;
			if ( $wpdb->get_var ( "SHOW TABLES LIKE 'rg_form'" ) === $wpdb->prefix . 'rg_form'
			     && $this->has_post_gravity_from_translations ()
			) {
				require 'gfml-migration.class.php';
				$migration_object = new GFML_Migration( $this );
				$migration_object->migrate ();
			}
			update_option ( 'gfml_pt_migr_comp', true );
		}
	}

	public function get_type() {
		return 'gravity_form';
	}

	public function get_st_context( $form_id ) {
		return sanitize_title_with_dashes( ICL_GRAVITY_FORM_ELEMENT_TYPE . '-' . $form_id );
	}

	public function get_string_prefix_id( $form ) {
		global $wpdb;
		$id = isset( $form[ 'id' ] ) ? $form[ 'id' ] : false;

		$query = $id !== false ? $wpdb->prepare( "SELECT ID
             FROM {$wpdb->prefix}icl_string_packages
             WHERE name = %s
               AND kind_slug = %s
             LIMIT 1", $id, ICL_GRAVITY_FORM_ELEMENT_TYPE ) : '';

		return $wpdb->get_var( $query );
	}

	protected function get_form_package( $form ) {
		$form_package            = new stdClass();
		$form_package->kind      = __( 'Gravity Form', 'gravity-forms-ml' );
		$form_package->kind_slug = ICL_GRAVITY_FORM_ELEMENT_TYPE;
		$form_package->name      = $form[ 'id' ];
		$form_package->title     = $form[ 'title' ];

		return $form_package;
	}

	protected function register_gf_string( $string_value, $string_name, $package, $string_title, $string_kind = false ) {
		do_action( 'wpml_register_string', $string_value, $string_name, $package, $string_title, $string_kind );
	}

	protected function register_strings_common_fields( $form_field, $form_package ) {
		// Filter common properties
		foreach ( $this->form_fields as $form_field_key ) {
			if ( ! empty( $form_field->{$form_field_key} ) && $form_field->type !== 'page' ) {
				$string_name_parts    = array();
				$string_name_parts[ ] = 'field';
				$string_name_parts[ ] = $form_field->id;
				$string_name_parts[ ] = $form_field_key;
				$string_name          = implode( '-', $string_name_parts );
				$this->register_gf_string( $form_field->{$form_field_key}, $string_name, $form_package, $string_name, $form_field->type );
			}
		}
	}

	protected function register_strings_field_page( $form_package, $form_field ) {
		foreach ( array( 'text', 'imageUrl' ) as $key ) {
			$string_name_parts         = array();
			$string_name_parts[ ]      = 'page';
			$string_name_parts[ ]      = ( intval( $form_field->pageNumber ) - 1 );
			$string_name_parts[ ]      = 'temp-value';
			$string_name_parts_next[ ] = $key;
			if ( ! empty( $form_field->nextButton[ $key ] ) ) {
				$string_name_parts_next[ 2 ] = 'nextButton';
				$string_name                 = implode( '-', $string_name_parts_next );
				$this->register_gf_string( $form_field->nextButton[ $key ], $string_name, $form_package, $string_name, $form_field->type );
			}
			if ( ! empty( $form_field->previousButton[ $key ] ) ) {
				$string_name_parts[ 2 ] = 'previousButton';
				$string_name            = implode( '-', $string_name_parts );
				$this->register_gf_string( $form_field->previousButton[ $key ], $string_name, $form_package, $string_name, $form_field->type );
			}
		}
	}

	protected function register_strings_field_choices( $form_package, $form, $form_field, $register_with_type = false ) {
		if ( is_array( $form_field->choices ) ) {
			foreach ( $form_field->choices as $index => $choice ) {
				$this->register_strings_field_choice( $form_package, $form, $form_field, $choice, $register_with_type );
			}
		}
	}

	protected function register_strings_field_choice( $form_package, $form, $form_field, $choice, $register_with_type ) {
		$string_name_parts = array();
		if ( $register_with_type ) {
			$string_name_parts[ ] = $form_field->type;
		} else {
			$string_name_parts[ ] = 'field';
		}
		$string_name_parts[ ] = $form_field->id;
		$string_name_parts[ ] = 'choice';
		$string_name_parts[ ] = $choice[ 'text' ];
		$string_name          = implode( '-', $string_name_parts );

		$string_name = $this->_sanitize_string_name( $string_name, $form );
		$this->register_gf_string( $choice[ 'text' ], $string_name, $form_package, $string_name, $form_field->type );

		return $string_name;
	}

	protected function register_strings_field_post_custom( $form_package, $form_field ) {
		// TODO if multi options - 'choices' (register and translate) 'inputType' => select, etc.
		if ( $form_field->customFieldTemplate != '' ) {
			$string_name_parts    = array();
			$string_name_parts[ ] = 'field';
			$string_name_parts[ ] = $form_field->id;
			$string_name_parts[ ] = 'customFieldTemplate';
			$string_name          = implode( '-', $string_name_parts );
			$this->register_gf_string( $form_field->customFieldTemplate, $string_name, $form_package, $string_name, $form_field->type );
		}
	}

	protected function register_strings_field_post_category( $form_package, $form_field ) {
		// TODO if multi options - 'choices' have static values (register and translate) 'inputType' => select, etc.
		if ( $form_field->categoryInitialItem != '' ) {
			$string_name_parts    = array();
			$string_name_parts[ ] = 'field';
			$string_name_parts[ ] = $form_field->id;
			$string_name_parts[ ] = 'categoryInitialItem';
			$string_name          = implode( '-', $string_name_parts );
			$this->register_gf_string( $form_field->categoryInitialItem, $string_name, $form_package, $string_name, $form_field->type );
		}
	}

	protected function register_strings_field_html( $form_package, $form_field ) {
		$string_name_parts    = array();
		$string_name_parts[ ] = 'field';
		$string_name_parts[ ] = $form_field->id;
		$string_name_parts[ ] = 'content';
		$string_name          = implode( '-', $string_name_parts );
		$this->register_gf_string( $form_field->content, $string_name, $form_package, $string_name, $form_field->type );
	}

	protected function register_strings_field_list( $form_package, $form, $form_field ) {
		$this->register_strings_field_choices( $form_package, $form, $form_field, true );
	}

	protected function register_strings_field_option( $form_package, $form, $form_field ) {
		// Price fields can be single or multi-option field type
		if ( in_array( $form_field->inputType, array( 'singleproduct', 'singleshipping' ), true )
		     && $form_field->basePrice != ''
		) {
			$this->register_gf_string( $form_field->basePrice, "{$form_field->type}-{$form_field->id}-basePrice", $form_package, "{$form_field->type}-{$form_field->id}-basePrice", $form_field->type );
		} else if ( in_array( $form_field->inputType, array( 'select', 'checkbox','list', 'radio', 'hiddenproduct' ), true )
		            && is_array( $form_field->choices )
		) {

			foreach ( $form_field->choices as $index => $choice ) {
				$string_name = $this->register_strings_field_choice( $form_package, $form, $form_field, $choice, true );
				if ( isset( $choice[ 'price' ] ) ) {
					$string_name = "{$string_name}-price";
					$this->register_gf_string( $choice[ 'price' ], $string_name, $form_package, $string_name, $form_field->type );
				}
			}
		}
	}

	protected function register_strings_fields( $form, $form_package ) {
		// Common field properties
		$this->_get_field_keys();

		// Filter form fields (array of GF_Field objects)
		foreach ( $form[ 'fields' ] as $field_id => $form_field ) {

			$this->register_strings_common_fields( $form_field, $form_package );

			// Field specific code
			switch ( $form_field->type ) {
				case 'html':
					$this->register_strings_field_html( $form_package, $form_field );
					break;
				case 'page':
					$this->register_strings_field_page( $form_package, $form_field );
					break;
				case 'list':
				case 'select':
				case 'multiselect':
				case 'checkbox':
				case 'radio':
					$this->register_strings_field_list( $form_package, $form, $form_field );
					break;
				case 'option':
					$this->register_strings_field_option( $form_package, $form, $form_field );
					break;
				case 'post_custom_field':
					$this->register_strings_field_post_custom( $form_package, $form_field );
					break;
				case 'post_category':
					$this->register_strings_field_post_category( $form_package, $form_field );
					break;
				default:
					do_action( 'wpml_gf_register_strings_field_{$form_field->type}', $form, $form_package, $form_field );
			}
		}
	}

	protected function register_strings_main_fields( $form_package ) {
		$form_keys = $this->_get_form_keys();
		foreach ( $form_keys as $key ) {
			$value = ! empty( $form[ $key ] ) ? $form[ $key ] : null;
			if ( $value !== null ) {
				$this->register_gf_string( $value, $key, $form_package, $key );
			}
		}
	}

	protected function register_strings_pagination( $form_package ) {
		// Paging Page Names - $form["pagination"]["pages"][i]
		if ( ! empty( $form[ 'pagination' ] )
		     && isset( $form[ 'pagination' ][ 'pages' ] )
		     && is_array( $form[ 'pagination' ][ 'pages' ] )
		) {
			foreach ( $form[ 'pagination' ][ 'pages' ] as $key => $page_title ) {
				$this->register_gf_string( $page_title, $key, $form_package, "page-" . ( intval( $key ) + 1 ) . '-title' );
			}
			$value = ! empty( $form[ 'pagination' ][ 'progressbar_completion_text' ] ) ? $form[ 'pagination' ][ 'progressbar_completion_text' ] : null;
			if ( $value !== null ) {
				$this->register_gf_string( $value, "progressbar_completion_text", $form_package, "progressbar_completion_text" );
			}
			$value = ! empty( $form[ 'lastPageButton' ][ 'text' ] ) ? $form[ 'lastPageButton' ][ 'text' ] : null;
			if ( $value !== null ) {
				$this->register_gf_string( $value, "lastPageButton", $form_package, "lastPageButton" );
			}
		}
	}

	protected function register_strings( $form ) {
		global $sitepress;

		if ( ! isset( $form[ 'id' ] ) ) {
			return false;
		}

		$form_id      = $form[ 'id' ];
		$form_package = $this->get_form_package( $form );

		// Cache
		$current_lang = $sitepress->get_current_language();
		if ( isset( $this->_current_forms[ $form_id ][ $current_lang ] ) ) {
			return $this->_current_forms[ $form_id ][ $current_lang ];
		}

		$this->register_strings_main_fields( $form_package );
		$this->register_strings_pagination( $form_package );
		$this->register_strings_fields( $form, $form_package );

		$this->_current_forms[ $form_id ][ $current_lang ] = $form;

		return $form;
	}

	public function update_form_translations( $form, $is_new, $needs_update = true ) {
		$this->register_strings( $form );
		$this->cleanup_form_strings( $form );
	}

	protected function cleanup_form_strings( $form ) {
		if ( isset( $form[ 'id' ] ) ) {

			global $wpdb;
			$form_id         = $form[ 'id' ];
			$current_strings = $this->get_form_strings( $form_id );
			$this->register_strings( $form );
			$st_context       = $this->get_st_context( $form_id );
			$database_strings = (bool) $current_strings !== false ? $wpdb->get_col( $wpdb->prepare( "SELECT s.name
                 FROM {$wpdb->prefix}icl_strings s
                 WHERE s.context = %s", $st_context ) ) : array();

			foreach ( $database_strings as $key => $string_name ) {
				if ( isset( $current_strings[ substr( $key, ( strlen( $form_id ) + 1 ) ) ] ) ) {
					icl_unregister_string( $st_context, $string_name );
				}
			}
		}
	}

	public function after_delete_form( $form_id ) {
		do_action( 'delete_package_action', $form_id, ICL_GRAVITY_FORM_ELEMENT_TYPE );
	}

	protected function gform_id( $package_id ) {
		return $package_id;
	}

	private function has_post_gravity_from_translations() {
		global $wpdb;

		$post_gravity_from_translations_query = "SELECT COUNT(*)
                               FROM {$wpdb->prefix}icl_translations
                               WHERE element_type = 'post_gravity_from'";
		$post_gravity_from_translations_count = $wpdb->get_var( $post_gravity_from_translations_query );

		return $post_gravity_from_translations_count == 0;
	}
}