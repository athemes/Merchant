<?php
/**
 * Merchant_Admin_Preview Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Admin_Preview' ) ) {
	class Merchant_Admin_Preview {

		/**
		 * The preview HTML.
		 *
		 * @var string
		 */
		protected $html;

		/**
		 * Manipulators array.
		 *
		 * @var array
		 */
		protected $manipulators;

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			// Add admin preview class to body.
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
		}

		/**
		 * Add admin preview class to body.
		 *
		 * @param string $classes
		 *
		 * @return string
		 */
		public function add_admin_body_class( $classes ) {
			$classes .= ' merchant-has-admin-preview';

			return $classes;
		}

		/**
		 * Set the HTML of the preview box.
		 *
		 * @param string|callable $html The HTML as a string or a callable that should echo the HTML.
		 * @param mixed ...$args Optional args to use in the callback
		 *
		 * @return void
		 */
		public function set_html( $html, ...$args ) {
			if ( is_callable( $html ) ) {
				ob_start();
				call_user_func( $html, ...$args );
				$html = ob_get_clean();
			}

			$this->html = $html;
		}

		/**
		 * This will update the CSS variable of a selector with the value from the settings field.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 * @param string $variable the CSS variable
		 * @param string $unit optional. set unit.
		 *
		 * @return void
		 */
		public function set_css( $setting, $selector, $variable, $unit = '' ) {
			$this->manipulators['css'][] = array(
				'setting'  => $setting,
				'selector' => $selector,
				'variable' => $variable,
				'unit'     => $unit,
			);
		}

		/**
		 * This will update the text of a selector with the value from the settings field.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 * @param array $replacements optional, set string replacements
		 *
		 * @return void
		 */
		public function set_text( $setting, $selector, $replacements = array() ) {
			$manipulator = array(
				'setting'  => $setting,
				'selector' => $selector,
			);

			if ( ! empty( $replacements ) ) {
				$manipulator['replacements'] = $replacements;
			}

			$this->manipulators['text'][] = $manipulator;
		}

		/**
		 * This will update an attribute of an element with the value from the settings field.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 * @param string $attribute the attribute to update.
		 * @param array $replacements optional, set string replacements
		 *
		 * @return void
		 */
		public function set_attribute( $setting, $selector, $attribute, $replacements = array() ) {
			$manipulator = array(
				'setting'   => $setting,
				'selector'  => $selector,
				'attribute' => $attribute,
			);

			if ( ! empty( $replacements ) ) {
				$manipulator['replacements'] = $replacements;
			}

			$this->manipulators['attributes'][] = $manipulator;
		}

		/**
		 * This will update an icon elements based on icon choices.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 *
		 * @return void
		 */
		public function set_icon( $setting, $selector ) {
			$manipulator = array(
				'setting'  => $setting,
				'selector' => $selector,
			);

			$this->manipulators['icons'][] = $manipulator;
		}

		/**
		 * This will update an svg icon elements based on icon choices.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 *
		 * @return void
		 */
		public function set_svg_icon( $setting, $selector ) {
			$manipulator = array(
				'icons_lib' => Merchant_SVG_Icons::$svg_icons,
				'setting'   => $setting,
				'selector'  => $selector,
			);

			$this->manipulators['svg_icons'][] = $manipulator;
		}

		/**
		 * This will update a class of an element with the value from the settings field.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 * @param array $remove optional, classes to remove prior to adding the new one.
		 * @param string $add optional, Toggle a pre-defined class. When this is set it will
		 *                   toggle this class instead of adding the value from the setting.
		 *                   Best used with switchers and checkboxes.
		 *
		 * @return void
		 */
		public function set_class( $setting, $selector, $remove = array(), $add = '' ) {
			$manipulator = array(
				'setting'  => $setting,
				'selector' => $selector,
			);

			if ( ! empty( $remove ) ) {
				$manipulator['remove'] = $remove;
			}

			if ( ! empty( $add ) ) {
				$manipulator['add'] = $add;
			}

			$this->manipulators['classes'][] = $manipulator;
		}

		/**
		 * This will update the repeater content of a selector with the value from the settings field.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 * @param string $repeater_item_selector the repeater item selector
		 * @param string $icon optional, the icon selector. Useful when the $list_item_selector contains
		 *                    icon HTML markup inside his content.
		 *
		 * @return void
		 */
		public function set_repeater_content( $setting, $selector ) {
			$manipulator = array(
				'setting'  => $setting,
				'selector' => $selector,
			);

			$this->manipulators['repeater_content'][] = $manipulator;
		}

		/**
		 * This will update the flexible content items inside a selector with the value from the settings field.
		 *
		 * @param string $setting the setting ID
		 * @param string $selector the selector
		 * @param array $variables the args to point {variables} with settings for each layout
		 *
		 * @example array('layout' => array('{variable}' => 'setting'))
		 *
		 * @return void
		 */
		public function set_flexible_content( $setting, $selector, $variables ) {
			$manipulator = array(
				'setting'   => $setting,
				'selector'  => $selector,
				'variables' => $variables,
			);

			$this->manipulators['flexible_content'][] = $manipulator;
		}

		/**
		 * This will trigger the preview on input change to load the latest changes from
		 * all inputs added to the manipulators array.
		 *
		 * @param string $setting the setting ID
		 *
		 * @return void
		 */
		public function trigger_update( $setting ) {
			$this->manipulators['update'][] = array(
				'setting' => $setting,
			);
		}

		/*****************************************
		 * Helpers
		 *****************************************/

		/***
		 * Get price format to use in replacements.
		 *
		 * @return string
		 */
		public function get_price_format() {
			return str_replace( '0.00', '{string}', wc_price( '0' ) );
		}

		/*****************************************
		 * Static methods
		 *****************************************/

		/**
		 * @return bool
		 */
		public static function has_preview() {
			return isset( self::instance()->html ) && ! empty( self::instance()->html );
		}

		/**
		 * @param $module_id
		 *
		 * @return void
		 */
		public static function set_preview( $module_id ) {
			/**
			 * Hook: merchant_module_preview
			 *
			 * @since 1.2
			 */
			apply_filters( 'merchant_module_preview', self::instance(), $module_id );

			$manipulators    = isset( self::instance()->manipulators ) && ! empty( self::instance()->manipulators )
				? self::instance()->manipulators
				: array();
			$script_variable = 'var merchantPreviewManipulators = ' . wp_json_encode( $manipulators );

			wp_add_inline_script( 'merchant-admin-preview', $script_variable );
		}

		/**
		 * @return string
		 */
		public static function get_html() {
			return self::instance()->html;
		}
	}
}
