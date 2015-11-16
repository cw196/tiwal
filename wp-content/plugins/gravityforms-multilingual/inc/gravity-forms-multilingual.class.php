<?php

define( 'ICL_GRAVITY_FORM_ELEMENT_TYPE', 'gravity_form' );

/**
 * Class Gravity_Forms_Multilingual
 * 
 * - Registers and updates WPML translation jobs
 * - Enables GF forms on WPML TM Dashboard screen
 * - Filters GF form on frontend ('gform_pre_render')
 * - Translates notifications
 * 
 * Changelog
 * 
 * 1.2.2
 * - Added support for GF 1.9.x
 * -- Reviewed gf_pre_render and get_form_strings
 * -- Added handling GF_Field objects
 * 
 * @version 1.2.2
 */
abstract class Gravity_Forms_Multilingual{

	protected $_current_forms;
	protected $form_fields;
	protected $missing;

    /**
     * Registers filters and hooks.
     * 
     * Called on 'init' hook at default priority.
     */
    function __construct(){
	    $this->_current_forms = array();
	    $this->form_fields    = array();
	    $this->missing        = array();

    	if ( !$this->required_plugins() ) {
            return;
        }
        /* WPML translation job hooks */
        add_filter( 'WPML_get_link', array($this, 'get_link'), 10, 4 );
        add_filter( 'page_link', array($this, 'gform_redirect'), 10, 3 );

        /* GF frontend hooks: form rendering and submission */
        if ( version_compare( GFCommon::$version, '1.9', '<' ) ) {
            add_filter( 'gform_pre_render', array($this, 'gform_pre_render_deprecated'), 10, 2 );
        } else {
            add_filter( 'gform_pre_render', array($this, 'gform_pre_render'), 10, 2 );
        }
        add_filter( 'gform_pre_submission_filter', array($this, 'gform_pre_submission_filter') );
        add_filter( 'gform_notification', array($this, 'gform_notification'), 10, 3 );
        add_filter( 'gform_field_validation', array($this, 'gform_field_validation'), 10, 4 );
        add_filter( 'gform_merge_tag_filter', array($this, 'gform_merge_tag_filter'), 10, 5 );

        /* GF admin hooks for updating WPML translation jobs */
        add_action( 'gform_after_save_form', array($this, 'update_form_translations'), 10, 2 );
        add_action( 'gform_pre_confirmation_save', array($this, 'update_confirmation_translations'), 10, 2 );
        add_action( 'gform_pre_notification_save', array($this, 'update_notifications_translations'), 10, 2 );
        add_action( 'gform_after_delete_form', array($this, 'after_delete_form') );
        add_action( 'gform_after_delete_field', array($this, 'after_delete_field'), 10, 2 );

    }

    protected abstract function get_type();

    public abstract function get_st_context( $form );

    protected abstract function update_form_translations( $form_meta, $is_new, $needs_update = true );

    public abstract function after_delete_form( $form_id );

    protected abstract function register_strings( $form );

    protected abstract function gform_id($id);

    public abstract function get_string_prefix_id($form);

    /**
     * Filters the link to the edit page of the Gravity Form
     *
     * @param String $item
     * @param Int $id
     * @param String $anchor
     * @param bool $hide_empty
     * @return bool|string
     */
    function get_link( $item, $id, $anchor, $hide_empty ) {
        if ( $item == "" && $id = $this->gform_id( $id ) && false === $anchor ) {
            global $wpdb;
            $anchor = $wpdb->get_var( $wpdb->prepare("SELECT title FROM {$wpdb->prefix}rg_form WHERE id = %d", $id ) );
            $item = $anchor ? sprintf( '<a href="%s">%s</a>', 'admin.php?page=gf_edit_forms&id=' . $id, $anchor ) : "";
        }

        return $id ? $item : "";
    }

