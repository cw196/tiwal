<?php

/*
Widget Name: Editor Widget
Description: A widget which allows editing of content using the TinyMCE editor.
Author: SiteOrigin
Author URI: https://siteorigin.com
*/

class SiteOrigin_Widget_Editor_Widget extends SiteOrigin_Widget {

	function __construct() {

		parent::__construct(
			'sow-editor',
			__('SiteOrigin Editor', 'siteorigin-widgets'),
			array(
				'description' => __('A rich-text, text editor.', 'siteorigin-widgets'),
				'help' => 'https://siteorigin.com/widgets-bundle/editor-widget/'
			),
			array(),
			array(
				'title' => array(
					'type' => 'text',
					'label' => __('Title', 'siteorigin-widgets'),
				),
				'text' => array(
					'type' => 'tinymce',
					'rows' => 20
				),
				'autop' => array(
					'type' => 'checkbox',
					'default' => true,
					'label' => __('Automatically add paragraphs', 'siteorigin-widgets'),
				),
			),
			plugin_dir_path(__FILE__)
		);
	}

	function unwpautop($string) {
		$string = str_replace("\n", "", $string);
		$string = str_replace("<p>", "", $string);
		$string = str_replace(array("<br />", "<br>", "<br/>"), "\n", $string);
		$string = str_replace("</p>", "\n\n", $string);

		return $string;
	}

	public function get_template_variables( $instance, $args ) {
		$instance = wp_parse_args(
			$instance,
			array(  'text' => '' )
		);

		$instance['text'] = $this->unwpautop( $instance['text'] );
		$instance['text'] = wp_kses_post( $instance['text'] );
		$instance['text'] = apply_filters( 'widget_text', $instance['text'] );

		// Run some known stuff
		if( !empty($GLOBALS['wp_embed']) ) {
			$instance['text'] = $GLOBALS['wp_embed']->autoembed( $instance['text'] );
		}
		if( $instance['autop'] ) {
			$instance['text'] = wpautop( $instance['text'] );
		}
		$instance['text'] = do_shortcode( $instance['text'] );

		return array(
			'text' => $instance['text'],
		);
	}


	function get_template_name($instance) {
		return 'editor';
	}

	function get_style_name($instance) {
		return '';
	}
}

siteorigin_widget_register( 'editor', __FILE__ );