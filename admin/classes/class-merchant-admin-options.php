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

				wp_enqueue_style('date-picker', MERCHANT_URI . 'assets/vendor/air-datepicker/air-datepicker.css', array(), MERCHANT_VERSION, 'all' );
				wp_enqueue_script('date-picker', MERCHANT_URI . 'assets/vendor/air-datepicker/air-datepicker.js', array( 'jquery' ), MERCHANT_VERSION, true );
				wp_localize_script( 'date-picker', 'merchant_datepicker_locale', array(
					wp_json_encode(
						array(
							'days'        => array(
								esc_html__( 'Sunday', 'merchant' ),
								esc_html__( 'Monday', 'merchant' ),
								esc_html__( 'Tuesday', 'merchant' ),
								esc_html__( 'Wednesday', 'merchant' ),
								esc_html__( 'Thursday', 'merchant' ),
								esc_html__( 'Friday', 'merchant' ),
								esc_html__( 'Saturday', 'merchant' ),
							),
							'daysShort'   => array(
								esc_html__( 'Sun', 'merchant' ),
								esc_html__( 'Mon', 'merchant' ),
								esc_html__( 'Tue', 'merchant' ),
								esc_html__( 'Wed', 'merchant' ),
								esc_html__( 'Thu', 'merchant' ),
								esc_html__( 'Fri', 'merchant' ),
								esc_html__( 'Sat', 'merchant' ),
							),
							'daysMin'     => array(
								esc_html__( 'Su', 'merchant' ),
								esc_html__( 'Mo', 'merchant' ),
								esc_html__( 'Tu', 'merchant' ),
								esc_html__( 'We', 'merchant' ),
								esc_html__( 'Th', 'merchant' ),
								esc_html__( 'Fr', 'merchant' ),
								esc_html__( 'Sa', 'merchant' ),
							),
							'months'      => array(
								esc_html__( 'January', 'merchant' ),
								esc_html__( 'February', 'merchant' ),
								esc_html__( 'March', 'merchant' ),
								esc_html__( 'April', 'merchant' ),
								esc_html__( 'May', 'merchant' ),
								esc_html__( 'June', 'merchant' ),
								esc_html__( 'July', 'merchant' ),
								esc_html__( 'August', 'merchant' ),
								esc_html__( 'September', 'merchant' ),
								esc_html__( 'October', 'merchant' ),
								esc_html__( 'November', 'merchant' ),
								esc_html__( 'December', 'merchant' ),
							),
							'monthsShort' => array(
								esc_html__( 'Jan', 'merchant' ),
								esc_html__( 'Feb', 'merchant' ),
								esc_html__( 'Mar', 'merchant' ),
								esc_html__( 'Apr', 'merchant' ),
								esc_html__( 'May', 'merchant' ),
								esc_html__( 'Jun', 'merchant' ),
								esc_html__( 'Jul', 'merchant' ),
								esc_html__( 'Aug', 'merchant' ),
								esc_html__( 'Sep', 'merchant' ),
								esc_html__( 'Oct', 'merchant' ),
								esc_html__( 'Nov', 'merchant' ),
								esc_html__( 'Dec', 'merchant' ),
							),
							'clear'       => esc_html__( 'Clear', 'merchant' ),
						)
					),
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

                    case 'user':
                        $query = new WP_User_Query( array(
                            'search'         => '*' . $term . '*',
                            'search_columns' => array( 'user_login', 'user_nicename', 'user_email', 'user_url' ),
                            'number'         => 25,
                        ) );

                        if ( ! empty( $query->results ) ) {
                            foreach ( $query->results as $user ) {
                                $options[] = array(
                                    'id'   => $user->ID,
                                    'text' => $user->display_name,
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
			 * @param array  $settings  Module settings.
			 * @param string $module_id Module ID.
			 *
			 * @since 1.0
			 */
			$settings = apply_filters( 'merchant_module_settings', $settings, $module_id );

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

								$current_module = Merchant_Admin_Modules::get_module_info( $settings['module'] );
								$is_pro_module  = ! merchant_is_pro_active() && isset( $current_module['pro'] ) && $current_module['pro'] === true;
								$is_pro_field   = ! merchant_is_pro_active() && isset( $field['pro'] ) && $field['pro'] === true;

								if ( $is_pro_module || $is_pro_field ) {
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

						if ( ! merchant_is_pro_active() && isset( $field['pro'] ) && $field['pro'] === true ) {
                            continue;
						}

						$value = null;

						if ( isset( $_POST['merchant'] ) && isset( $_POST['merchant'][ $field['id'] ] ) ) {
							if ( 'textarea_code' === $field['type'] ) {
								$value = wp_kses( $_POST['merchant'][ $field['id'] ], merchant_kses_allowed_tags_for_code_snippets() );
							} elseif ( 'textarea_multiline' === $field['type'] ) {
								$value = sanitize_textarea_field( $_POST['merchant'][ $field['id'] ] );
							} elseif ( is_array( $_POST['merchant'][ $field['id'] ] ) ) {
								$value = array_filter( map_deep( wp_unslash( $_POST['merchant'][ $field['id'] ] ), 'sanitize_textarea_field' ) );
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

				case 'textarea_multiline':
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
				case 'buttons_content':
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

				$id         = ( ! empty( $settings['id'] ) ) ? $settings['id'] : '';
				$class      = ( ! empty( $settings['class'] ) ) ? ' ' . $settings['class'] : '';
				$condition  = ( ! empty( $settings['condition'] ) ) ? $settings['condition'] : array();
				$conditions = ( ! empty( $settings['conditions'] ) ) ? $settings['conditions'] : ''; //Docs here: https://github.com/athemes/Merchant/pull/133
				$default    = ( ! empty( $settings['default'] ) ) ? $settings['default'] : null;
				$is_upsell  = ! merchant_is_pro_active() && isset( $settings['pro'] ) && $settings['pro'] === true;

				if ( ! $value && ( 0 !== $value && '0' !== $value ) ) {
					if ( $type === 'checkbox_multiple' ) {
						$value = is_array( $value ) ? $value : (array) $default;
					} elseif ( $type === 'text' && ! empty( $module_id ) ) {
						$value = Merchant_Option::get( $module_id, $id );
					} else {
						$value = $default;
					}
				}

				$wrapper_classes   = array( 'merchant-module-page-setting-field' );
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
					. esc_attr( $id ) . '" data-type="' . esc_attr( $type ) . '" data-condition="' . esc_attr( wp_json_encode( $condition ) ) .
                    '" data-conditions="' . ( $conditions ? esc_attr( wp_json_encode( $conditions ) ) : "" ) . '">';
				if ( ! empty( $settings['title'] ) ) {
                    ?>
                    <div class="merchant-module-page-setting-field-title<?php echo esc_attr( $is_upsell ? ' merchant-module-page-setting-field-title__has-upsell' : '' ); ?>">
                        <?php echo esc_html( $settings['title'] ); ?>

                        <?php if ( $is_upsell ) : ?>
                            <a href="<?php echo esc_url( 'https://athemes.com/merchant-upgrade?utm_source=plugin_dashboard&utm_medium=merchant_dashboard&utm_campaign=Merchant' ); ?>" class="merchant-module-pro-upsell" target="_blank">
                                <span class="merchant-pro-badge merchant-pro-tooltip" data-tooltip-message="<?php echo esc_attr__( 'This option is only available on Merchant Pro', 'merchant' ); ?>">
                                    <svg width="28" height="16" viewBox="0 0 28 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.41309 8.90723H5.58203V7.85254H7.41309C7.71257 7.85254 7.95508 7.80371 8.14062 7.70605C8.32943 7.60514 8.46777 7.46842 8.55566 7.2959C8.64355 7.12012 8.6875 6.91992 8.6875 6.69531C8.6875 6.47721 8.64355 6.27376 8.55566 6.08496C8.46777 5.89616 8.32943 5.74316 8.14062 5.62598C7.95508 5.50879 7.71257 5.4502 7.41309 5.4502H6.02148V11.5H4.67871V4.39062H7.41309C7.96647 4.39062 8.43848 4.48991 8.8291 4.68848C9.22298 4.88379 9.52246 5.1556 9.72754 5.50391C9.93587 5.84896 10.04 6.24284 10.04 6.68555C10.04 7.14453 9.93587 7.54004 9.72754 7.87207C9.52246 8.2041 9.22298 8.45964 8.8291 8.63867C8.43848 8.81771 7.96647 8.90723 7.41309 8.90723ZM11.0947 4.39062H13.6777C14.2181 4.39062 14.682 4.47201 15.0693 4.63477C15.4567 4.79753 15.7546 5.03841 15.9629 5.35742C16.1712 5.67643 16.2754 6.06868 16.2754 6.53418C16.2754 6.90202 16.2103 7.22103 16.0801 7.49121C15.9499 7.76139 15.766 7.98763 15.5283 8.16992C15.2939 8.35221 15.0173 8.49544 14.6982 8.59961L14.2783 8.81445H11.998L11.9883 7.75488H13.6924C13.9691 7.75488 14.1986 7.70605 14.3809 7.6084C14.5632 7.51074 14.6999 7.37565 14.791 7.20312C14.8854 7.0306 14.9326 6.83366 14.9326 6.6123C14.9326 6.37467 14.887 6.1696 14.7959 5.99707C14.7048 5.82129 14.5664 5.6862 14.3809 5.5918C14.1953 5.4974 13.9609 5.4502 13.6777 5.4502H12.4375V11.5H11.0947V4.39062ZM15.1084 11.5L13.4629 8.31641L14.8838 8.31152L16.5488 11.4316V11.5H15.1084ZM23.209 7.76465V8.13086C23.209 8.66797 23.1374 9.15137 22.9941 9.58105C22.8509 10.0075 22.6475 10.3704 22.3838 10.6699C22.1201 10.9694 21.806 11.1989 21.4414 11.3584C21.0768 11.5179 20.6715 11.5977 20.2256 11.5977C19.7861 11.5977 19.3825 11.5179 19.0146 11.3584C18.6501 11.1989 18.3343 10.9694 18.0674 10.6699C17.8005 10.3704 17.5938 10.0075 17.4473 9.58105C17.3008 9.15137 17.2275 8.66797 17.2275 8.13086V7.76465C17.2275 7.22428 17.3008 6.74089 17.4473 6.31445C17.5938 5.88802 17.7988 5.52507 18.0625 5.22559C18.3262 4.92285 18.6403 4.69173 19.0049 4.53223C19.3727 4.37272 19.7764 4.29297 20.2158 4.29297C20.6618 4.29297 21.0671 4.37272 21.4316 4.53223C21.7962 4.69173 22.1104 4.92285 22.374 5.22559C22.641 5.52507 22.846 5.88802 22.9893 6.31445C23.1357 6.74089 23.209 7.22428 23.209 7.76465ZM21.8516 8.13086V7.75488C21.8516 7.36751 21.8158 7.02734 21.7441 6.73438C21.6725 6.43815 21.5667 6.18913 21.4268 5.9873C21.2868 5.78548 21.1143 5.63411 20.9092 5.5332C20.7041 5.42904 20.473 5.37695 20.2158 5.37695C19.9554 5.37695 19.7243 5.42904 19.5225 5.5332C19.3239 5.63411 19.1546 5.78548 19.0146 5.9873C18.8747 6.18913 18.7673 6.43815 18.6924 6.73438C18.6208 7.02734 18.585 7.36751 18.585 7.75488V8.13086C18.585 8.51497 18.6208 8.85514 18.6924 9.15137C18.7673 9.44759 18.8747 9.69824 19.0146 9.90332C19.1579 10.1051 19.3304 10.2581 19.5322 10.3623C19.734 10.4665 19.9652 10.5186 20.2256 10.5186C20.486 10.5186 20.7171 10.4665 20.9189 10.3623C21.1208 10.2581 21.29 10.1051 21.4268 9.90332C21.5667 9.69824 21.6725 9.44759 21.7441 9.15137C21.8158 8.85514 21.8516 8.51497 21.8516 8.13086Z" fill="#3858E9"/>
                                        <rect x="0.5" y="1" width="27" height="14" rx="1.5" stroke="#3858E9"/>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php
				}

				echo '<div class="merchant-module-page-setting-field-inner merchant-field-' . esc_attr( $id ) . '">';
				if ( method_exists( 'Merchant_Admin_Options', $type ) ) {
					call_user_func( array( 'Merchant_Admin_Options', $type ), $settings, $value, $module_id );
				} else {
					esc_html_e( 'Field not found!', 'merchant' );
				}
				echo '</div>';

				$hidden_desc = $settings['hidden_desc'] ?? '';

				/**
				 * Hook 'merchant_admin_module_field_hidden_description'
				 *
				 * @since 1.9.3
				 */
				$hidden_desc = apply_filters( 'merchant_admin_module_field_hidden_description', $hidden_desc, $settings, $value, $module_id );

				$desc = ( ! empty( $settings['desc'] ) ) ? $settings['desc'] : '';

				/**
				 * Hook 'merchant_admin_module_field_description'
				 * 
				 * @since 1.9.3
				 */
				$desc = apply_filters( 'merchant_admin_module_field_description', $desc, $settings, $value, $module_id );

				if ( ! empty( $desc ) ) {
					$hidden_desc_html = '';
                    if ( ! empty( $hidden_desc ) ) {
	                    $hidden_desc_html  = '<div class="merchant-module-page-setting-field-hidden-desc-trigger" data-show-text="' . esc_html__( 'Show more', 'merchant' ) . '" data-hidden-text="' . esc_html__( 'Show less', 'merchant' ) . '"><span>' . esc_html__( 'Show more', 'merchant' ) . '</span>';
	                    $hidden_desc_html .= '<img src="' . esc_url( MERCHANT_URI . '/assets/images/arrow-down.svg' ) . '" alt="Merchant" />';
	                    $hidden_desc_html .= '</div>';
                    }

					printf( '<div class="merchant-module-page-setting-field-desc' . esc_attr( $hidden_desc ? ' merchant-module-page-setting-field-desc-has-hidden-desc' : '' ) .'">%s%s</div>', wp_kses_post( $desc ), wp_kses_post( $hidden_desc_html ) );
                }

				if ( ! empty( $hidden_desc ) ) {
					printf( '<div class="merchant-module-page-setting-field-hidden-desc">%s</div>', wp_kses_post( nl2br( $hidden_desc ) ) );
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
			echo esc_attr( $value ); ?>" placeholder="<?php
            echo esc_attr( $settings['placeholder'] ?? '' ); ?>"/>
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
			echo esc_attr( $value ); ?>"<?php
            echo isset( $settings['step'] ) ? ' step="' . esc_attr( $settings['step'] ) . '"' : '';
            echo isset( $settings['max'] ) ? ' max="' . esc_attr( $settings['max'] ) . '"' : '';
            echo isset( $settings['min'] ) ? ' min="' . esc_attr( $settings['min'] ) . '"' : '' ?>
            placeholder="<?php echo esc_attr( $settings['placeholder'] ?? '' ); ?>"/>
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
		 * Field: Image picker
		 */
		public static function image_picker( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-image-picker">
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
							if ( isset( $option['image'] ) ) { ?>
                                <img src="<?php
								echo esc_url( $option['image'] ) ?>" alt="">
								<?php
							} ?>
							<?php
							if ( isset( $option['title'] ) ) { ?>
                                <span class="tool-tip-text"><?php
									echo esc_html( $option['title'] ) ?></span>
								<?php
							} ?>
                        </label>
					<?php
					endforeach; ?>
				<?php
				endif; ?>
            </div>
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
									<?php if ( ! empty( $option['image'] ) ) :
										$title = $option['title'] ?? '';
                                        ?>
                                        <img src="<?php echo esc_url( sprintf( $option['image'], MERCHANT_URI . 'assets/images' ) ); ?>" alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>"/>
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
							case 'user':
                                $user = get_user_by( 'ID', $id );
                                if( $user ) {
                                    echo '<option value="' . esc_attr( $user->ID ) . '" selected>' . esc_html( $user->display_name ) . '</option>';
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
								echo '<option value="' . esc_attr( $child_option['id'] ) . '" ' . selected( $selected_value, is_array( $value ) ? true : $value, false ) . '>' . esc_html( $child_option['text'] ) . '</option>';
							}

							echo '</optgroup>';
						} else {
							$selected_value = is_array( $value ) ? in_array( $option['id'], $value, true ) : $option['id'];
							echo '<option value="' . esc_attr( $option['id'] ) . '" ' . selected( $selected_value, is_array( $value ) ? true : $value, false ) . '>' . esc_html( $option['text'] ) . '</option>';
						}
					}
				} ?>
            </select>
			<?php
		}

		/**
		 * Field: Info Block
		 *
		 * @return void
		 */
		public static function info_block( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-info-block">
                <i class="dashicons dashicons-info"></i>
                <p><?php
					echo ! empty( $settings['description'] ) ? esc_html( $settings['description'] ) : ''; ?><?php
					if ( ! empty( $settings['button_text'] ) && ! empty( $settings['button_link'] ) ) {
						printf( '<a href="%s" target="_blank">%s</a>', esc_url( $settings['button_link'] ), esc_html( $settings['button_text'] ) );
					} ?></p>
            </div>
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

			if ( ! isset( $settings['allowed_types'] ) ) {
				$settings['allowed_types'] = array( 'all' );
			}
			?>

            <div class="merchant-products-search-container" data-multiple="<?php
			echo esc_attr( $multiple ); ?>">
                <div class="merchant-search-area">
                    <input type="text" name="merchant-search-field" data-allowed-types="<?php
                    echo esc_attr( implode( ',', $settings['allowed_types'] ) ) ?>" placeholder="<?php
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

		public static function product_data_li( $product, $search = false, $hierarchy = false ) {
			$product_id   = $product->get_id();
			$product_sku  = $product->get_sku();
			$product_name = $product->get_name();
			$edit_link    = get_edit_post_link( $product_id );
			if ( $product->is_type( 'variation' ) ) {
				$edit_link = get_edit_post_link( $product->get_parent_id() );
			}

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

            $item_class = 'product-item';
            if( $hierarchy && $product->is_type( 'variation' ) ) {
                $item_class .= ' hierarchy-style';
            }

			echo '<li class="' . esc_attr( $item_class ) . '" data-key="' . esc_attr( $key ) . '" data-name="' . esc_attr( $product_name ) . '" data-sku="'
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

			if ( isset( $_POST['product_types'] ) || ! empty( $_POST['product_types'] ) ) {
				$types = explode( ',', sanitize_text_field( $_POST['product_types'] ) );
			}

			$hierarchy = false;
			if (
				in_array( 'all', $types, true )
				|| ( in_array( 'variation', $types, true ) && in_array( 'variable', $types, true ) )
			) {
				$hierarchy = true;
			}

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

            // Filter by category slugs.
			$categories = array_map( 'sanitize_text_field', $_POST['categories'] ?? array() );
            if ( is_array( $categories ) && ! empty( $categories ) ) {
	            $query_args['tax_query'][] = array(
		            'taxonomy' => 'product_cat',
		            'field'    => 'slug',
		            'terms'    => $categories,
		            'operator' => 'IN',
	            );
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
						self::product_data_li( $_product, array( 'qty' => 1 ), true, $hierarchy );
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
								self::product_data_li( $child_product, true, $hierarchy );
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
		 * Field: Buttons Content
		 */
		public static function buttons_content( $settings, $value, $module_id = '' ) {
			?>
            <div class="merchant-buttons-content">
				<?php
				if ( ! empty( $settings['options'] ) ) : ?>
					<?php
					foreach ( $settings['options'] as $key => $option ) : ?>
                        <label class="merchant-button-content-<?php echo esc_attr( $key ); ?>">
                            <input
                                type="radio"
                                name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]"
                                value="<?php echo esc_attr( $key ); ?>"
								<?php checked( $value, $key, true ); ?>
                            />

                            <span class="merchant-buttons-inner-content">
                                <?php if ( $option['icon'] ) : ?>
                                    <img src="<?php echo esc_url( $option['icon'] ); ?>" alt="">
                                <?php endif; ?>
                                <span>
                                    <span><?php echo esc_html( $option['title'] ?? '' ); ?></span>
                                    <span><?php echo esc_html( $option['desc'] ?? '' ); ?></span>
                                </span>
                            </span>
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

			$drag_drop = $settings['drag_drop'] ?? false;
			?>
            <div class="merchant-upload-button-wrapper<?php echo esc_attr( $drag_drop ? ' merchant-upload-button-drag-drop' : '' ); ?>">
                <?php if ( $drag_drop ) : ?>
                    <img src="<?php echo esc_url( esc_url( MERCHANT_URI . '/assets/images/upload-icon.svg' ) ); ?>" alt="<?php echo esc_attr__( 'Upload image', 'merchant' ); ?>"/>
                <?php endif; ?>
                <a href="#" class="merchant-upload-button"><?php echo esc_html( $settings['label'] ?? '' ); ?></a>
            </div>
            <input type="hidden" class="merchant-upload-input" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $value ); ?>"/>
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
         * Field: Date Time.
         *
		 * @param $settings array field settings.
		 * @param $value string field value.
		 * @param $module_id string module id.
		 *
		 * @return void
		 */
        public static function date_time( $settings, $value, $module_id = '' ) {
	        // All options are documented here: https://air-datepicker.com/docs
	        $options = array(
		        'dateFormat' => 'MM-dd-yyyy',
		        'timepicker' => true,
		        'timeFormat' => 'hh:mm AA',
		        'minDate'    => 'today',
		        'timeZone'   => wp_timezone_string(),
	        );
	        if ( isset( $settings['options'] ) ) {
		        $settings['options'] = wp_parse_args( $settings['options'], $options );
	        } else {
		        $settings['options'] = $options;
	        }
	        ?>
            <div class="merchant-datetime-field" data-options="<?php echo esc_attr( wp_json_encode( $settings['options'] ) ); ?>">
                <input type="text" name="merchant[<?php
		        echo esc_attr( $settings['id'] ); ?>]" value="<?php
		        echo esc_attr( $value ); ?>" placeholder="<?php
		        echo isset( $settings['placeholder'] ) ? esc_attr( $settings['placeholder'] ) : ''; ?>"/>
            </div>
	        <?php
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

			$has_sorting = (bool) ( $settings['sorting'] ?? true );
			if ( ! $has_sorting ) {
				$classes[] = 'disable-sorting';
			}

			$has_accordion = (bool) ( $settings['accordion'] ?? false );
			if ( $has_accordion ) {
				$classes[] = 'has-accordion';
			}

			$has_duplicate = (bool) ( $settings['duplicate'] ?? false );
			if ( $has_duplicate ) {
				$classes[] = 'has-duplicate';
			}
			?>
            <div class="<?php
			echo esc_attr( implode( ' ', $classes ) ); ?>"
                data-id="<?php
				echo esc_attr( $settings['id'] ) ?>">

                <div class="layouts" data-id="<?php echo esc_attr( $settings['id'] ) ?>">
					<?php foreach ( $settings['layouts'] as $layout_type => $layout ) : ?>
                        <div class="layout" data-type="<?php echo esc_attr( $layout_type ) ?>">
                            <div class="layout__inner">
                                <?php if ( $has_sorting ) : ?>
                                    <span class="customize-control-flexible-content-move">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="14" viewBox="0 0 10 14" fill="none">
                                        <path d="M1.75 0.5C1.19772 0.5 0.75 0.947715 0.75 1.5V2.5C0.75 3.05228 1.19772 3.5 1.75 3.5H2.75C3.30228 3.5 3.75 3.05228 3.75 2.5V1.5C3.75 0.947715 3.30228 0.5 2.75 0.5H1.75Z" fill="#4A4A4A"/>
                                        <path d="M1.75 5.5C1.19772 5.5 0.75 5.94772 0.75 6.5V7.5C0.75 8.05228 1.19772 8.5 1.75 8.5H2.75C3.30228 8.5 3.75 8.05228 3.75 7.5V6.5C3.75 5.94772 3.30228 5.5 2.75 5.5H1.75Z" fill="#4A4A4A"/>
                                        <path d="M0.75 11.5C0.75 10.9477 1.19772 10.5 1.75 10.5H2.75C3.30228 10.5 3.75 10.9477 3.75 11.5V12.5C3.75 13.0523 3.30228 13.5 2.75 13.5H1.75C1.19772 13.5 0.75 13.0523 0.75 12.5V11.5Z" fill="#4A4A4A"/>
                                        <path d="M7.25 0.5C6.69772 0.5 6.25 0.947715 6.25 1.5V2.5C6.25 3.05228 6.69772 3.5 7.25 3.5H8.25C8.80228 3.5 9.25 3.05228 9.25 2.5V1.5C9.25 0.947715 8.80228 0.5 8.25 0.5H7.25Z" fill="#4A4A4A"/>
                                        <path d="M6.25 6.5C6.25 5.94772 6.69772 5.5 7.25 5.5H8.25C8.80228 5.5 9.25 5.94772 9.25 6.5V7.5C9.25 8.05228 8.80228 8.5 8.25 8.5H7.25C6.69772 8.5 6.25 8.05228 6.25 7.5V6.5Z" fill="#4A4A4A"/>
                                        <path d="M7.25 10.5C6.69772 10.5 6.25 10.9477 6.25 11.5V12.5C6.25 13.0523 6.69772 13.5 7.25 13.5H8.25C8.80228 13.5 9.25 13.0523 9.25 12.5V11.5C9.25 10.9477 8.80228 10.5 8.25 10.5H7.25Z" fill="#4A4A4A"/>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                                <div class="layout-header">
                                    <div class="layout-count">1</div>
                                    <div class="layout-title"<?php
                                    if ( isset( $layout['title-field'] ) && ! empty( $layout['title-field'] ) ) {
                                        echo ' data-title-field="' . esc_attr( $layout['title-field'] ) . '"';
                                    } ?>>
                                        <?php
                                        echo esc_html( $layout['title'] ) ?>
                                    </div>
                                    <div class="layout-toggle">
                                        <?php if ( $has_accordion ) : ?>
                                            <span class="customize-control-flexible-content-accordion">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="7" viewBox="0 0 10 7" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.71967 0.732854C1.01256 0.43996 1.48744 0.43996 1.78033 0.732854L5.25 4.20252L8.71967 0.732854C9.01256 0.43996 9.48744 0.43996 9.78033 0.732854C10.0732 1.02575 10.0732 1.50062 9.78033 1.79351L5.78033 5.79351C5.48744 6.08641 5.01256 6.08641 4.71967 5.79351L0.71967 1.79351C0.426777 1.50062 0.426777 1.02575 0.71967 0.732854Z" fill="#4A4A4A"/>
                                                </svg>
                                            </span>
                                        <?php endif; ?>
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
                                            if ( 'fields_group' === $sub_field['type'] ) {
                                                static::fields_group( $sub_field, $value, $module_id, true, array(
                                                    'id'         => $settings['id'],
                                                    'option_key' => 0,
                                                    'value'      => $value,
                                                ) );
                                            } else {
                                                static::replace_field(
                                                    $sub_field,
	                                                $sub_field['default'] ?? '',
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
                                                    $module_id
                                                );
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    endforeach; ?>
                                    <input type="hidden" data-name="merchant[<?php
                                    echo esc_attr( $settings['id'] ) ?>][0][layout]" value="<?php
                                    echo esc_attr( $layout_type ) ?>">
                                </div>
                                <?php self::print_flexible_layout_actions( $settings, $layout_type ); ?>
                            </div>
                        </div>
					<?php
					endforeach; ?>
                </div>

                <div class="merchant-flexible-content <?php echo esc_attr( $empty ); ?> sortable">
					<?php
					foreach ( $values as $option_key => $option ) : ?>
                        <div class="layout" data-type="<?php echo esc_attr( $option['layout'] ) ?>">
                            <div class="layout__inner">
                                <?php if ( $has_sorting ) : ?>
                                    <span class="customize-control-flexible-content-move">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="14" viewBox="0 0 10 14" fill="none">
                                        <path d="M1.75 0.5C1.19772 0.5 0.75 0.947715 0.75 1.5V2.5C0.75 3.05228 1.19772 3.5 1.75 3.5H2.75C3.30228 3.5 3.75 3.05228 3.75 2.5V1.5C3.75 0.947715 3.30228 0.5 2.75 0.5H1.75Z" fill="#4A4A4A"/>
                                        <path d="M1.75 5.5C1.19772 5.5 0.75 5.94772 0.75 6.5V7.5C0.75 8.05228 1.19772 8.5 1.75 8.5H2.75C3.30228 8.5 3.75 8.05228 3.75 7.5V6.5C3.75 5.94772 3.30228 5.5 2.75 5.5H1.75Z" fill="#4A4A4A"/>
                                        <path d="M0.75 11.5C0.75 10.9477 1.19772 10.5 1.75 10.5H2.75C3.30228 10.5 3.75 10.9477 3.75 11.5V12.5C3.75 13.0523 3.30228 13.5 2.75 13.5H1.75C1.19772 13.5 0.75 13.0523 0.75 12.5V11.5Z" fill="#4A4A4A"/>
                                        <path d="M7.25 0.5C6.69772 0.5 6.25 0.947715 6.25 1.5V2.5C6.25 3.05228 6.69772 3.5 7.25 3.5H8.25C8.80228 3.5 9.25 3.05228 9.25 2.5V1.5C9.25 0.947715 8.80228 0.5 8.25 0.5H7.25Z" fill="#4A4A4A"/>
                                        <path d="M6.25 6.5C6.25 5.94772 6.69772 5.5 7.25 5.5H8.25C8.80228 5.5 9.25 5.94772 9.25 6.5V7.5C9.25 8.05228 8.80228 8.5 8.25 8.5H7.25C6.69772 8.5 6.25 8.05228 6.25 7.5V6.5Z" fill="#4A4A4A"/>
                                        <path d="M7.25 10.5C6.69772 10.5 6.25 10.9477 6.25 11.5V12.5C6.25 13.0523 6.69772 13.5 7.25 13.5H8.25C8.80228 13.5 9.25 13.0523 9.25 12.5V11.5C9.25 10.9477 8.80228 10.5 8.25 10.5H7.25Z" fill="#4A4A4A"/>
                                        </svg>
                                    </span>
                                <?php endif; ?>
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
                                    <div class="layout-toggle">
                                        <?php if ( $has_accordion ) : ?>
                                            <span class="customize-control-flexible-content-accordion">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="7" viewBox="0 0 10 7" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.71967 0.732854C1.01256 0.43996 1.48744 0.43996 1.78033 0.732854L5.25 4.20252L8.71967 0.732854C9.01256 0.43996 9.48744 0.43996 9.78033 0.732854C10.0732 1.02575 10.0732 1.50062 9.78033 1.79351L5.78033 5.79351C5.48744 6.08641 5.01256 6.08641 4.71967 5.79351L0.71967 1.79351C0.426777 1.50062 0.426777 1.02575 0.71967 0.732854Z" fill="#4A4A4A"/>
                                                </svg>
                                            </span>
                                        <?php endif; ?>
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
                                            } elseif ( isset( $sub_field['type'] ) && $sub_field['type'] === 'switcher' ) {
	                                            $value = is_null( $value ) ? 0 : 1;
                                            } elseif ( isset( $sub_field['default'] ) ) {
                                                $value = $sub_field['default'];
                                            }

                                            if ( 'fields_group' === $sub_field['type'] ) {
                                                static::fields_group( $sub_field, $value, $module_id, true, array(
                                                    'id'         => $settings['id'],
                                                    'option_key' => $option_key,
                                                    'value'      => $option,
                                                ) );
                                            } else {
                                                static::replace_field(
                                                    $sub_field,
                                                    $value,
                                                    "name=\"merchant[{$sub_field['id']}]",
                                                    "name=\"merchant[{$settings['id']}][{$option_key}][{$sub_field['id']}]",
                                                    $module_id
                                                );
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    endforeach; ?>
                                    <input type="hidden" name="merchant[<?php
                                    echo esc_attr( $settings['id'] ) ?>][<?php
                                    echo absint( $option_key ) ?>][layout]"
                                            value="<?php
                                            echo esc_attr( $option['layout'] ) ?>">
                                </div>
                                <?php self::print_flexible_layout_actions( $settings, $layout_type ); ?>
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
         * Field: Group.
         *
		 * @param $settings        array field settings.
		 * @param $value           mixed field value.
		 * @param $module_id       string module id.
		 * @param $inside_flexible boolean is inside flexible content.
		 * @param $args            array extra arguments to be passed if inside flexible content.
		 *
		 * @return void
		 */
		public static function fields_group( $settings, $value, $module_id = '', $inside_flexible = false, $args = array() ) {
			$control_field_status = ! empty( $settings['display_status'] ) && $settings['display_status'] === true;
			$accordion            = ! empty( $settings['accordion'] ) && $settings['accordion'] === true;
			$state                = ! empty( $settings['state'] ) && $settings['state'] === 'open';
			?>
            <div class="merchant-group-field<?php
			echo $accordion ? ' has-accordion' : '';
			echo $control_field_status ? ' has-flag' : '';
            echo $state ? ' open' : '';
			echo ' merchant-group-field-' . esc_attr( $settings['id'] ) ?>" data-id="<?php
			echo esc_attr( $settings['id'] ) ?>">
                <div class="title-area<?php
				echo $accordion ? ' accordion-style' : '' ?>">
					<?php
					if ( ! empty( $settings['title'] ) ) {
						printf( '<div class="merchant-module-page-setting-field-title">%s<span class="field-status hidden"></span></div>', esc_html( $settings['title'] ) );
					}
					if ( ! empty( $settings['sub-desc'] ) ) {
						printf( '<div class="merchant-module-page-setting-field-desc">%s</div>', wp_kses_post( $settings['sub-desc'] ) );
					}
					if ( $accordion ) {
		                ?>
                            <span class="accordion-icon dashicons dashicons-arrow-down-alt2"></span>
		                <?php
	                }
	                ?>
                </div>
                <div class="merchant-group-fields-container">
					<?php
					if ( $control_field_status ) {
						/**
						 * Field: Status.
						 *
						 * @since 1.9.12
						 */
						$status = apply_filters(
							'merchant_group_status_field',
							array(
								'id'      => $settings['id'] . '_status',
								'type'    => 'select',
								'title'   => esc_html__( 'Status', 'merchant' ),
								'options' => array(
									'inactive' => esc_html__( 'Inactive', 'merchant' ),
									'active'   => esc_html__( 'Active', 'merchant' ),
								),
								'default' => isset( $settings['default'] ) ? $settings['default'] : 'active',
							),
							$settings,
							$value,
							$module_id
						);
						if ( $inside_flexible ) {
							static::replace_field(
								$status,
								isset( $args['value'][$settings['id']][ $status['id'] ] ) ? $args['value'][$settings['id']][ $status['id'] ] : $status['default'] ?? '',
								"name=\"merchant[{$status['id']}]",
								"name=\"merchant[{$args['id']}][{$args['option_key']}][{$settings['id']}][{$status['id']}]\"  data-name=\"merchant[{$args['id']}][0][{$settings['id']}][{$status['id']}]",
								$module_id
							);
						} else {
							static::replace_field(
								$status,
								isset( $value[ $status['id'] ] ) ? $value[ $status['id'] ] : '',
								"name=\"merchant[{$status['id']}]",
								"name=\"merchant[{$settings['id']}][{$status['id']}]",
								$module_id
							);
						}
					}

					foreach ( $settings['fields'] as $field ) {
						if ( $inside_flexible ) {
							static::replace_field(
								$field,
								isset( $args['value'][$settings['id']][ $field['id'] ] ) ? $args['value'][$settings['id']][ $field['id'] ] : ( $field['default'] ?? '' ),
								"name=\"merchant[{$field['id']}]",
								"name=\"merchant[{$args['id']}][{$args['option_key']}][{$settings['id']}][{$field['id']}]\"  data-name=\"merchant[{$args['id']}][0][{$settings['id']}][{$field['id']}]",
								$module_id
							);
						} else {
							static::replace_field(
								$field,
								isset( $value[ $field['id'] ] ) ? $value[ $field['id'] ] : ( $field['default'] ?? '' ),
								"name=\"merchant[{$field['id']}]",
								"name=\"merchant[{$settings['id']}][{$field['id']}]",
								$module_id
							);
						}
					} ?>
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

        private static function print_flexible_layout_actions( $settings, $layout_type ) {
	        $has_duplicate = ! empty( $settings['duplicate'] );
	        $has_accordion = ! empty( $settings['accordion'] );
            ?>
            <div class="layout-actions<?php echo $has_accordion ? esc_attr( ' layout-actions-has_accordion' ) :  ' layout-actions-no_accordion'; ?>">
                <a href="#" class="layout-actions__toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="4" viewBox="0 0 14 4" fill="none">
                        <path d="M1.5 0.763184C2.32843 0.763183 3 1.43476 3 2.26318C3 3.09161 2.32843 3.76318 1.5 3.76318C0.671573 3.76318 -2.93554e-08 3.09161 -6.55671e-08 2.26318C-1.01779e-07 1.43476 0.671573 0.763184 1.5 0.763184Z" fill="#4A4A4A"/>
                        <path d="M7 0.763183C7.82843 0.763183 8.5 1.43476 8.5 2.26318C8.5 3.09161 7.82843 3.76318 7 3.76318C6.17157 3.76318 5.5 3.09161 5.5 2.26318C5.5 1.43476 6.17157 0.763183 7 0.763183Z" fill="#4A4A4A"/>
                        <path d="M12.5 0.763183C13.3284 0.763183 14 1.43476 14 2.26318C14 3.09161 13.3284 3.76318 12.5 3.76318C11.6716 3.76318 11 3.09161 11 2.26318C11 1.43476 11.6716 0.763183 12.5 0.763183Z" fill="#4A4A4A"/>
                    </svg>
                </a>
                <div class="layout-actions__inner" style="display: none;">
			        <?php if ( $has_duplicate ) : ?>
                        <a
                            href="#"
                            class="customize-control-flexible-content-duplicate"
                            data-id="<?php echo esc_attr( $settings['id'] ); ?>"
                            data-layout="<?php echo esc_attr( $layout_type ); ?>"
                            title="<?php echo esc_attr__( 'Duplicate', 'merchant' ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 22" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2 1.76318H13C13.2761 1.76318 13.5 1.98704 13.5 2.26318V13.2632C13.5 13.5393 13.2761 13.7632 13 13.7632H2C1.72386 13.7632 1.5 13.5393 1.5 13.2632V2.26318C1.5 1.98704 1.72386 1.76318 2 1.76318ZM0 2.26318C0 1.15861 0.895431 0.263184 2 0.263184H13C14.1046 0.263184 15 1.15861 15 2.26318V13.2632C15 14.3678 14.1046 15.2632 13 15.2632H2C0.89543 15.2632 0 14.3678 0 13.2632V2.26318ZM17 5.26318V16.0132C17 16.7035 16.4404 17.2632 15.75 17.2632H3V18.7632H15.75C17.2688 18.7632 18.5 17.532 18.5 16.0132V5.26318H17Z" fill="#757575"/>
                            </svg>
                            <span><?php echo esc_html__( 'Duplicate', 'merchant' ); ?></span>
                        </a>
			        <?php endif; ?>

                    <a class="customize-control-flexible-content-delete" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="17" viewBox="0 0 14 17" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 0.263184C5.18578 0.263184 3.67247 1.55151 3.32502 3.26318H0V4.76318H1.2699L2.08782 13.7602C2.21659 15.1767 3.40421 16.2613 4.82652 16.2613H9.17366C10.596 16.2613 11.7836 15.1767 11.9124 13.7602L12.7303 4.76318H14V3.26318H10.675C10.3275 1.5515 8.81422 0.263184 7 0.263184ZM7 1.76318C6.02034 1.76318 5.18691 2.38929 4.87803 3.26318H9.12197C8.81309 2.38929 7.97966 1.76318 7 1.76318ZM11.2241 4.76318H2.77609L3.58166 13.6244C3.64019 14.2683 4.18002 14.7613 4.82652 14.7613H9.17366C9.82017 14.7613 10.36 14.2683 10.4185 13.6244L11.2241 4.76318Z" fill="#757575"/>
                        </svg>
                        <span><?php echo esc_html__( 'Delete', 'merchant' ); ?></span>
                    </a>
                </div>
            </div>
            <?php
        }

		/**
         * Get category choices for select2
         *
		 * @return array
		 */
		public static function get_category_select2_choices() {
			$choices    = array();
			$categories = merchant_get_product_categories();

			if ( is_array( $categories ) && ! empty( $categories ) ) {
			    $choices = self::build_category_select2_choices( $categories );
			}

			return $choices;
		}

		/**
         * Build Select2 choices for hierarchical categories.
         *
		 * @param $categories
		 * @param $level
		 *
		 * @return array
		 */
		private static function build_category_select2_choices( $categories, $level = 0 ) {
			$choices = array();

			foreach ( $categories as $cat ) {
				$indent = str_repeat( '&nbsp;', $level * 4 ); // Use non-breaking spaces for indentation

                $choices[] = array(
					'id'   => esc_attr( $cat['slug'] ?? '' ),
					'text' => esc_html( $indent . ( $cat['name'] ?? '' ) ),
				);

				if ( ! empty( $cat['children'] ) ) {
					$choices = array_merge( $choices, self::build_category_select2_choices( $cat['children'], $level + 1 ) );
				}
			}

			return $choices;
		}

        /**
         * Get Tag choices for select2
         *
		 * @return array
		 */
		public static function get_tag_select2_choices() {
			$choices = array();
			$tags    = merchant_get_product_tags();

			if ( is_array( $tags ) && ! empty( $tags ) ) {
				foreach ( $tags as $slug => $name ) {
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