    /**
     *
     * Check for missing plugins
     */
    private function required_plugins() {
        $this->missing = array();
		$allok = true;

		if ( !defined( 'ICL_SITEPRESS_VERSION' )
                || ICL_PLUGIN_INACTIVE
                || version_compare( ICL_SITEPRESS_VERSION,  '2.0.5', '<' ) ) {
            $this->missing['WPML'] = 'http://wpml.org';
            $allok = false;
        }

        if ( !class_exists( 'GFForms' ) ) {
            $this->missing['Gravity Forms'] = 'http://www.gravityforms.com/';
            $allok = false;
        }

        if ( !defined( 'WPML_TM_VERSION' ) ) {
            $this->missing['WPML Translation Management'] = 'http://wpml.org';
            $allok = false;
        }

        if ( !defined( 'WPML_ST_VERSION' ) ) {
            $this->missing['WPML String Translation'] = 'http://wpml.org';
            $allok = false;
        }

        if ( !$allok ) {
            add_action( 'admin_notices', array($this, 'missing_plugins_warning') );
        }
        return $allok;
	}

    /**
     * Missing plugins warning.
     */
	public function missing_plugins_warning() {
		$missing = '';
		$counter = 0;
		foreach ($this->missing as $title => $url) {
			$counter ++;
			if ($counter == sizeof($this->missing)) {
				$sep = '';
			} elseif ($counter == sizeof($this->missing) - 1) {
				$sep = ' ' . __('and', 'plugin woocommerce') . ' ';
			} else {
				$sep = ', ';
			}
			$missing .= '<a href="' . $url . '">' . $title . '</a>' . $sep;
		}
	?>
		<div class="message error"><p><?php printf(__('Gravity Forms Multilingual is enabled but not effective. It requires %s in order to work.', 'plugin woocommerce'), $missing); ?></p></div>
	<?php
	}

    /**
     * Fix for default lang parameter settings + default wordpress permalinks.
     */
    function gform_redirect( $link, $post_id, $sample ) {
        global $sitepress;
        $icl_settings = $sitepress->get_settings();
        if ( $icl_settings['language_negotiation_type'] == 3 ) {
            $link = str_replace( '&amp;lang=', '&lang=', $link );
        }
        return $link;
    }

    /**
     * Returns an array of keys under which translatable strings are saved in a Gravity Form array
     *
     * @return Array
     */
    protected function _get_form_keys() {
        if ( !isset( $this->_form_keys ) ) {
            $this->_form_keys = array(
                'title',
                'description',
                'limitEntriesMessage',
                'scheduleMessage',
                'postTitleTemplate',
                'postContentTemplate',
                'button-text',
                'button-imageUrl',
                'lastPageButton-text',
                'lastPageButton-imageUrl',
            );
        }
        return apply_filters( 'gform_multilingual_form_keys', $this->_form_keys );
    }

    /**
     * Returns an array of keys under which a Gravity Form Field array stores translatable strings
     *
     * @return Array
     */
    protected function _get_field_keys() {
        if ( !isset( $this->_field_keys ) ) {
            $this->_field_keys = array(
                'label',
                'adminLabel',
                'description',
                'defaultValue',
                'errorMessage');
        }
	    $this->form_fields = apply_filters( 'gform_multilingual_field_keys', $this->_field_keys );
        return $this->form_fields;
    }

    private function add_button_to_data( $string_data, $field, $kind, $key ) {
        $kind .= 'Button';
        if ( isset($field[ $kind ][ $key ] ) ) {
            $string_data[ 'page-' . ( $field[ 'pageNumber' ] - 1 ) . '-' . $kind . '-' . $key ] = $field[ $kind ][ $key ];
        }

        return $string_data;
    }

    private function add_pagination_data_deprecated($string_data, $field, $id){
        // page breaks are stored as belonging to the next page,
        // but their buttons are actually displayed in the previous page
        $string_data = $this->add_button_to_data ( $string_data, $field, 'next', 'text' );
        $string_data = $this->add_button_to_data ( $string_data, $field, 'next', 'imageUrl' );
        $string_data = $this->add_button_to_data ( $string_data, $field, 'previous', 'text' );
        $string_data = $this->add_button_to_data ( $string_data, $field, 'previous', 'imageUrl' );

        return $string_data;
    }

