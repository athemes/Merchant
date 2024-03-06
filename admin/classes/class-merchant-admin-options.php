<?php
/**
 * Merchant_Admin_Options Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Merchant_Admin_Options' ) ) {
	class Merchant_Admin_Options {

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
			// Enqueue hooks.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Ajax callbacks.
			add_action( 'wp_ajax_merchant_create_page_control', array( $this, 'create_page_control_ajax_callback' ) );
			add_action( 'wp_ajax_merchant_admin_options_select_ajax', array( $this, 'select_content_ajax' ) );
			add_action( 'wp_ajax_merchant_admin_products_search', array( $this, 'products_search' ) );

            add_action( 'clean_user_cache', array( $this, 'clear_customer_choices_cache' ), 10, 2 );
		}

		/**
		 * Enqueue scripts.
		 */
		public function enqueue_scripts() {
			if (
				isset( $_GET['page'] ) && 'merchant' === $_GET['page'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				&& isset( $_GET['module'] )  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			) {
				wp_enqueue_script( 'merchant-select2', MERCHANT_URI . 'assets/vendor/select2/select2.full.min.js', array( 'jquery' ), '4.0.13', true );
				wp_enqueue_style( 'merchant-select2', MERCHANT_URI . 'assets/vendor/select2/select2.min.css', array(), '4.0.13', 'all' );

				wp_localize_script( 'merchant-select2', 'merchant_admin_options', array(
					'ajaxurl'                             => admin_url( 'admin-ajax.php' ),
					'ajaxnonce'                           => wp_create_nonce( 'merchant_admin_options' ),
					'product_delete_confirmation_message' => esc_html__( 'Are you sure you want to remove this product?', 'merchant' ),
				) );
			}
		}

		/**
		 * Ajax callbacks.
		 */
		public function create_page_control_ajax_callback() {
			check_ajax_referer( 'customize-create-page-control-nonce', 'nonce' );

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			$page_title      = isset( $_POST['page_title'] ) ? sanitize_text_field( $_POST['page_title'] ) : '';
			$page_meta_key   = isset( $_POST['page_meta_key'] ) ? sanitize_text_field( $_POST['page_meta_key'] ) : '';
			$page_meta_value = isset( $_POST['page_meta_value'] ) ? sanitize_text_field( $_POST['page_meta_value'] ) : '';
			$option_name     = isset( $_POST['option_name'] ) ? sanitize_text_field( $_POST['option_name'] ) : '';

			$meta_input = array();
			if ( $page_meta_key && $page_meta_value ) {
				$meta_input = array(
					$page_meta_key => $page_meta_value,
				);
			}

			$postarr = array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page_title,
				'post_content' => '',
				'meta_input'   => $meta_input,
			);

			$page_id = wp_insert_post( $postarr );

			if ( ! is_wp_error( $page_id ) ) {
				if ( $option_name ) {
					update_option( $option_name, $page_id );
				}

				wp_send_json( array(
					'status'  => 'success',
					'page_id' => $page_id,
				) );
			} else {
				wp_send_json( array(
					'status' => 'error',
				) );
			}
		}

		/**
		 * Select content ajax callback.
		 *
		 */
		public function select_content_ajax() {
			$term   = ( isset( $_GET['term'] ) ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';
			$nonce  = ( isset( $_GET['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
			$source = ( isset( $_GET['source'] ) ) ? sanitize_text_field( wp_unslash( $_GET['source'] ) ) : '';

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( ! empty( $term ) && ! empty( $source ) && ! empty( $nonce ) && wp_verify_nonce( $nonce, 'merchant_admin_options' ) ) {
				$options = array();

				switch ( $source ) {
					case 'post':
					case 'product':
						$query = new WP_Query( array(
							's'              => $term,
							'post_type'      => $source,
							'post_status'    => 'publish',
							'posts_per_page' => 25,
							'order'          => 'DESC',
						) );

						if ( ! empty( $query->posts ) ) {
							foreach ( $query->posts as $post ) {
								$options[] = array(
									'id'   => $post->ID,
									'text' => $post->post_title,
								);
							}
						}

						break;
				}

				wp_send_json_success( $options );
			} else {
				wp_send_json_error();
			}
		}

		/**
		 * Get option.
		 */
		public static function get( $module, $setting, $default_val ) {
			$options = get_option( 'merchant', array() );

			$value = $default_val;

			if ( isset( $options[ $module ][ $setting ] ) ) {
				$value = $options[ $module ][ $setting ];
			}

			/**
			 * Hook: merchant_get_option filter.
			 * Fires after getting module option.
			 *
			 * @param mixed  $value       Option value.
			 * @param string $module      Module ID.
			 * @param string $setting     Setting ID.
			 * @param mixed  $default_val Default value.
             *
             * @since 1.9.3
			 */
			return apply_filters( 'merchant_get_option', $value, $module, $setting, $default_val );
		}

        /**
         * Set option.
         */
		public static function set( $module, $setting, $value ) {
			$options = get_option( 'merchant', array() );
			$options[ $module ][ $setting ] = $value;
			update_option( 'merchant', $options );
		}

        /**
         * Delete option.
         */
        public static function delete( $module, $setting ) {
            $options = get_option( 'merchant', array() );
            unset( $options[ $module ][ $setting ] );
            update_option( 'merchant', $options );
        }

		/**
		 * Get all options.
		 */
		public static function get_all( $module ) {
			$options = get_option( 'merchant', array() );
			$value   = array();

			if ( isset( $options[ $module ] ) ) {
				$value = $options[ $module ];
			}

			return $value;
		}

		/**
		 * Create options.
		 */
		public static function create( $settings ) {
			$module_id = ( isset( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			/**
			 * Hook: merchant_module_settings
			 *
			 * @since 1.0
			 */
			$settings = apply_filters( 'merchant_module_settings', $settings );

			self::save_options( $settings );

			$options = get_option( 'merchant', array() );

			?>
            <div class="merchant-module-page-settings">
                <div class="merchant-module-page-setting-box">
					<?php
					if ( ! empty( $settings['title'] ) ) : ?>
                        <div class="merchant-module-page-setting-title">
							<?php
							echo esc_html( $settings['title'] ); ?>
							<?php
							if ( ! empty( $settings['subtitle'] ) ) : ?>
                                <div class="merchant-module-page-setting-subtitle"><?php
									echo esc_html( $settings['subtitle'] ); ?></div>
							<?php
							endif; ?>
                        </div>
					<?php
					endif; ?>
                    <div class="merchant-module-page-setting-fields">
						<?php

						if ( ! empty( $settings['fields'] ) ) {
							foreach ( $settings['fields'] as $field ) {
								$value = null;

								if ( isset( $field['default'] ) ) {
									$value = $field['default'];
								}

								if ( isset( $field['id'] ) && isset( $options[ $settings['module'] ] ) && isset( $options[ $settings['module'] ][ $field['id'] ] ) ) {
									$value = $options[ $settings['module'] ][ $field['id'] ];
								}

								$module_info = Merchant_Admin_Modules::get_module_info( $settings['module'] );
								if ( ( $module_info && $module_info['pro'] ) && ! defined( 'MERCHANT_PRO_DIR' ) ) {
									self::disabled_field( $field, $value );
								} else {
									self::field( $field, $value, $module_id );
								}
							}
						}

						?>
                    </div>
                </div>
            </div>
			<?php
		}

		/**
		 * Save options.
		 */
		public static function save_options( $settings ) {
			$save  = ( isset( $_POST['merchant_save'] ) ) ? sanitize_text_field( wp_unslash( $_POST['merchant_save'] ) ) : '';
			$reset = ( isset( $_POST['merchant_reset'] ) ) ? sanitize_text_field( wp_unslash( $_POST['merchant_reset'] ) ) : '';
			$nonce = ( isset( $_POST['merchant_nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['merchant_nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'merchant_nonce' ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$options = get_option( 'merchant', array() );

			if ( ! empty( $save ) ) {
				if ( ! empty( $settings['fields'] ) ) {
					foreach ( $settings['fields'] as $field ) {
						if ( ! isset( $field['id'] ) ) {
							continue;
						}

						$value = null;

						if ( isset( $_POST['merchant'] ) && isset( $_POST['merchant'][ $field['id'] ] ) ) {
							if ( 'textarea_code' === $field['type'] ) {
								$value = wp_kses( $_POST['merchant'][ $field['id'] ], merchant_kses_allowed_tags_for_code_snippets() );
							} elseif ( 'textarea_multiline' === $field['type'] ) {
								$value = sanitize_textarea_field( $_POST['merchant'][ $field['id'] ] );
							} elseif ( is_array( $_POST['merchant'][ $field['id'] ] ) ) {
								$value = array_filter( map_deep( wp_unslash( $_POST['merchant'][ $field['id'] ] ), 'sanitize_text_field' ) );
							} else {
								$value = sanitize_text_field( wp_unslash( $_POST['merchant'][ $field['id'] ] ) );
							}
						}


						$options[ $settings['module'] ][ $field['id'] ] = self::sanitize( $field, $value );
					}
				}
			} elseif ( ! empty( $reset ) ) {
				$options[ $settings['module'] ] = array();
			}

			update_option( 'merchant', $options );

			/**
			 * Hook: merchant_options_saved, fired after saving module options.
			 *
			 * @param string $module  module ID.
			 * @param array  $options module options.
			 *
			 * @since 1.9.3
			 */
			do_action( 'merchant_options_saved', $settings['module'], $options[ $settings['module'] ] );

			/**
			 * Hook: merchant_options_saved, fired after saving specific module options.
			 *
			 * @param array $options module options.
			 *
			 * @since 1.9.3
			 */
			do_action( "merchant_options_saved_{$settings['module']}", $options[ $settings['module'] ] );
		}

		/**
		 * Sanitize options.
		 */
		public static function sanitize( $field, $value ) {
			// Callback for custom sanitize methods.
			if ( ! empty( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
				return call_user_func( $field['sanitize'], $value );
			}

			switch ( $field['type'] ) {
				case 'text':
				case 'color':
				case 'number':
				case 'select_size_chart':
					$value = sanitize_text_field( $value );
					break;

				case 'textarea':
					$value = sanitize_textarea_field( $value );
					break;

				case 'textarea_code':
					$value = $value;
					break;

				case 'checkbox_multiple':
					if ( is_array( $value ) && ! empty( $value ) ) {
						$value = array_filter( array_map( 'sanitize_text_field', $value ) );
					} else {
						$value = array();
					}
					break;

				case 'checkbox':
				case 'switcher':
					$value = ( '1' === $value ) ? 1 : 0;
					break;

				case 'range':
				case 'number':
					$value = absint( $value );
					break;
				case 'select_ajax':
					if ( is_array( $value ) && ! empty( $value ) ) {
						$value = array_filter( array_map( 'sanitize_text_field', $value ) );
					} elseif ( is_string( $value ) ) {
						$value = sanitize_text_field( $value );
					} else {
						$value = isset( $field['multiple'] ) && $field['multiple'] === false ? '' : array();
					}
					break;
				case 'radio':
				case 'radio_alt':
				case 'select':
				case 'choices':
				case 'buttons':
				case 'buttons_alt':
					$value = ( in_array( $value, array_keys( $field['options'] ), true ) ) ? sanitize_key( $value ) : '';
					break;

				case 'hook_select':
					$value = is_array( $value ) ? $value : array();

					foreach ( $value as $key => $val ) {
						if ( $key === 'hook_name' ) {
							$value[ $key ] = in_array( $val, array_keys( $field['options'] ), true ) ? sanitize_key( $val ) : '';
						} else {
							$value[ $key ] = (int) $val;
						}
					}

					break;

				case 'code-editor':
					$value = wp_kses_post( $value );
					break;

				case 'sortable':
					$values = json_decode( $value );
					$value  = array();

					foreach ( $values as $val ) {
						if ( in_array( $val, array_keys( $field['options'] ), true ) ) {
							$value[] = sanitize_key( $val );
						}
					}
					break;
				case 'dimensions':
					$values = is_array( $value ) ? $value : array();

					foreach ( $values as $key => $val ) {
						if ( $key === 'unit' ) {
							$value[ $key ] = sanitize_key( $value[ $key ] );
						} else {
							$value[ $key ] = absint( $value[ $key ] );
						}
					}
					break;
				case 'responsive_dimensions':
					$values = is_array( $value ) ? $value : array();

					foreach ( $values as $device => $device_fields ) {
						foreach ( $device_fields as $device_field => $device_field_val ) {
							if ( $device_field === 'unit' ) {
								$value[ $device ][ $device_field ] = sanitize_key( $device_field_val );
							} else {
								$value[ $device ][ $device_field ] = absint( $device_field_val );
							}
						}
					}
					break;

				case 'sortable_repeater':
					$values = json_decode( $value );
					$value  = array_map( 'sanitize_text_field', $values );
					break;
				case 'flexible_content':
					$value = ! is_array( $value ) || empty( $value ) ? array() : $value;

					$value = array_map( function ( $sub_fields ) use ( $field ) {
						foreach ( $sub_fields as $sub_field => $value ) {
							if ( $sub_field === 'layout' ) {
								$sub_fields[ $sub_field ] = sanitize_text_field( $value );
							} else {
								$layout_field = array_filter( $field['layouts'][ $sub_fields['layout'] ]['fields'], function ( $layout_field ) use ( $sub_field ) {
									return $layout_field['id'] === $sub_field;
								} );


								$sub_fields[ $sub_field ] = self::sanitize( reset( $layout_field ), $value );
							}
						}

						return $sub_fields;
					}, $value );

					break;
			}

			return $value;
		}

		/**
		 * Field
		 */
		public static function field( $settings, $value, $module_id = '') {
			if ( ! empty( $settings['type'] ) ) {
				$type = $settings['type'];

				$id        = ( ! empty( $settings['id'] ) ) ? $settings['id'] : '';
				$class     = ( ! empty( $settings['class'] ) ) ? ' ' . $settings['class'] : '';
				$condition = ( ! empty( $settings['condition'] ) ) ? $settings['condition'] : array();
				$default   = ( ! empty( $settings['default'] ) ) ? $settings['default'] : null;

				if ( ! $value && 0 !== $value ) {
					if ( $type === 'checkbox_multiple' ) {
						$value = array();
					} else {
						$value = $default;
					}
				}

				$wrapper_classes = array( 'merchant-module-page-setting-field' );
				$wrapper_classes[] = 'merchant-module-page-setting-field-' . $type;

				if ( ! empty( $class ) ) {
					$wrapper_classes[] = $class;
				}

				/**
				 * Hook 'merchant_admin_module_field_wrapper_classes'
				 * 
				 * @since 1.9.3
				 */
				$wrapper_classes = apply_filters( 'merchant_admin_module_field_wrapper_classes', $wrapper_classes, $settings, $value, $module_id );

				echo '<div class="'. esc_attr( implode( ' ', $wrapper_classes ) ) .'" data-id="'
					. esc_attr( $id ) . '" data-type="' . esc_attr( $type ) . '" data-condition="' . esc_attr( wp_json_encode( $condition ) ) . '">';
				if ( ! empty( $settings['title'] ) ) {
					printf( '<div class="merchant-module-page-setting-field-title">%s</div>', esc_html( $settings['title'] ) );
				}

				echo '<div class="merchant-module-page-setting-field-inner merchant-field-' . esc_attr( $id ) . '">';
				if ( method_exists( 'Merchant_Admin_Options', $type ) ) {
					call_user_func( array( 'Merchant_Admin_Options', $type ), $settings, $value, $module_id );
				} else {
					esc_html_e( 'Field not found!', 'merchant' );
				}
				echo '</div>';

				$desc = ( ! empty( $settings['desc'] ) ) ? $settings['desc'] : '';

				/**
				 * Hook 'merchant_admin_module_field_description'
				 * 
				 * @since 1.9.3
				 */
				$desc = apply_filters( 'merchant_admin_module_field_description', $desc, $settings, $value, $module_id );

				if ( ! empty( $desc ) ) {
					printf( '<div class="merchant-module-page-setting-field-desc">%s</div>', wp_kses_post( $desc ) );
				}

				echo '</div>';
			}
		}

		/**
		 * Disabled field
		 *
		 * @param array $settings
		 * @param mixed $value
		 *
		 * @return void
		 */
		public static function disabled_field( $settings, $value, $module_id = '') {
			static::replace_field(
				$settings,
				$value,
				array(
					'<input ',
					'<select ',
					'merchant-module-page-setting-field-inner',
				),
				array(
					'<input disabled ',
					'<select disabled ',
					'merchant-module-page-setting-field-inner disabled',
				),
				$module_id
			);
		}

		/**
		 * Modified field.
		 *
		 * @param $settings
		 * @param $value
		 * @param $search
		 * @param $replace
		 *
		 * @return void
		 */
		public static function replace_field( $settings, $value, $search, $replace, $module_id = '') {
			ob_start();
			self::field( $settings, $value, $module_id );
			$field = ob_get_clean();

			echo wp_kses( str_replace( $search, $replace, $field ), merchant_kses_allowed_tags( array( 'all' ) ) );
		}

		/**
		 * Field: Text
		 */
		public static function text( $settings, $value, $module_id = '' ) {
			?>
            <input type="text" name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" value="<?php
			echo esc_attr( $value ); ?>"/>
			<?php
		}

		/**
		 * Field: Text (readonly)
		 */
		public static function text_readonly( $settings, $value, $module_id = '' ) {
			?>
            <input type="text" value="<?php
			echo esc_attr( $value ); ?>" readonly/>
			<?php
		}

		/**
		 * Field: Number
		 */
		public static function number( $settings, $value, $module_id = '' ) {
			?>
            <input type="number" name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" value="<?php
			echo esc_attr( $value ); ?>"/>
			<?php
		}

		/**
		 * Field: Textarea
		 */
		public static function textarea( $settings, $value, $module_id = '' ) {
			$value = ( $value ) ? $value : '';
			?>
            <textarea name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]"><?php
				echo wp_kses_post( $value ); ?></textarea>
			<?php
		}

		/**
		 * Field: Textarea
		 */
		public static function textarea_multiline( $settings, $value, $module_id = '' ) {
			$value = ( $value ) ? $value : '';
			?>
            <textarea name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]"><?php
				echo wp_kses_post( $value ); ?></textarea>
			<?php
		}

		/**
		 * Field: Textarea Code Snippet.
		 */
		public static function textarea_code( $settings, $value, $module_id = '' ) {
			$value = ( $value ) ? $value : '';
			?>
            <textarea name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]"><?php
				echo wp_kses( $value, merchant_kses_allowed_tags_for_code_snippets() ); ?></textarea>
			<?php
		}

		/**
		 * Field: Checkbox
		 */
		public static function checkbox( $settings, $value, $module_id = '' ) {
			?>
            <div>
                <label>
                    <input type="checkbox" name="merchant[<?php
					echo esc_attr( $settings['id'] ); ?>]" value="1" <?php
					checked( $value, 1, true ); ?> />
					<?php
					if ( ! empty( $settings['label'] ) ) : ?>
                        <span><?php
							echo esc_html( $settings['label'] ); ?></span>
					<?php
					endif; ?>
                </label>
            </div>
			<?php
		}

		/**
		 * Field: Checkbox multiple
		 */
		public static function checkbox_multiple( $settings, $value ) {
			if ( ! empty( $settings['options'] ) ) : ?>
				<?php
				foreach ( $settings['options'] as $key => $option ) : ?>
                    <label>
                        <input 
							type="checkbox" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>][]" 
							value="<?php echo esc_attr( $key ); ?>" 
							<?php checked( in_array( $key, $value, true ), true ); ?>
						/>
                        <span><?php echo esc_html( $option ); ?></span>
                    </label>
				<?php
				endforeach; ?>
			<?php
			endif; 
		}

		/**
		 * Field: Switcher
		 */
		public static function switcher( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-toggle-switch">
                <input type="checkbox" id="<?php
				echo esc_attr( $settings['id'] ); ?>" name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]" value="1" <?php
				checked( $value, 1, true ); ?>
                        class="toggle-switch-checkbox"/>
                <label class="toggle-switch-label" for="<?php
				echo esc_attr( $settings['id'] ); ?>">
                    <span class="toggle-switch-inner"></span>
                    <span class="toggle-switch-switch"></span>
                </label>
				<?php
				if ( ! empty( $settings['label'] ) ) : ?>
                    <span><?php
						echo esc_html( $settings['label'] ); ?></span>
				<?php
				endif; ?>
            </div>
			<?php
		}

		/**
		 * Field: Radio
		 */
		public static function radio( $settings, $value, $module_id = '' ) {
			?>
			<?php
			if ( ! empty( $settings['options'] ) ) : ?>
				<?php
				foreach ( $settings['options'] as $key => $option ) : ?>
                    <label>
                        <input type="radio" name="merchant[<?php
						echo esc_attr( $settings['id'] ); ?>]" value="<?php
						echo esc_attr( $key ); ?>" <?php
						checked( $value, $key, true ); ?>/>
                        <span><?php
							echo esc_html( $option ); ?></span>
                    </label>
				<?php
				endforeach; ?>
			<?php
			endif; ?>
			<?php
		}

		/**
		 * Field: Radio Alt
		 */
		public static function radio_alt( $settings, $value, $module_id = '' ) {
			?>
			<?php
			if ( ! empty( $settings['options'] ) ) : ?>
				<?php
				foreach ( $settings['options'] as $key => $option ) : ?>
                    <div>
                        <label>
                            <input type="radio" name="merchant[<?php
							echo esc_attr( $settings['id'] ); ?>]" value="<?php
							echo esc_attr( $key ); ?>" <?php
							checked( $value, $key, true ); ?>/>
                            <span><?php
								echo esc_html( $option['title'] ); ?></span>
                        </label>
                        <p><?php
							echo esc_html( $option['desc'] ); ?></p>
                    </div>
				<?php
				endforeach; ?>
			<?php
			endif; ?>
			<?php
		}

		/**
		 * Field: Choices
		 */
		public static function choices( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-choices merchant-choices-<?php
			echo esc_attr( $settings['id'] ) ?>">
				<?php
				if ( ! empty( $settings['options'] ) ) : ?>
					<?php
					foreach ( $settings['options'] as $key => $option ) : ?>
                        <label>
                            <input type="radio" name="merchant[<?php
							echo esc_attr( $settings['id'] ); ?>]" value="<?php
							echo esc_attr( $key ); ?>" <?php
							checked( $value, $key, true ); ?>/>
							<?php
							if ( ! empty( $option['svg'] ) ) : ?>
                                <span class="merchant-svg">
									<?php
									echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $option['svg'] ), merchant_kses_allowed_tags( array(), false ) ); ?>

									<?php
									if ( ! empty( $option['label'] ) ) : ?>
                                        <span class="merchant-tooltip"><?php
											echo esc_html( $option['label'] ); ?></span>
									<?php
									endif; ?>
								</span>
							<?php
							else : ?>
                                <figure>
									<?php
									if ( ! empty( $option['image'] ) ) : ?>
                                        <img src="<?php
										echo esc_url( sprintf( $option['image'], MERCHANT_URI . 'assets/images' ) ); ?>"/>
									<?php
									else : ?>
                                        <img src="<?php
										echo esc_url( sprintf( $option, MERCHANT_URI . 'assets/images' ) ); ?>"/>
									<?php
									endif; ?>
									<?php
									if ( ! empty( $option['label'] ) ) : ?>
                                        <span class="merchant-tooltip"><?php
											echo esc_html( $option['label'] ); ?></span>
									<?php
									endif; ?>
                                </figure>
							<?php
							endif; ?>
                        </label>
					<?php
					endforeach; ?>
				<?php
				endif; ?>
            </div>
			<?php
		}

		/**
		 * Field: Select
		 */
		public static function select( $settings, $value, $module_id = '' ) {
			?>
			<?php
			if ( ! empty( $settings['options'] ) ) : ?>
                <select name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]">
					<?php
					foreach ( $settings['options'] as $key => $option ) : ?>
                        <option value="<?php
						echo esc_attr( $key ); ?>" <?php
						selected( $value, $key, true ); ?>><?php
							echo esc_html( $option ); ?></option>
					<?php
					endforeach; ?>
                </select>
			<?php
			endif; ?>
			<?php
		}

		/**
		 * Field: Hook Select
		 */
		public static function hook_select( $settings, $value, $module_id = '' ) {
			$hook_name     = isset( $value['hook_name'] ) ? $value['hook_name'] : '';
			$hook_priority = isset( $value['hook_priority'] ) ? $value['hook_priority'] : '';

			?>
			<div class="merchant-module-page-setting-field-hook_select-wrapper">
				<div class="merchant-module-page-setting-field-select">
					<select name="merchant[<?php
					echo esc_attr( $settings['id'] ); ?>][hook_name]">
						<?php
						if ( ! empty( $settings['options'] ) ) : ?>
							<?php
							foreach ( $settings['options'] as $key => $option ) : ?>
								<option value="<?php
								echo esc_attr( $key ); ?>" <?php
								selected( $hook_name, $key, true ); ?>><?php
									echo esc_html( $option ); ?></option>
							<?php
							endforeach; ?>
						<?php
						endif; ?>
					</select>
				</div>
				<?php

				if ( isset( $settings['order'] ) && true === $settings['order'] ) {
					?>
					<div class="merchant-module-page-setting-field-number">
						<input type="number" name="merchant[<?php
						echo esc_attr( $settings['id'] ); ?>][hook_priority]" value="<?php
						echo esc_attr( $hook_priority ); ?>"/>
					</div>
					<?php
				} ?>
			</div>

			<?php
		}

		/**
		 * Field: Select ajax
		 *
		 * @param $settings
		 * @param $value
		 *
		 * @return void
		 */
		public static function select_ajax( $settings, $value, $module_id = '' ) {
			$settings = wp_parse_args( $settings, array(
				'source' => 'post',
			) );

			$ids = ( is_array( $value ) && ! empty( $value ) ) ? $value : (array) $value;

			if ( isset( $settings['multiple'] ) ) {
				$multiple = $settings['multiple'] === true ? 'multiple' : '';
			} else {
				$multiple = 'multiple';
			}

            $placeholder = $settings['placeholder'] ?? '';
			?>
            <select
                name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]<?php echo esc_attr( $multiple ? '[]' : '' ); ?>"
                <?php echo esc_attr( $multiple ); ?>
                data-source="<?php echo esc_attr( $settings['source'] ); ?>"
                data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
				<?php
				if ( ! empty( $ids ) ) {
					foreach ( $ids as $id ) {
						switch ( $settings['source'] ) {
							case 'post':
							case 'product':
								$post = get_post( $id );

								if ( ! empty( $post ) ) {
									echo '<option value="' . esc_attr( $post->ID ) . '" selected>' . esc_html( $post->post_title ) . '</option>';
								}
								break;
							case 'options':
						}
					}
				}
				if ( $settings['source'] === 'options' ) {
					$value = ! empty( $value ) ? $value : '';

					foreach ( $settings['options'] as $option ) {
						if ( isset( $option['options'] ) ) {
							echo '<optgroup label="' . esc_attr( $option['text'] ) . '">';

							foreach ( $option['options'] as $child_option ) {
								$selected_value = is_array( $value ) ? in_array( $child_option['id'], $value, true ) : $child_option['id'];
								echo '<option value="' . esc_attr( $child_option['id'] ) . '" ' . selected( $selected_value, is_array( $value ) ? true : $value ) . '>' . esc_html( $child_option['text'] )
									. '</option>';
							}

							echo '</optgroup>';
						} else {
							$selected_value = is_array( $value ) ? in_array( $option['id'], $value, true ) : $option['id'];
							echo '<option value="' . esc_attr( $option['id'] ) . '" ' . selected( $selected_value, is_array( $value ) ? true : $value ) . '>' . esc_html( $option['text'] ) . '</option>';
						}
					}
				} ?>
            </select>
			<?php
		}

		/**
		 * Field: Products Selector
		 *
		 * @param $settings
		 * @param $value
		 *
		 * @return void
		 */
		public static function products_selector( $settings, $value, $module_id = '' ) {
			if ( ! class_exists( 'WooCommerce' ) ) {
				echo '<p class="merchant-notice">' . esc_html__( 'WooCommerce is not installed or activated.', 'merchant' ) . '</p>';

				return;
			}

			$ids = $value ? explode( ',', $value ) : array();

			if ( isset( $settings['multiple'] ) ) {
				$multiple = $settings['multiple'] === true ? 'multiple' : 'false';
			} else {
				$multiple = 'multiple';
			}
			?>

            <div class="merchant-products-search-container" data-multiple="<?php
			echo esc_attr( $multiple ); ?>">
                <div class="merchant-search-area">
                    <input type="text" name="merchant-search-field" placeholder="<?php
					esc_attr_e( 'Search products', 'merchant' ); ?>" class="merchant-search-field">
                    <span class="merchant-searching"><?php
		                esc_html_e( 'Searching...', 'merchant' ); ?></span>
                    <img src="<?php echo esc_url( MERCHANT_URI . 'assets/images/admin/products-search-icon.svg' ); ?>" class="merchant-search-icon"
                        alt="<?php esc_attr_e( 'Search icon', 'merchant' ); ?>">
                    <div class="merchant-selections-products-preview"></div>
                </div>
                <div class="merchant-selected-products-preview">
                    <ul>
						<?php
						if ( ! empty( $ids ) ) {
							foreach ( $ids as $product_id ) {
								$product = wc_get_product( $product_id );
								if ( $product ) {
									self::product_data_li( $product );
								}
							}
						}
						?>
                    </ul>
                </div>
                <input type="hidden" name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]" class="merchant-selected-products" value="<?php
				echo esc_attr( $value ) ?>">
            </div>
			<?php
		}

		public static function product_data_li( $product, $search = false ) {
			$product_id   = $product->get_id();
			$product_sku  = $product->get_sku();
			$product_name = $product->get_name();
			$edit_link    = get_edit_post_link( $product_id );
			/**
			 * Filter product image.
			 *
			 * @since 1.9.1
			 */
			$product_image = apply_filters(
				'merchant_product_item_product_image',
				'<span class="img">' . $product->get_image( array( 30, 30 ) ) . '</span>',
				$product
			);

			$price = $product->get_price();
			$key   = $product_id . '_' . $product_sku;

			/**
			 * Filter the product bundle item product info.
			 *
			 * @param string     $product_info The product bundle item product info.
			 * @param WC_Product $product      The product object.
			 *
			 * @since 1.9.0
			 */
			$product_info = apply_filters( 'merchant_pro_product_bundle_item_product_info', $product->get_type() . '<br/>#' . $product_id, $product );
			if ( $search ) {
				$remove_btn = '<span class="remove hint--left" aria-label="' . esc_html__( 'Add', 'merchant' ) . '">+</span>';
			} else {
				$remove_btn = '<span class="remove hint--left" aria-label="' . esc_html__( 'Remove', 'merchant' ) . '">×</span>';
			}

			echo '<li class="product-item" data-key="' . esc_attr( $key ) . '" data-name="' . esc_attr( $product_name ) . '" data-sku="'
				. esc_attr( $product_sku ) . '" data-id="' . esc_attr( $product_id ) . '" data-price="' . esc_attr( $price ) . '">'
				. wp_kses( $product_image, array(
					'span' => array(
						'class' => true,
					),
					'img'  => array(
						'src'      => true,
						'alt'      => true,
						'decoding' => true,
						'srcset'   => true,
						'loading'  => true,
						'sizes'    => true,
						'class'    => true,
						'width'    => true,
						'height'   => true,
					),
				) ) . '<span class="data">'
				. '<span class="name">' . esc_html( $product_name ) . '</span><span class="info">' . wp_kses( $product->get_price_html(), array(
					'span' => array(
						'class' => true,
					),
					'del'  => array(
						'aria-hidden' => true,
					),
					'ins'  => array(),
					'bdi'  => array(),

				) ) . '</span> ' . ( $product->is_sold_individually()
					? '<span class="info">' . esc_html__( 'sold individually', 'merchant' ) . '</span> ' : '' ) . '</span>'
				. '<span class="type"><a href="' . esc_url( $edit_link ) . '" target="_blank">' . wp_kses_post( $product_info ) . '</a></span> ' . wp_kses( $remove_btn, array(
					'span' => array(
						'class'      => true,
						'aria-label' => true,
					),
				) );

			echo '</li>';
		}

		public function products_search() {
			check_ajax_referer( 'merchant_admin_options', 'nonce' );

			if ( ! isset( $_POST['keyword'] ) || empty( $_POST['keyword'] ) ) {
				exit();
			}
			$types     = array( 'simple', 'variable' ); // limit search to product types
			$added_ids = isset( $_POST['ids'] ) ? explode( ',', sanitize_text_field( $_POST['ids'] ) ) : array();
			$keyword   = sanitize_text_field( $_POST['keyword'] );
			if ( is_numeric( $keyword ) ) {
				// search by id
				$query_args = array(
					'p'         => absint( $keyword ),
					'post_type' => 'product',
				);
			} else {
				$query_args = array(
					'post_type'      => 'product',
					'post_status'    => array( 'publish', 'private' ),
					's'              => $keyword,
					'posts_per_page' => 10,
				);

				if ( ! empty( $types ) && ! in_array( 'all', $types, true ) ) {
					$product_types = $types;

					if ( in_array( 'variation', $types, true ) ) {
						$product_types[] = 'variable';
					}

					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					$query_args['tax_query'] = array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => $product_types,
						),
					);
				}

				$query_args['post__not_in'] = array_map( 'absint', $added_ids );
			}

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				echo '<ul>';

				while ( $query->have_posts() ) {
					$query->the_post();
					$_product = wc_get_product( get_the_ID() );

					if ( ! $_product ) {
						continue;
					}

					if (
						! $_product->is_type( 'variable' )
						|| in_array( 'variable', $types, true )
						|| in_array( 'all', $types, true )
					) {
						self::product_data_li( $_product, array( 'qty' => 1 ), true );
					}

					if (
						$_product->is_type( 'variable' )
						&& (
							empty( $types )
							|| in_array( 'all', $types, true )
							|| in_array( 'variation', $types, true )
						)
					) {
						// show all children
						$children = $_product->get_children();

						if ( is_array( $children ) && count( $children ) > 0 ) {
							foreach ( $children as $child ) {
								$child_product = wc_get_product( $child );
								/**
								 * @var WC_Product_Variation $child_product
								 */
								if ( ! $this->are_variation_attributes_set( $child_product ) ) {
									// Don't display variations that don't have all attributes set.
									continue;
								}
								self::product_data_li( $child_product, true );
							}
						}
					}
				}

				echo '</ul>';
				wp_reset_postdata();
			} else {
				// translators: %s is the search keyword
				echo wp_kses( '<ul><span>' . sprintf( esc_html__( 'No results found for "%s"', 'merchant' ), $keyword ) . '</span></ul>', array(
					'ul'   => array(),
					'span' => array(),
				) );
			}

			exit;
		}

		/**
		 * Check if all variation attributes are set.
		 *
		 * @param $variation WC_Product_Variation variation product object
		 *
		 * @return bool
		 */
		public function are_variation_attributes_set( $variation ) {
			// Check if the product is a variation and has attributes
			if ( $variation && $variation->is_type( 'variation' ) ) {
				// Get the variation attributes
				$variation_attributes = $variation->get_variation_attributes();

				// Check if all variation attributes are set
				foreach ( $variation_attributes as $attribute => $value ) {
					if ( empty( $value ) ) {
						return false; // At least one attribute is not set
					}
				}

				return true; // All variation attributes are set
			}

			return false; // Not a valid variation product
		}

		/**
		 * Field: Select Size Chart
		 */
		public static function select_size_chart( $settings, $value, $module_id = '' ) {
			$options = array(
				'' => esc_html__( 'Default', 'merchant' ),
			);

			$posts = get_posts( array(
				'post_type'      => 'merchant_size_chart',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
			) );

			if ( ! is_wp_error( $posts ) && ! empty( $posts ) ) {
				foreach ( $posts as $_post ) {
					$options[ $_post->ID ] = $_post->post_title;
				}
			}

			?>
			<?php
			if ( ! empty( $options ) ) : ?>
                <select name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]">
					<?php
					foreach ( $options as $key => $option ) : ?>
                        <option value="<?php
						echo esc_attr( $key ); ?>" <?php
						selected( $value, $key, true ); ?>><?php
							echo esc_html( $option ); ?></option>
					<?php
					endforeach; ?>
                </select>
			<?php
			endif; ?>
			<?php
		}

		/**
		 * Field: Buttons
		 */
		public static function buttons( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-buttons">
				<?php
				if ( ! empty( $settings['options'] ) ) : ?>
					<?php
					foreach ( $settings['options'] as $key => $option ) : ?>
                        <label class="merchant-button-<?php
						echo esc_attr( $key ); ?>"">
                        <input type="radio" name="merchant[<?php
						echo esc_attr( $settings['id'] ); ?>]" value="<?php
						echo esc_attr( $key ); ?>" <?php
						checked( $value, $key, true ); ?>/>
                        <span><?php
							echo esc_html( $option ); ?></span>
                        </label>
					<?php
					endforeach; ?>
				<?php
				endif; ?>
            </div>
			<?php
		}

		/**
		 * Field: Buttons Alt
		 */
		public static function buttons_alt( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-buttons">
				<?php
				if ( ! empty( $settings['options'] ) ) : ?>
					<?php
					foreach ( $settings['options'] as $key => $option ) : ?>
                        <label class="merchant-button-<?php
						echo esc_attr( $key ); ?>">
                            <input type="radio" name="merchant[<?php
							echo esc_attr( $settings['id'] ); ?>]" value="<?php
							echo esc_attr( $key ); ?>" <?php
							checked( $value, $key, true ); ?>/>
                            <span><?php
								echo esc_html( $option ); ?></span>
                        </label>
					<?php
					endforeach; ?>
				<?php
				endif; ?>
            </div>
			<?php
		}

		/**
		 * Field: Range
		 */
		public static function range( $settings, $value, $module_id = '' ) {
			$settings = wp_parse_args( $settings, array(
				'min'  => '',
				'max'  => '',
				'step' => '',
				'unit' => '',
			) );

			?>
            <div class="merchant-range">
                <input type="range" class="merchant-range-input" name="" min="<?php
				echo esc_attr( $settings['min'] ); ?>" max="<?php
				echo esc_attr( $settings['max'] ); ?>"
                        step="<?php
						echo esc_attr( $settings['step'] ); ?>" value="<?php
				echo esc_attr( $value ); ?>"/>
                <input type="number" class="merchant-range-number-input" name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]" min="<?php
				echo esc_attr( $settings['min'] ); ?>"
                        max="<?php
						echo esc_attr( $settings['max'] ); ?>" step="<?php
				echo esc_attr( $settings['step'] ); ?>" value="<?php
				echo esc_attr( $value ); ?>"/>
				<?php
				if ( ! empty( $settings['unit'] ) ) : ?>
                    <span class="merchant-range-unit"><?php
						echo esc_html( $settings['unit'] ); ?></span>
				<?php
				endif; ?>
            </div>
			<?php
		}

		/**
		 * Field: Color
		 */
		public static function color( $settings, $value, $module_id = '' ) {
			$settings = wp_parse_args( $settings, array(
				'default' => '#212121',
			) );

			?>
            <div class="merchant-color">
                <div class="merchant-color-picker" data-default-color="<?php
				echo esc_attr( $settings['default'] ); ?>" style="background-color: <?php
				echo esc_attr( $value ); ?>;"></div>
                <input type="text" class="merchant-color-input" name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]" value="<?php
				echo esc_attr( $value ); ?>"/>
            </div>
			<?php
		}

		/**
		 * Field: Gallery
		 */
		public static function gallery( $settings, $value, $module_id = '' ) {
			$settings = wp_parse_args( $settings, array(
				'label' => esc_html__( 'Select Images', 'merchant' ),
			) );

			$images = ( ! empty( $value ) ) ? explode( ',', $value ) : array();


			echo '<div class="merchant-gallery-images">';

			if ( ! empty( $images ) ) :

				foreach ( $images as $image_id ) :

					$image = wp_get_attachment_image_src( $image_id, 'thumbnail' );

					if ( ! empty( $image ) && ! empty( $image[0] ) ) :

						printf( '<div class="merchant-gallery-image" data-item-id="%s">', esc_attr( $image_id ) );

						echo '<i class="merchant-gallery-remove dashicons dashicons-no-alt"></i>';

						printf( '<img src="%s" />', esc_url( $image[0] ) );

						echo '</div>';

					endif;

				endforeach;

			endif;

			echo '</div>';

			?>
            <a href="#" class="merchant-gallery-button"><?php
				echo esc_html( $settings['label'] ); ?></a>
            <input type="hidden" class="merchant-gallery-input" name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" value="<?php
			echo esc_attr( $value ); ?>"/>
			<?php
		}

		/**
		 * Field: Upload
		 */
		public static function upload( $settings, $value, $module_id = '' ) {
			$settings = wp_parse_args( $settings, array(
				'label' => esc_html__( 'Select Image', 'merchant' ),
			) );

			echo '<div class="merchant-upload-wrapper">';

			if ( ! empty( $value ) ) :

				$image = wp_get_attachment_image_src( $value, 'thumbnail' );

				if ( ! empty( $image ) && ! empty( $image[0] ) ) :

					echo '<div class="merchant-upload-image">';
					echo '<i class="merchant-upload-remove dashicons dashicons-no-alt"></i>';
					printf( '<img src="%s" />', esc_url( $image[0] ) );
					echo '</div>';

				endif;

			endif;

			echo '</div>';

			?>
            <a href="#" class="merchant-upload-button"><?php
				echo esc_html( $settings['label'] ); ?></a>
            <input type="hidden" class="merchant-upload-input" name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" value="<?php
			echo esc_attr( $value ); ?>"/>
			<?php
		}

		/**
		 * Field: Warning
		 */
		public static function warning( $settings ) {
			echo wp_kses_post( $settings['content'] );
		}

		/**
		 * Field: Warning
		 */
		public static function info( $settings ) {
			echo wp_kses_post( $settings['content'] );
		}

		/**
		 * Field: Divider
		 */
		public static function divider() {
		}

		/**
		 * Field: Content
		 */
		public static function content( $settings ) {
			echo wp_kses_post( $settings['content'] );
		}

		/**
		 * Field: Sortable
		 */
		public static function sortable( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-sortable">
                <ul class="merchant-sortable-list ui-sortable">
					<?php
					foreach ( $value as $option_key ) :
						$option_val = $settings['options'][ $option_key ];

						if ( in_array( $option_key, $value, true ) ) :
							?>
                            <li class="merchant-sortable-item" data-value="<?php
							echo esc_attr( $option_key ); ?>">
                                <i class='dashicons dashicons-menu'></i>
                                <i class="dashicons dashicons-visibility visibility"></i>
								<?php
								echo esc_html( $option_val ); ?>
                            </li>
						<?php
						endif; ?>
					<?php
					endforeach; ?>

					<?php
					foreach ( $settings['options'] as $option_key => $option_val ) :
						if ( ! in_array( $option_key, $value, true ) ) :
							$invisible = ! in_array( $option_key, $value, true ) ? ' invisible' : '';

							?>
                            <li class="merchant-sortable-item<?php
							echo esc_attr( $invisible ); ?>" data-value="<?php
							echo esc_attr( $option_key ); ?>">
                                <i class='dashicons dashicons-menu'></i>
                                <i class="dashicons dashicons-visibility visibility"></i>
								<?php
								echo esc_html( $option_val ); ?>
                            </li>
						<?php
						endif; ?>
					<?php
					endforeach; ?>
                </ul>

                <input class="merchant-sortable-input" type="hidden" name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]" value="<?php
				echo esc_attr( wp_json_encode( $value ) ); ?>"/>
            </div>
			<?php
		}

		/**
		 * Field: Sortable Repeater.
		 */
		public static function sortable_repeater( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-sortable-repeater-control<?php
			echo isset( $settings['sorting'] ) && false === $settings['sorting'] ? ' disable-sorting' : ''; ?>">
                <div class="merchant-sortable-repeater sortable regular-field">
                    <div class="repeater">
                        <input type="text" value="" class="repeater-input"/><span class="dashicons dashicons-menu"></span><a class="customize-control-sortable-repeater-delete"
                                                                                                                            href="#"><span
                                    class="dashicons dashicons-no-alt"></span></a>
                    </div>
                </div>
                <button class="button customize-control-sortable-repeater-add" type="button"><?php
					echo esc_html( $settings['button_label'] ); ?></button>
                <input class="merchant-sortable-repeater-input" type="hidden" name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>]" value="<?php
				echo esc_attr( wp_json_encode( $value ) ); ?>"/>
            </div>
			<?php
		}

		public static function wc_coupons( $settings, $value ) {
			$coupons = get_posts( array(
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'asc',
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
			) );
			if ( $coupons ) {
                ?>
                    <div class="merchant-wc-coupon-selector merchant-<?php
				echo esc_attr( $settings['id'] ) ?>">
				<?php
				echo '<select name="merchant[' . esc_attr( $settings['id'] ) . ']">';
				echo '<option value="">' . esc_html__( 'Select a coupon', 'merchant' ) . '</option>';

				foreach ( $coupons as $coupon ) {
					$coupon_code = strtolower( $coupon->post_title );

					echo '<option value="' . esc_attr( $coupon_code ) . '"' . selected( esc_attr( $coupon_code ), $value, false ) . '>';
					echo esc_html( $coupon_code );
					echo '</option>';
				}

				echo '</select>';
				echo '<a href="' . esc_url( admin_url( 'edit.php?post_type=shop_coupon' ) ) . '" target="_blank" style="margin-left: 10px;">' . esc_html__( 'Manage coupons',
						'merchant' ) . '</a>';
				?>
                    </div>
				<?php
			} else {
				echo '<div style="color: red;">';
				echo wp_kses_post(
					sprintf(
					/* Translators: 1. Coupon admin url 2. Link target attribute value */
						__( 'No coupons found! <a href="%1$s" target="%2$s">Create a new coupon</a>', 'merchant' ),
						admin_url( 'post-new.php?post_type=shop_coupon' ),
						'_blank'
					)
				);
				echo '</div>';
				echo '<input type="hidden" name="merchant[' . esc_attr( $settings['id'] ) . ']" value="" />';
			}
		}

		/**
		 * Field: Flexible Content.
		 *
		 * @param array $settings
		 * @param mixed $value
		 *
		 * @return void
		 */
		public static function flexible_content( $settings, $value, $module_id = '' ) {
			$values = ( is_array( $value ) && ! empty( $value ) ) ? $value : array();
			$empty  = empty( $values ) ? 'empty' : '';

			$settings = wp_parse_args( $settings, array(
				'sorting' => false,
				'style'   => 'default',
                'accordion' => false,
			) );

			$classes = array(
				'merchant-flexible-content-control',
				"{$settings['style']}-style",
			);

			if ( $settings['sorting'] === false ) {
				$classes[] = 'disable-sorting';
			}

			if ( $settings['accordion'] === true ) {
				$classes[] = 'has-accordion';
			}
			?>
            <div class="<?php
			echo esc_attr( implode( ' ', $classes ) ); ?>"
                data-id="<?php
				echo esc_attr( $settings['id'] ) ?>">

                <div class="layouts" data-id="<?php
				echo esc_attr( $settings['id'] ) ?>">

					<?php
					foreach ( $settings['layouts'] as $layout_type => $layout ) : ?>

                        <div class="layout" data-type="<?php
						echo esc_attr( $layout_type ) ?>">
                            <div class="layout-header">
                                <div class="layout-count">1</div>
                                <div class="layout-title"<?php
	                            if ( isset( $layout['title-field'] ) && ! empty( $layout['title-field'] ) ) {
		                            echo ' data-title-field="' . esc_attr( $layout['title-field'] ) . '"';
	                            } ?>>
									<?php
									echo esc_html( $layout['title'] ) ?>
                                </div>
                                <div class="layout-actions">
                                    <span class="customize-control-flexible-content-move dashicons dashicons-menu"></span>
                                    <a class="customize-control-flexible-content-delete" href="#">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </a>
	                                <?php
	                                if ( $settings['accordion'] === true ) { ?>
                                        <span class="customize-control-flexible-content-accordion dashicons dashicons-arrow-down"></span>
	                                <?php
	                                } ?>
                                </div>
                            </div>
                            <div class="layout-body">
								<?php
								foreach ( $layout['fields'] as $sub_field ) :
									$classes = array( 'layout-field' );

									if ( isset( $sub_field['classes'] ) ) {
										$classes = array_merge( $classes, $sub_field['classes'] );
									} ?>
                                    <div class="<?php
									echo esc_attr( implode( ' ', $classes ) ); ?>">
										<?php
										static::replace_field( $sub_field,
											'',
											array(
												"name=\"merchant[{$sub_field['id']}]",
												'merchant-module-page-setting-field-upload',
												'merchant-module-page-setting-field-select_ajax',
											),
											array(
												"data-name=\"merchant[{$settings['id']}][0][{$sub_field['id']}]",
												'merchant-module-page-setting-field-upload template',
												'merchant-module-page-setting-field-select_ajax template',
											),
											$module_id ); ?>
                                    </div>
								<?php
								endforeach; ?>
                                <input type="hidden" data-name="merchant[<?php
								echo esc_attr( $settings['id'] ) ?>][0][layout]" value="<?php
								echo esc_attr( $layout_type ) ?>">
                            </div>
                        </div>

					<?php
					endforeach; ?>

                </div>

                <div class="merchant-flexible-content <?php
				echo esc_attr( $empty ); ?> sortable">

					<?php
					foreach ( $values as $option_key => $option ) : ?>
                        <div class="layout" data-type="<?php
						echo esc_attr( $option['layout'] ) ?>">
                            <div class="layout-header">
                                <div class="layout-count"><?php
									echo absint( $option_key + 1 ) ?></div>
                                <div class="layout-title"<?php
                                if ( isset( $layout['title-field'] ) && ! empty( $layout['title-field'] ) ) {
	                                echo ' data-title-field="' . esc_attr( $layout['title-field'] ) . '"';
                                } ?>>
									<?php
									echo isset( $settings['layouts'][ $option['layout'] ]['title'] ) ? esc_html( $settings['layouts'][ $option['layout'] ]['title'] ) : '' ?>
                                </div>
                                <div class="layout-actions">
                                    <span class="customize-control-flexible-content-move dashicons dashicons-menu"></span>
                                    <a class="customize-control-flexible-content-delete" href="#">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </a>
	                                <?php
	                                if ( $settings['accordion'] === true ) { ?>
                                        <span class="customize-control-flexible-content-accordion dashicons dashicons-arrow-down"></span>
		                                <?php
	                                } ?>
                                </div>
                            </div>
                            <div class="layout-body">
								<?php
								foreach ( $settings['layouts'][ $option['layout'] ]['fields'] as $sub_field ) :
									$classes = array( 'layout-field' );
									if ( isset( $sub_field['classes'] ) ) {
										$classes = array_merge( $classes, $sub_field['classes'] );
									} ?>
                                    <div class="<?php
									echo esc_attr( implode( ' ', $classes ) ) ?>">
										<?php
										$value = null;
										if ( isset( $option[ $sub_field['id'] ] ) ) {
											$value = $option[ $sub_field['id'] ];
										} elseif ( isset( $sub_field['default'] ) ) {
											$value = $sub_field['default'];
										}
										static::replace_field( $sub_field,
											$value,
											"name=\"merchant[{$sub_field['id']}]",
											"name=\"merchant[{$settings['id']}][{$option_key}][{$sub_field['id']}]",
											$module_id ); ?>
                                    </div>
								<?php
								endforeach; ?>
                                <input type="hidden" name="merchant[<?php
								echo esc_attr( $settings['id'] ) ?>][<?php
								echo absint( $option_key ) ?>][layout]"
                                        value="<?php
										echo esc_attr( $option['layout'] ) ?>">
                            </div>
                        </div>

					<?php
					endforeach; ?>

                </div>

                <div class="customize-control-flexible-content-add-wrapper">
                    <div class="customize-control-flexible-content-add-list">
						<?php
						foreach ( $settings['layouts'] as $layout_type => $layout ) : ?>
                            <a href="#"
                                class="customize-control-flexible-content-add"
                                data-id="<?php
								echo esc_attr( $settings['id'] ) ?>"
                                data-layout="<?php
								echo esc_attr( $layout_type ) ?>">
								<?php
								echo esc_attr( $layout['title'] ) ?>
                            </a>
						<?php
						endforeach; ?>
                    </div>
                    <button class="button customize-control-flexible-content-add-button" type="button"><?php
						echo esc_html( $settings['button_label'] ); ?></button>
                </div>
            </div>
			<?php
		}

		/**
		 * Field: Create Page.
		 */
		public static function create_page( $settings, $value, $module_id = '' ) {
			$page_id = get_option( $settings['option_name'] );

			echo '<div class="merchant-create-page-control">';

			if ( $page_id && post_exists( get_the_title( $page_id ) ) && 'publish' === get_post_status( $page_id ) ) {
				echo wp_kses_post(
					sprintf(  /* translators: 1: link to edit page */
						__( '<p class="merchant-module-page-setting-field-desc mrc-mt-0">Your page is created!</p><p class="merchant-module-page-setting-field-desc">Click <a href="%1$s" target="_blank">here</a> if you want to edit the page.</p><p class="merchant-module-page-setting-field-desc mrc-mb-0">To display the page in your theme header area, assign the page to the primary menu by clicking <a href="%2$s" target="_blank">here</a></p>',
							'merchant' ),
						get_admin_url() . 'post.php?post=' . $page_id . '&action=edit',
						get_admin_url() . 'nav-menus.php'
					)
				);
			} else {
				echo '<div class="merchant-create-page-control-create-message">';
				echo wp_kses_post(
					'<p class="merchant-module-page-setting-field-desc mrc-mt-0">'.
                        sprintf( /* translators: 1: page name */
						__( 'It looks like you haven\'t created a <strong>%1$s</strong> page yet. Click the below button to create the page.',
							'merchant' ),
						$settings['page_title']
					). '</p>'
				);
				echo '</div>';
				echo '<div class="merchant-create-page-control-success-message" style="display: none;">';
				echo wp_kses_post(
					sprintf( /* translators: 1: link to edit page */
						__( '<p class="merchant-module-page-setting-field-desc">Page created with success!</p><p class="merchant-module-page-setting-field-desc">Click <a href="%1$s" target="_blank">here</a> if you want to edit the page.</p><p class="merchant-module-page-setting-field-desc mrc-mb-0">To display the page in your theme header area, assign the page to the primary menu by clicking <a href="%2$s" target="_blank">here</a></p>',
							'merchant' ),
						get_admin_url() . 'post.php?post=&action=edit',
						get_admin_url() . 'nav-menus.php'
					)
				);
				echo '</div>';
				echo wp_kses_post(
					sprintf( /* translators: 1: page title, 2: page meta key, 3: page meta value, 4: option name, 5: nonce, 6: loading text, 7: success text  */
						__( '<a href="#" class="merchant-create-page-control-button button-tertiary" data-page-title="%2$s" data-page-meta-key="%3$s" data-page-meta-value="%4$s" data-option-name="%5$s" data-nonce="%6$s" data-creating-text="%7$s" data-created-text="%8$s">%1$s</a>',
							'merchant' ),
						__( 'Create Page', 'merchant' ),
						$settings['page_title'],
						$settings['page_meta_key'],
						$settings['page_meta_value'],
						$settings['option_name'],
						wp_create_nonce( 'customize-create-page-control-nonce' ),
						__( 'Creating...', 'merchant' ),
						__( 'Created!', 'merchant' )
					)
				);
			}

			echo '</div>';
		}

		public static function dimensions( $settings, $value, $module_id = '' ) {
			$settings      = wp_parse_args( $settings, array(
				'units'      => array(
					'px'  => 'px',
					'rem' => 'rem',
					'em'  => 'em',
					'vw'  => 'vw',
					'vh'  => 'vh',
					'%'   => '%',
				),
				'dimensions' => array(
					'top',
					'right',
					'bottom',
					'left',
				),
			) );
			$default_value = array(
				'unit' => reset( $settings['units'] ),
			);
			foreach ( $settings['dimensions'] as $dimension ) {
				$default_value[ $dimension ] = 0;
			}
			$value = wp_parse_args( $value, $default_value );
			?>
            <div class="merchant-module-page-settings-unit">
                <select name="merchant[<?php
				echo esc_attr( $settings['id'] ); ?>][unit]">
					<?php
					foreach ( $settings['units'] as $key => $option ) : ?>
                        <option value="<?php
						echo esc_attr( $key ); ?>" <?php
						selected( $value['unit'], $key, true ); ?>><?php
							echo esc_html( $option ); ?></option>
					<?php
					endforeach; ?>
                </select>
            </div>
            <div class="merchant-module-page-settings-dimensions">
				<?php
				foreach ( $settings['dimensions'] as $dimension ) : ?>
                    <div>
                        <input id="merchant-<?php
						echo esc_attr( $settings['id'] ); ?>-<?php
						echo esc_attr( $dimension ) ?>"
                                type="number"
                                name="merchant[<?php
								echo esc_attr( $settings['id'] ); ?>][<?php
								echo esc_attr( $dimension ) ?>]"
                                value="<?php
								echo esc_attr( $value[ $dimension ] ); ?>"/>
                        <label for="merchant-<?php
						echo esc_attr( $settings['id'] ); ?>-<?php
						echo esc_attr( $dimension ) ?>">
							<?php
							echo esc_html( ucfirst( $dimension ) ); ?>
                        </label>
                    </div>
				<?php
				endforeach; ?>
            </div>
			<?php
		}

		public static function responsive_dimensions( $settings, $value, $module_id = '' ) {
			$settings       = wp_parse_args( $settings, array(
				'units'      => array(
					'px'  => 'px',
					'rem' => 'rem',
					'em'  => 'em',
					'vw'  => 'vw',
					'vh'  => 'vh',
					'%'   => '%',
				),
				'dimensions' => array(
					'top',
					'right',
					'bottom',
					'left',
				),
				'devices'    => array(
					'desktop' => 'dashicons-desktop',
					'tablet'  => 'dashicons-tablet',
					'mobile'  => 'dashicons-smartphone',
				),
			) );
			$default_values = array();
			foreach ( $settings['devices'] as $device => $icon ) {
				$default_values[ $device ]['unit'] = reset( $settings['units'] );

				foreach ( $settings['dimensions'] as $dimension ) {
					$default_values[ $device ][ $dimension ] = 0;
				}
			}
			$value = wp_parse_args( $value, $default_values );
			?>
            <div class="merchant-module-page-settings-responsive">
                <ul class="merchant-module-page-settings-devices">
					<?php
					foreach ( $settings['devices'] as $device => $icon )  : ?>
                        <li class="<?php
						echo esc_attr( $device ); ?>">
                            <button type="button" class="preview-<?php
							echo esc_attr( $device ); ?> <?php
							echo $device === key( $settings['devices'] ) ? 'active' : '' ?>"
                                    data-device="<?php
							        echo esc_attr( $device ); ?>">
                                <i class="dashicons <?php
								echo esc_attr( $icon ); ?>"></i>
                            </button>
                        </li>
					<?php
					endforeach; ?>
                </ul>
				<?php
				foreach ( $settings['devices'] as $device => $icon ) : ?>
                    <div class="merchant-module-page-settings-device-container <?php
					echo $device === key( $settings['devices'] ) ? 'active' : '' ?>" data-device="<?php
					echo esc_attr( $device ); ?>">
                        <div class="merchant-module-page-settings-unit">
                            <select name="merchant[<?php
							echo esc_attr( $settings['id'] ); ?>][<?php
							echo esc_attr( $device ) ?>][unit]">
								<?php
								foreach ( $settings['units'] as $key => $option ) : ?>
                                    <option value="<?php
									echo esc_attr( $key ); ?>" <?php
									selected( $value[ esc_attr( $device ) ]['unit'], $key, true ); ?>><?php
										echo esc_html( $option ); ?></option>
								<?php
								endforeach; ?>
                            </select>
                        </div>
                        <div class="merchant-module-page-settings-dimensions">
							<?php
							foreach ( $settings['dimensions'] as $dimension ) : ?>
                                <div>
                                    <input id="merchant-<?php
									echo esc_attr( $settings['id'] ); ?>-<?php
									echo esc_attr( $device ) ?>-<?php
									echo esc_attr( $dimension ) ?>"
                                            type="number"
                                            name="merchant[<?php
											echo esc_attr( $settings['id'] ); ?>][<?php
											echo esc_attr( $device ) ?>][<?php
											echo esc_attr( $dimension ) ?>]"
                                            value="<?php
											echo esc_attr( $value[ $device ][ $dimension ] ); ?>"/>
                                    <label for="merchant-<?php
									echo esc_attr( $settings['id'] ); ?>-<?php
									echo esc_attr( $device ) ?>-<?php
									echo esc_attr( $dimension ) ?>">
										<?php
										echo esc_html( ucfirst( $dimension ) ); ?>
                                    </label>
                                </div>
							<?php
							endforeach; ?>
                        </div>
                    </div>
				<?php
				endforeach; ?>
            </div>

			<?php
		}

		/**
		 * Field: Custom callback.
		 */
		public static function custom_callback( $settings, $value, $module_id = '' ) {
			if ( ! empty( $settings['class_name'] ) && ! empty( $settings['callback_name'] ) ) {
				return call_user_func( array( $settings['class_name'], $settings['callback_name'] ), $settings, $value );
			}

			if ( ! empty( $settings['callback_name'] ) ) {
				return call_user_func( $settings['callback_name'], $settings, $value );
			}

			return false;
		}

		/**
         * Get category choices for select2
         *
		 * @return array
		 */
		public static function get_category_select2_choices() {
			$choices = array();
			$cats    = merchant_get_product_categories();

			if ( is_array( $cats ) && ! empty( $cats ) ) {
				foreach ( $cats as $slug => $name ) {
					$choices[] = array(
						'id'   => esc_attr( $slug ),
						'text' => esc_html( $name ),
					);
				}
			}

			return $choices;
		}

		/**
         * Get User Roles choices for select2
         *
		 * @return array
		 */
		public static function get_user_roles_select2_choices() {
			$choices    = array();
			$user_roles = get_editable_roles();

			if ( ! empty( $user_roles ) ) {
				foreach ( $user_roles as $role_id => $role_data ) {
					$choices[] = array(
						'id'   => $role_id,
						'text' => $role_data['name'],
					);
				}
			}

			return $choices;
		}

		/**
         * Get Customers choices for select2.
         *
		 * @return array
		 */
        public static function get_customers_select2_choices() {
	        $cache_key = 'customers_select2_choices';
	        $choices   = get_transient( $cache_key );

            if ( ! empty( $choices ) && is_array( $choices ) ) {
	            return $choices;
            }

	        // Get users with the 'customer' role
	        $customer_users = get_users(
		        array(
			        'role'   => 'customer',
			        'fields' => array( 'ID', 'display_name' ),
		        )
            );

	        $choices = array();
	        if ( ! empty( $customer_users ) ) {
		        foreach ( $customer_users as $user ) {
			        $choices[] = array(
				        'id'   => $user->ID,
				        'text' => $user->display_name,
			        );
		        }
	        }

	        // Cache the choices with no expiration. Will be cleared using `clear_customer_choices_cache`
	        set_transient( $cache_key, $choices );

	        return $choices;
		}

		/**
         * Clear customers cache when a customer is created/deleted/updated.
         *
		 * @param $user_id
		 * @param $user
		 *
		 * @return void
		 */
		public function clear_customer_choices_cache( $user_id, $user ) {
            $user_roles = $user->roles ?? array();
			if ( in_array( 'customer', $user_roles, true ) ) {
				delete_transient( 'customers_select2_choices' );
            }
        }
	}

	Merchant_Admin_Options::instance();
}
