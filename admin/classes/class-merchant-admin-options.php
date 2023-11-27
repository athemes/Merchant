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
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'ajaxnonce' => wp_create_nonce( 'merchant_admin_options' ),
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
		public static function get( $module, $setting, $default ) {
			$options = get_option( 'merchant', array() );

			$value = $default;

			if ( isset( $options[ $module ] ) && isset( $options[ $module ][ $setting ] ) ) {
				$value = $options[ $module ][ $setting ];
			}

			return $value;
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
									self::field( $field, $value );
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
							} else {
								if ( is_array( $_POST['merchant'][ $field['id'] ] ) ) {
									$value = array_filter( map_deep( wp_unslash( $_POST['merchant'][ $field['id'] ] ), 'sanitize_text_field' ) );
								} else {
									$value = sanitize_text_field( wp_unslash( $_POST['merchant'][ $field['id'] ] ) );
								}
							}
						}


						$options[ $settings['module'] ][ $field['id'] ] = self::sanitize( $field, $value );
					}
				}
			} elseif ( ! empty( $reset ) ) {
				$options[ $settings['module'] ] = array();
			}

			update_option( 'merchant', $options );
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
		public static function field( $settings, $value ) {
			if ( ! empty( $settings['type'] ) ) {
				$type = $settings['type'];

				$id        = ( ! empty( $settings['id'] ) ) ? $settings['id'] : '';
				$class     = ( ! empty( $settings['class'] ) ) ? ' ' . $settings['class'] : '';
				$condition = ( ! empty( $settings['condition'] ) ) ? $settings['condition'] : array();
				$default   = ( ! empty( $settings['default'] ) ) ? $settings['default'] : null;
				if ( ! $value ) {
					$value = $default;
				}
				echo '<div class="merchant-module-page-setting-field merchant-module-page-setting-field-' . esc_attr( $type ) . '' . esc_attr( $class ) . '" data-id="'
				     . esc_attr( $id ) . '" data-type="' . esc_attr( $type ) . '" data-condition="' . esc_attr( wp_json_encode( $condition ) ) . '">';
				if ( ! empty( $settings['title'] ) ) {
					echo sprintf( '<div class="merchant-module-page-setting-field-title">%s</div>', esc_html( $settings['title'] ) );
				}

				echo '<div class="merchant-module-page-setting-field-inner">';
				if ( method_exists( 'Merchant_Admin_Options', $type ) ) {
					call_user_func( array( 'Merchant_Admin_Options', $type ), $settings, $value );
				} else {
					esc_html_e( 'Field not found!', 'merchant' );
				}
				echo '</div>';

				if ( ! empty( $settings['desc'] ) ) {
					echo sprintf( '<div class="merchant-module-page-setting-field-desc">%s</div>', wp_kses_post( $settings['desc'] ) );
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
		public static function disabled_field( $settings, $value ) {
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
				)
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
		public static function replace_field( $settings, $value, $search, $replace ) {
			ob_start();
			self::field( $settings, $value );
			$field = ob_get_clean();

			echo wp_kses( str_replace( $search, $replace, $field ), merchant_kses_allowed_tags( array( 'all' ) ) );
		}

		/**
		 * Field: Text
		 */
		public static function text( $settings, $value ) {
			?>
            <input type="text" name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" value="<?php
			echo esc_attr( $value ); ?>"/>
			<?php
		}

		/**
		 * Field: Text (readonly)
		 */
		public static function text_readonly( $settings, $value ) {
			?>
            <input type="text" value="<?php
			echo esc_attr( $value ); ?>" readonly/>
			<?php
		}

		/**
		 * Field: Number
		 */
		public static function number( $settings, $value ) {
			?>
            <input type="number" name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" value="<?php
			echo esc_attr( $value ); ?>"/>
			<?php
		}

		/**
		 * Field: Textarea
		 */
		public static function textarea( $settings, $value ) {
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
		public static function textarea_code( $settings, $value ) {
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
		public static function checkbox( $settings, $value ) {
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
		 * Field: Switcher
		 */
		public static function switcher( $settings, $value ) {
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
		public static function radio( $settings, $value ) {
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
		public static function radio_alt( $settings, $value ) {
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
		public static function choices( $settings, $value ) {
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
		public static function select( $settings, $value ) {
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
		 * Field: Select ajax
		 *
		 * @param $settings
		 * @param $value
		 *
		 * @return void
		 */
		public static function select_ajax( $settings, $value ) {
			$settings = wp_parse_args( $settings, array(
				'source' => 'post',
			) );

			$ids = ( is_array( $value ) && ! empty( $value ) ) ? $value : (array) $value;

			if ( isset( $settings['multiple'] ) ) {
				$multiple = $settings['multiple'] === true ? 'multiple' : '';
			} else {
				$multiple = 'multiple';
			}
			?>
            <select name="merchant[<?php
			echo esc_attr( $settings['id'] ); ?>]" <?php
			echo esc_attr( $multiple ) ?> data-source="<?php
			echo esc_attr( $settings['source'] ) ?>">
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
								echo '<option value="' . esc_attr( $child_option['id'] ) . '" ' . selected( $child_option['id'], $value ) . '>' . esc_html( $child_option['text'] )
								     . '</option>';
							}

							echo '</optgroup>';
						} else {
							echo '<option value="' . esc_attr( $option['id'] ) . '" ' . selected( $option['id'], $value ) . '>' . esc_html( $option['text'] ) . '</option>';
						}
					}
				} ?>
            </select>
			<?php
		}

		/**
		 * Field: Select Size Chart
		 */
		public static function select_size_chart( $settings, $value ) {
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
		public static function buttons( $settings, $value ) {
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
		public static function buttons_alt( $settings, $value ) {
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
		public static function range( $settings, $value ) {
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
		public static function color( $settings, $value ) {
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
		public static function gallery( $settings, $value ) {
			$settings = wp_parse_args( $settings, array(
				'label' => esc_html__( 'Select Images', 'merchant' ),
			) );

			$images = ( ! empty( $value ) ) ? explode( ',', $value ) : array();


			echo '<div class="merchant-gallery-images">';

			if ( ! empty( $images ) ) :

				foreach ( $images as $image_id ) :

					$image = wp_get_attachment_image_src( $image_id, 'thumbnail' );

					if ( ! empty( $image ) && ! empty( $image[0] ) ) :

						echo sprintf( '<div class="merchant-gallery-image" data-item-id="%s">', esc_attr( $image_id ) );

						echo '<i class="merchant-gallery-remove dashicons dashicons-no-alt"></i>';

						echo sprintf( '<img src="%s" />', esc_url( $image[0] ) );

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
		public static function upload( $settings, $value ) {
			$settings = wp_parse_args( $settings, array(
				'label' => esc_html__( 'Select Image', 'merchant' ),
			) );

			echo '<div class="merchant-upload-wrapper">';

			if ( ! empty( $value ) ) :

				$image = wp_get_attachment_image_src( $value, 'thumbnail' );

				if ( ! empty( $image ) && ! empty( $image[0] ) ) :

					echo '<div class="merchant-upload-image">';
					echo '<i class="merchant-upload-remove dashicons dashicons-no-alt"></i>';
					echo sprintf( '<img src="%s" />', esc_url( $image[0] ) );
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
		public static function sortable( $settings, $value ) {
			?>
            <div class="merchant-sortable">
                <ul class="merchant-sortable-list ui-sortable">
					<?php
					foreach ( $value as $option_key ) :
						$option_val = $settings['options'][ $option_key ];

						if ( in_array( $option_key, $value ) ) :
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
						if ( ! in_array( $option_key, $value ) ) :
							$invisible = ! in_array( $option_key, $value ) ? ' invisible' : '';

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
		public static function sortable_repeater( $settings, $value ) {
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

		/**
		 * Field: Flexible Content.
		 *
		 * @param array $settings
		 * @param mixed $value
		 *
		 * @return void
		 */
		public static function flexible_content( $settings, $value ) {
			$values = ( is_array( $value ) && ! empty( $value ) ) ? $value : array();
			$empty  = empty( $values ) ? 'empty' : '';

			$settings = wp_parse_args( $settings, array(
				'sorting' => false,
				'style'   => 'default',
			) );

			$classes = array(
				'merchant-flexible-content-control',
				"{$settings['style']}-style",
			);

			if ( $settings['sorting'] === false ) {
				$classes[] = 'disable-sorting';
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
                                <div class="layout-title">
									<?php
									echo esc_html( $layout['title'] ) ?>
                                </div>
                                <div class="layout-actions">
                                    <span class="customize-control-flexible-content-move dashicons dashicons-menu"></span>
                                    <a class="customize-control-flexible-content-delete" href="#">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </a>
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
											) ) ?>
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
                                <div class="layout-title">
									<?php
									echo esc_html( $settings['layouts'][ $option['layout'] ]['title'] ) ?>
                                </div>
                                <div class="layout-actions">
                                    <span class="customize-control-flexible-content-move dashicons dashicons-menu"></span>
                                    <a class="customize-control-flexible-content-delete" href="#">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </a>
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
											"name=\"merchant[{$settings['id']}][{$option_key}][{$sub_field['id']}]" ) ?>
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
		public static function create_page( $settings, $value ) {
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
					sprintf( /* translators: 1: page name */
						__( '<p class="merchant-module-page-setting-field-desc mrc-mt-0">It looks like you haven\'t created a <strong>%1$s</strong> page yet. Click the below button to create the page.</p>',
							'merchant' ),
						$settings['page_title']
					)
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

		public static function dimensions( $settings, $value ) {
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

		public static function responsive_dimensions( $settings, $value ) {
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
		public static function custom_callback( $settings, $value ) {
			if ( ! empty( $settings['class_name'] ) && ! empty( $settings['callback_name'] ) ) {
				return call_user_func( array( $settings['class_name'], $settings['callback_name'] ), $settings, $value );
			}

			if ( ! empty( $settings['callback_name'] ) ) {
				return call_user_func( $settings['callback_name'], $settings, $value );
			}

			return false;
		}

	}

	Merchant_Admin_Options::instance();
}