    /**
     * Translation job package - collect translatable strings from GF form.
     * 
     * @param int $form_id
     * @return array
     */
    private function _get_form_strings_deprecated($form_id) {

		$form = RGFormsModel::get_form_meta($form_id);
        $string_data = $this->get_form_main_strings($form);

        ///- Paging Page Names           - $form["pagination"]["pages"][i]
		if (isset($form["pagination"])) {
			foreach ($form['pagination']['pages'] as $key => $page_title) {
				$string_data['page-'.($key+1).'-title'] = $page_title;
			}
		}

		//Fields (including paging fields)
		$keys = $this->_get_field_keys();

		foreach ($form['fields'] as $id => $field) {
			if ($field['type'] != 'page') {
				foreach ($keys as $key) {
					if (isset($field[$key]) && $field[$key] != '') {
						$string_data['field-' . $field['id'] . '-' . $key] = $field[$key];
					}
				}
			}

			switch ($field['type']) {
				case 'text':
				case 'textarea':
				case 'email':
				case 'number':
				case 'section':
					break;

				case 'html':
					$string_data['field-' . $field['id'] . '-content'] = $field['content'];
					break;

                case 'page':
                    $string_data = $this->add_pagination_data_deprecated ( $string_data, $field, $id );
                    break;
				case 'select':
				case 'multiselect':
				case 'checkbox':
				case 'radio':
				case 'list':
                    if(!empty($field['choices'])) {
						foreach ($field['choices'] as $index => $choice) {
							$string_name = $this->_sanitize_string_name($field_type . '-' . $field['id'] . '-choice-' . $choice['text'], $form);
							$string_data[$string_name] = $choice['text'];
						}
					}
					break;

				case 'product':
                case 'shipping':
				case 'option':
                    // Price fields can be single or multi-option field type
                    if ( in_array( $field['inputType'], array( 'singleproduct', 'singleshipping' ) ) && isset( $field['basePrice'] ) ) {
                        $string_data["{$field['type']}-{$field['id']}-basePrice"] = $field['basePrice'];
                    } else if ( in_array( $field['inputType'], array( 'select', 'checkbox', 'radio', 'hiddenproduct' ) ) && !empty( $field['choices'] ) ) {
                        foreach ( $field['choices'] as $index => $choice ) {
                            $string_name = $this->_sanitize_string_name( "{$field['type']}-{$field['id']}-choice-{$choice['text']}", $form );
                            $string_data[$string_name] = $choice['text'];
                            if ( isset( $choice['price'] ) ) {
                                $string_data[$string_name . '-price'] = $choice['price'];
                            }
                        }
                    }
                    break;
				case 'post_custom_field':
                    if ( isset($field['customFieldTemplate']) ) {
                        $string_data['field-' . $field['id'] . '-customFieldTemplate'] = $field["customFieldTemplate"];
                    }
					break;
				case 'post_category':
					if(isset($field["categoryInitialItem"])){
						$string_data['field-' . $field['id'] . '-categoryInitialItem'] = $field["categoryInitialItem"];
					}
					break;
			}
		}

		// confirmations
		foreach ($form['confirmations'] as $key => $confirm) {
			switch ($confirm['type']) {
				case 'message':
					$string_data["field-confirmation-message_".$confirm['name']] = $confirm['message']; //add prefix 'field-' to get a textarea editor box
				break;
				case 'redirect':
					$string_data["confirmation-redirect_".$confirm['name']] = $confirm['url'];
				break;
				case 'page':
					$string_data["confirmation-page_".$confirm['name']] = $confirm['pageId'];
				break;
			}
		}

		//notifications: translate only those for user submitted emails
		if (!empty($form['notifications'])){
			foreach ($form['notifications'] as $key => $notif) {
				if ($notif['toType'] === 'field' || $notif['toType'] === 'email') {
					$string_data["notification-subject_".$notif['name']] = $notif['subject'];
					$string_data["field-notification-message_".$notif['name']] = $notif['message'];
				}
			}
		}

        return $string_data;

    }

    private function get_form_main_strings($form){
        $string_data = array();
        $form_keys = $this->_get_form_keys();

        // Form main fields
        foreach ( $form_keys as $key ) {
            $parts = explode( '-', $key );
            if ( sizeof( $parts ) == 1 ) {
                if ( isset( $form[$key] ) && $form[$key] != '' ) {
                    $string_data[$key] = $form[$key];
                }
            } else {
                if ( isset( $form[$parts[0]][$parts[1]] ) && $form[$parts[0]][$parts[1]] != '' ) {
                    $string_data[$key] = $form[$parts[0]][$parts[1]];
                }
            }
        }

        return $string_data;
    }

    /**
     * Translation job package - collect translatable strings from GF form.
     *
     * @todo See to merge this and gform_pre_render (already overlaping)
     * @param int $form_id
     * @return Array Associative array that holds the forms string values as values and uses the ST string name field
     *                   suffixes as indexes. The value $form_id_$index would be the actual ST icl_strings string name.
     */
    public function get_form_strings( $form_id ) {

        if ( version_compare( GFCommon::$version, '1.9', '<' ) ) {
            return $this->_get_form_strings_deprecated( $form_id );
        }

        $form = RGFormsModel::get_form_meta( $form_id );
        $string_data = $this->get_form_main_strings($form);

        // Pagination - Paging Page Names - $form["pagination"]["pages"][i]
        if ( isset( $form['pagination']['pages'] ) && is_array( $form['pagination']['pages'] ) ) {
            foreach ( $form['pagination']['pages'] as $key => $page_title ) {
                $string_data['page-' . ( intval( $key ) + 1) . '-title'] = $page_title;
            }
        }

        // Common field properties
        $keys = $this->_get_field_keys();

        // Fields
        foreach ($form['fields'] as $id => $field) {
            if ( $field->type != 'page' ) {
                foreach ( $keys as $key ) {
                    if ( $field->{$key} != '' ) {
                        $string_data["field-{$field->id}-{$key}"] = $field->{$key};
                    }
                }
            }

            switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'email':
                case 'number':
                case 'section':
                    break;
                case 'html':
                    $string_data["field-{$field->id}-content"] = $field->content;
                    break;
                case 'page':
                    /*
                     * Page breaks are stored as belonging to the next page,
                     * but their buttons are actually displayed in the previous page
                     */
                    $_bn = 'page-' . ( intval( $field->pageNumber ) - 1);
                    foreach ( array('text', 'imageUrl') as $key ) {
                        if ( isset( $field->nextButton[$key] ) ) {
                            $string_data["{$_bn}-nextButton-{$key}"] = $field->nextButton[$key];
                        }
                        if ( isset( $field->previousButton[$key] ) ) {
                            $string_data["{$_bn}-previousButton-{$key}"] = $field->previousButton[$key];
                        }
                    }
                    break;
                case 'select':
                case 'multiselect':
                case 'checkbox':
                case 'radio':
                case 'list':
                    if ( is_array( $field->choices ) ) {
                        foreach ( $field->choices as $index => $choice ) {
                            $string_name = $this->_sanitize_string_name( "{$field['type']}-{$field->id}-choice-{$choice['text']}", $form );
                            $string_data[$string_name] = $choice['text'];
                        }
                    }
                    break;

                case 'product':
                case 'shipping':
                case 'option':

                    // Price fields can be single or multi-option field type
                    if ( in_array( $field->inputType, array(
                            'singleproduct',
                            'singleshipping') ) && $field->basePrice != '' ) {
                        $string_data["{$field->type}-{$field->id}-basePrice"] = $field->basePrice;
                    } else if ( in_array( $field->inputType, array(
                            'select',
                            'checkbox',
                            'radio',
                            'hiddenproduct') ) && is_array( $field->choices ) ) {
                        foreach ( $field->choices as $index => $choice ) {
                            $string_name = $this->_sanitize_string_name( "{$field->type}-{$field->id}-choice-{$choice['text']}", $form );
                            $string_data[$string_name] = $choice['text'];
                            if ( isset( $choice['price'] ) ) {
                                $string_data["{$string_name}-price"] = $choice['price'];
                            }
                        }
                    }
                    break;
                case 'post_custom_field':
                    // TODO not registered at my tests
                    if ( $field->customFieldTemplate != '' ) {
                        $string_data["field-{$field->id}-customFieldTemplate"] = $field->customFieldTemplate;
                    }
                    break;
                case 'post_category':
                    if ( $field->categoryInitialItem != '' ) {
                        $string_data["field-{$field['id']}-categoryInitialItem"] = $field->categoryInitialItem;
                    }
                    break;
            }

        }

        // Confirmations
        if ( is_array( $form['confirmations'] ) ) {
            foreach ( $form['confirmations'] as $key => $confirm ) {
                switch ( $confirm['type'] ) {
                    case 'message':
                        // Add prefix 'field-' to get a textarea editor box
                        $string_data["field-confirmation-message_{$confirm['name']}"] = $confirm['message'];
                        break;
                    case 'redirect':
                        $string_data["confirmation-redirect_{$confirm['name']}"] = $confirm['url'];
                        break;
                    case 'page':
                        $string_data["confirmation-page_{$confirm['name']}"] = $confirm['pageId'];
                        break;
                }
            }
        }

        // Notifications: translate only those for user submitted emails
        if ( is_array( $form['notifications'] ) ) {
            foreach ( $form['notifications'] as $key => $notif ) {
                if ( $notif['toType'] == 'field'
                     || $notif['toType'] == 'email' ) {
                    $string_data["notification-subject_{$notif['name']}"] = $notif['subject'];
                    $string_data["field-notification-message_{$notif['name']}"] = $notif['message'];
                }
            }
        }

        return $string_data;
    }

    /**
     * @param Array $form
     * @param String $st_context
     * @param Int $form_st_id
     * @return Array
     */
    private function populate_translated_values($form, $st_context, $form_st_id){
        $form_keys = $this->_get_form_keys();

        foreach ($form_keys as $key) {
            $parts = explode('-', $key);
            if (sizeof($parts) == 1) {
                if (isset($form[$key]) && $form[$key] != '') {
                    $form[$key] = icl_t($st_context, $form_st_id . '_' . $key, $form[$key]);
                }
            } else {
                if (isset($form[$parts[0]][$parts[1]]) && $form[$parts[0]][$parts[1]] != '') {
                    $form[$parts[0]][$parts[1]] = icl_t($st_context, $form_st_id . '_' . $key, $form[$parts[0]][$parts[1]]);
                }
            }
        }

        return $form;
    }


	/**
     * Front-end form rendering (deprecated).
     */
    function gform_pre_render_deprecated( $form, $ajax = null ) {
        //render the form

		global $sitepress;
        $form_st_id = $this->get_string_prefix_id($form);
        $st_context = $this->get_st_context ( $form[ 'id' ] );

		$current_lang = $sitepress->get_current_language();
		if (isset($this->_current_forms[$form[ 'id' ]][$current_lang])) {
			return $this->_current_forms[$form[ 'id' ]][$current_lang];
		}

        $form = $this->populate_translated_values($form, $st_context, $form_st_id);

		///- Paging Page Names           - $form["pagination"]["pages"][i]
		if (isset($form["pagination"])) {
			foreach ($form['pagination']['pages'] as $key => $page_title) {
				$form['pagination']['pages'][$key] =
					icl_t($st_context,$form_st_id.'_page-'.($key+1).'-title',$form['pagination']['pages'][$key]);
			}
		}

		//Fields (including paging fields)
		$keys = $this->_get_field_keys();

		foreach ($form['fields'] as $id => $field) {

			foreach ($keys as $key) {
				if (isset($field[$key]) && $field[$key] != '' && $field['type'] !== 'page') {
					$form['fields'][$id][$key] = icl_t($st_context, $form_st_id . '_field-' . $field['id'] . '-' . $key, $field[$key]);
				}
			}

			switch ($field['type']) {
				case 'text':
				case 'textarea':
				case 'email':
				case 'number':
				case 'section':
					break;

				case 'html':
					$form['fields'][$id]['content'] = icl_t($st_context, $form_st_id . '_field-' . $field['id'] . '-content', $field['content']);
					break;

				case 'page':
					foreach (array('text','imageUrl') as $key) {
						if (isset($form['fields'][$id]['nextButton'][$key])) {
							$form['fields'][$id]['nextButton'][$key] = icl_t($st_context, $form_st_id . '_page-' . ($field['pageNumber']-1) . '-nextButton-'.$key, $field['nextButton'][$key]);
						}
						if (isset($form['fields'][$id]['previousButton'][$key])) {
							$form['fields'][$id]['previousButton'][$key] = icl_t($st_context, $form_st_id . '_page-' . ($field['pageNumber']-1) . '-previousButton-'.$key, $field['previousButton'][$key]);
						}
					}
					break;
				case 'select':
				case 'multiselect':
				case 'checkbox':
				case 'radio':
				case 'list':
					if ( !empty( $field['choices'] ) ) {
                        foreach ( $field['choices'] as $index => $choice ) {
                            $string_name = "{$form_st_id}_" . $this->_sanitize_string_name( $field['type'] . '-' . $field['id'] . '-choice-' . $choice['text'], $form );
                            $translation = icl_t( $st_context, $string_name, $choice['text'] );
                            $form['fields'][$id]['choices'][$index]['text'] = $translation;
                        }
                    }
                    break;
				case 'product':
                case 'shipping':
                case 'option':
                    // Price fields can be single or multi-option field type
                    if ( in_array( $field['inputType'], array( 'singleproduct', 'singleshipping' ) ) && isset( $field['basePrice'] ) ) {
                        $form['fields'][$id]['basePrice'] = icl_t( $st_context, "{$form_st_id}_{$field['type']}-{$field['id']}-basePrice", $field['basePrice'] );
                    } else if ( in_array( $field['inputType'], array( 'select', 'checkbox', 'radio', 'hiddenproduct' ) ) && !empty( $field['choices'] ) ) {
                        foreach ( $field['choices'] as $index => $choice ) {
                            $string_name = "{$form_st_id}_" . $this->_sanitize_string_name( "{$field['type']}-{$field['id']}-choice-{$choice['text']}", $form );
                            $translation = icl_t( $st_context, $string_name, $choice['text'] );
                            $form['fields'][$id]['choices'][$index]['text'] = $translation;
                            if ( isset( $choice['price'] ) ) {
                                $translation = icl_t( $st_context, $string_name . '-price', $choice['price'] );
                                $form['fields'][$id]['choices'][$index]['price'] = $translation;
                            }
                        }
                    }
                    break;

				case 'post_custom_field':
					$form['fields'][$id]['customFieldTemplate'] =
					icl_t($st_context,$form_st_id . '_field-' . $field['id'] . '-customFieldTemplate', $field["customFieldTemplate"]);
					break;
				case 'post_category':
					$form['fields'][$id]['categoryInitialItem'] = icl_t($st_context,$form_st_id.'_field-'.$field['id'].'-categoryInitialItem');
					break;
			}

		}

		if (isset($form['pagination']['pages'])) {
			foreach ($form['pagination']['pages'] as $key => $page_title) {
				$form['pagination']['pages'][$key] =
					icl_t($st_context,$form_st_id.'_page-'.($key+1).'-title',$form['pagination']['pages'][$key]);
			}
			if (isset($form['pagination']['progressbar_completion_text']))
				$form['pagination']['progressbar_completion_text'] =
					icl_t($st_context,$form_st_id.'_progressbar_completion_text',$form['pagination']['progressbar_completion_text']);

		}

		if (isset($form['lastPageButton'])) {
			$form['lastPageButton'] = icl_t($st_context,$form_st_id.'_lastPageButton',$form['lastPageButton']);
		}

		$this->_current_forms[$form_st_id][$current_lang] = $form;

		return $form;
	}

    /**
     * Front-end form rendering.
     * 
     * @global object $sitepress
     * @param array $form
     * @param string $ajax
     * @return array
     */
    function gform_pre_render( $form, $ajax ) {

        global $sitepress;

        $form_st_id = $this->get_string_prefix_id($form);
        $st_context = $this->get_st_context($form['id']);
        // Cache
		$current_lang = $sitepress->get_current_language();
        if ( isset( $this->_current_forms[$form['id']][$current_lang] ) ) {
            return $this->_current_forms[$form['id']][$current_lang];
        }

        $form = $this->populate_translated_values($form, $st_context, $form_st_id);

        // Pagination
        if ( !empty( $form['pagination'] ) ) {
            // Paging Page Names - $form["pagination"]["pages"][i]
            if ( isset( $form['pagination']['pages'] ) && is_array( $form['pagination']['pages'] ) ) {
                foreach ( $form['pagination']['pages'] as $key => $page_title ) {
                    $form['pagination']['pages'][$key] = icl_t( $st_context,
                            "{$form_st_id}_page-" . ( intval( $key ) + 1 ) . '-title',
                            $page_title );
                }
            }
            // Completition text
            if ( !empty( $form['pagination']['progressbar_completion_text'] ) ) {
                $form['pagination']['progressbar_completion_text'] = icl_t( $st_context,
                        "{$form_st_id}_progressbar_completion_text",
                        $form['pagination']['progressbar_completion_text'] );
            }
            // Last page button text
            // TODO not registered at my tests
            if ( !empty( $form['lastPageButton']['text'] ) ) {
                $form['lastPageButton']['text'] = icl_t( $st_context,
                        "{$form_st_id}_lastPageButton",
                        $form['lastPageButton']['text'] );
            }
        }

        // Common field properties
		$keys = $this->_get_field_keys();

        // Filter form fields (array of GF_Field objects)
		foreach ( $form['fields'] as $id => &$field ) {

            // Filter common properties
            foreach ($keys as $key) {
				if ( !empty( $field->{$key} ) && $field->type != 'page' ) {
                    $field->{$key} = icl_t($st_context,
                            "{$form_st_id}_field-{$field->id}-{$key}",
                            $field->{$key});
				}
			}

            // Field specific code
			switch ( $field->type ) {
				case 'html':
                    $field->content = icl_t( $st_context,
                        "{$form_st_id}_field-{$field->id}-content", $field->content );
                    break;
				case 'page':
                    $_bn = "{$form_st_id}_page-" . ( intval( $field->pageNumber ) - 1);
					foreach ( array('text', 'imageUrl') as $key ) {
                        if ( !empty( $field->nextButton[$key] ) ) {
                            $field->nextButton[$key] = icl_t( $st_context,
                                    "{$_bn}-nextButton-{$key}",
                                    $field->nextButton[$key] );
                        }
						if ( isset( $field->previousButton[$key] ) ) {
                            $field->previousButton[$key] = icl_t( $st_context,
                                    "{$_bn}-previousButton-{$key}",
                                    $field->previousButton[$key] );
                        }
					}
					break;
                case 'option':
                    // Price fields can be single or multi-option field type
                    if ( in_array( $field->inputType, array(
                        'singleproduct',
                        'singleshipping'
                        ) ) && $field->basePrice != '' ) {
                        $field->basePrice = icl_t( $st_context,
                                    "{$form_st_id}_{$field->type}-{$field->id}-basePrice",
                                    $field->basePrice );
                    } else if ( in_array( $field->inputType, array(
                        'select',
                        'checkbox',
                        'radio',
                        'hiddenproduct' ) ) && is_array( $field->choices ) ) {
                         $field = $this->handle_multi_input($field, $form, $form_st_id, $st_context);
                    }
                    break;
                case 'select':
                case 'multiselect':
                case 'checkbox':
                case 'list':
                case 'radio':
                    $field = $field = $this->handle_multi_input($field, $form, $form_st_id, $st_context);
                    break;
				case 'post_custom_field':
                    // TODO if multi options - 'choices' (register and translate) 'inputType' => select, etc.
                    if ( $field->customFieldTemplate != '' ) {
                        $field->customFieldTemplate = icl_t( $st_context,
                            "{$form_st_id}_field-{$field->id}-customFieldTemplate",
                            $field->customFieldTemplate );
                    }
					break;
				case 'post_category':
                    // TODO if multi options - 'choices' have static values (register and translate) 'inputType' => select, etc.
                    if ( $field->categoryInitialItem != '' ) {
                        $field->categoryInitialItem = icl_t( $st_context,
                                "{$form_st_id}_field-{$field->id}-categoryInitialItem",
                                $field->categoryInitialItem );
                    }
					break;
			}
		}

		$this->_current_forms[$form_st_id][$current_lang] = $form;

		return $form;
	}

    private function handle_multi_input( $field, $form, $form_st_id, $st_context ) {
        foreach ( $field->choices as $index => $choice ) {
            $string_name                        = "{$form_st_id}_" . $this->_sanitize_string_name (
                    "{$field->type}-{$field->id}-choice-{$choice['text']}",
                    $form
                );
            $field->choices[ $index ][ 'text' ] = icl_t (
                $st_context,
                $string_name,
                $choice[ 'text' ]
            );
            if ( isset( $choice[ 'price' ] ) ) {
                $field->choices[ $index ][ 'price' ] = icl_t (
                    $st_context,
                    "{$string_name}-price",
                    $choice[ 'price' ]
                );
            }
        }

        return $field;
    }

    /**
     * Translate confirmations before submission.
     * @param Array $form
     * @return array
     */
	function gform_pre_submission_filter( $form ) {
        $form = $this->gform_pre_render($form,false);
		if (!empty($form['confirmations'])) {
            $form_st_id = $this->get_string_prefix_id($form);
            $st_context = $this->get_st_context ( $form[ 'id' ] );
            foreach($form['confirmations'] as $key => &$confirmation) {
				switch ($confirmation['type']) {
					case 'message':
						$confirmation['message'] = icl_t($st_context,$form_st_id."_field-confirmation-message_".$confirmation['name'],$confirmation['message']);
					break;
					case 'redirect':
					global $sitepress;
						$confirmation['url'] = str_replace('&amp;lang=','&lang=',$sitepress->convert_url(
							icl_t($st_context,$form_st_id."_confirmation-redirect_".$confirmation['name'], $confirmation['url'])));
						//error_log("Redirecting to ".$confirmation['url']);
					break;
					case 'page':
						$confirmation['pageId'] = icl_object_id(icl_t($st_context,$form_st_id."_confirmation-page_".$confirmation['name'], $confirmation['pageId']),'page',true);
					break;
				}
			}
		}
		global $sitepress;
		$current_lang = $sitepress->get_current_language();
		$this->_current_forms[$current_lang][$form['id']] = $form;
		return $form;
	}

    /**
     * Translate notifications.
     */
	function gform_notification($notification, $form, $lead) {
		if ( $form['notifications'][$notification['id']]['toType'] === 'email'
            || $form['notifications'][$notification['id']]['toType'] === 'field' ) {
            $form_id = $this->get_string_prefix_id($form);
            $st_context = $this->get_st_context ( $form[ 'id' ] );
			$notification['subject'] = icl_t($st_context,$form_id."_notification-subject_".$notification['name'],$notification['subject']);
			$notification['message'] = icl_t($st_context,$form_id."_field-notification-message_".$notification['name'],$notification['message']);
		}

		return $notification;
	}

    /**
     * Translate validation messages.
     * @param String $result
     * @param $value
     * @param Array $form
     * @param Array $field
     * @return String
     */
	function gform_field_validation($result,$value,$form,$field) {
    	if (!$result['is_valid']) {
            $form_id = $this->get_string_prefix_id($form);
            $st_context = $this->get_st_context ( $form[ 'id' ] );
    		$result['message'] = icl_t($st_context,$form_id.'_field-'.$field['id'].'-errorMessage',$result['message']);
    	}

    	return $result;
    }

    /**
     * Get translated form.
     * @param String $form_id
     * @param null|String $lang
     * @return array
     */
    function get_form( $form_id,$lang = null ) {
    	if (!$lang) {
            global $sitepress;
            $lang = $sitepress->get_current_language ();
        }

    	return isset($this->_current_forms[$form_id][$lang])
            ? $this->_current_forms[$form_id][$lang]
            : $this->gform_pre_render(RGFormsModel::get_form_meta($form_id),false);
    }

    /**
     * Get translated field value to use with merge tags.
     * @param $value
     * @param $input_id
     * @param $match
     * @param $field
     * @param $raw_value
     * @return array|string
     */
    function gform_merge_tag_filter($value, $input_id, $match, $field, $raw_value) {

    	if (RGFormsModel::get_input_type($field)!== 'multiselect') {
    		return $value;
    	}

    	$options = array();
    	$value = explode(',',$value);
    	foreach ($value as $selected) {
    		$options[] = GFCommon::selection_display($selected, $field,$currency=NULL,$use_text=true);
    	}

    	return implode(', ',$options);
    }

	/**
	 * Remove translations of deleted field
	 *
	 * @param int $form_id
	 * @param string $field_id
	 */
	function after_delete_field( $form_id, $field_id ) {
		$form_meta = RGFormsModel::get_form_meta($form_id);
		//it is not new form (second parameter) and when deleting field do not need to update status (third parameter)
		$this->update_form_translations($form_meta, false, false);
	}

    /**
     * Undocumented.
     */
	function update_notifications_translations($notification,$form) {

		$this->update_form_translations($form,false);
		return $notification;
	}

    /**
     * Undocumented.
     */
	function update_confirmation_translations($confirmation,$form) {

		$this->update_form_translations($form,false);
		return $confirmation;
	}



    /**
     * Sanitizes icl_string name.
     *
     * @param string $string
     * @param Array $form
     * @return string
     */
    protected function _sanitize_string_name( $string, $form ) {
        $max_length = 128 - strlen("{$form['id']}_");
        $string = sanitize_text_field( $string );
        if ( strlen( $string ) > $max_length ) {
            $string = substr( $string, 0, strrpos( substr( $string, 0, $max_length ), ' ' ) );
        }
        return sanitize_title( $string );
    }
}
