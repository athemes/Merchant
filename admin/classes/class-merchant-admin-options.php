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
		public function __construct() {}

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
						<?php if ( ! empty( $settings['title'] ) ) : ?>
							<div class="merchant-module-page-setting-title">
								<?php echo esc_html( $settings['title'] ); ?>
								<?php if ( ! empty( $settings['subtitle'] ) ) : ?>
									<div class="merchant-module-page-setting-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
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

									self::field( $field, $value );

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

			$options = get_option( 'merchant', array() );

			if ( ! empty( $save ) ) {

				if ( ! empty( $settings['fields'] ) ) {

					foreach ( $settings['fields'] as $field ) {

						$value = null;

						if ( isset( $_POST['merchant'] ) && isset( $_POST['merchant'][ $field['id'] ] ) ) {
							$value = sanitize_text_field( wp_unslash( $_POST['merchant'][ $field['id'] ] ) );
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
					$value = sanitize_text_field( $value );
					break;

				case 'textarea':
					$value = sanitize_textarea_field( $value );
					break;

				case 'checkbox':
				case 'switcher':
					$value = ( '1' === $value ) ? 1 : 0;
					break;

				case 'range':
				case 'number':
					$value = absint( $value );
					break;

				case 'radio':
				case 'radio_alt':
				case 'select':
				case 'choices':
					$value = ( in_array( $value, array_keys( $field['options'] ), true ) ) ? sanitize_key( $value ) : '';
					break;

				case 'code-editor':
					$value = wp_kses_post( $value );
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

				$class     = ( ! empty( $settings['class'] ) ) ? ' ' . $settings['class'] : '';
				$condition = ( ! empty( $settings['condition'] ) ) ? $settings['condition'] : array();

				echo '<div class="merchant-module-page-setting-field merchant-module-page-setting-field-' . esc_attr( $type ) . '' . esc_attr( $class ) . '" data-condition="' . esc_attr( wp_json_encode( $condition ) ) . '">';

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
		 * Field: Text
		 */
		public static function text( $settings, $value ) {
			?>
				<input type="text" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
			<?php
		}

		/**
		 * Field: Number
		 */
		public static function number( $settings, $value ) {
			?>
				<input type="number" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
			<?php
		}

		/**
		 * Field: Textarea
		 */
		public static function textarea( $settings, $value ) {
			$value = ( $value ) ? $value : '';
			?>
				<textarea name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]"><?php echo wp_kses_post( $value ); ?></textarea>
			<?php
		}

		/**
		 * Field: Checkbox
		 */
		public static function checkbox( $settings, $value ) {

			?>
				<div>
					<label>
						<input type="checkbox" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="1" <?php checked( $value, 1, true ); ?> />
						<?php if ( ! empty( $settings['label'] ) ) : ?>
							<span><?php echo esc_html( $settings['label'] ); ?></span>
						<?php endif; ?>
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
					<input type="checkbox" id="<?php echo esc_attr( $settings['id'] ); ?>" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="1" <?php checked( $value, 1, true ); ?> class="toggle-switch-checkbox" />
					<label class="toggle-switch-label" for="<?php echo esc_attr( $settings['id'] ); ?>">
						<span class="toggle-switch-inner"></span>
						<span class="toggle-switch-switch"></span>
					</label>
					<?php if ( ! empty( $settings['label'] ) ) : ?>
						<span><?php echo esc_html( $settings['label'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php

		}

		/**
		 * Field: Radio
		 */
		public static function radio( $settings, $value ) {

			?>
				<?php if ( ! empty( $settings['options'] ) ) : ?>
					<?php foreach ( $settings['options'] as $key => $option ) : ?>
						<label>
							<input type="radio" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $value, $key, true ); ?>/>
							<span><?php echo esc_html( $option ); ?></span>
						</label>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php

		}

		/**
		 * Field: Radio Alt
		 */
		public static function radio_alt( $settings, $value ) {

			?>
				<?php if ( ! empty( $settings['options'] ) ) : ?>
					<?php foreach ( $settings['options'] as $key => $option ) : ?>
						<div>
							<label>
								<input type="radio" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $value, $key, true ); ?>/>
								<span><?php echo esc_html( $option['title'] ); ?></span>
							</label>
							<p><?php echo esc_html( $option['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php

		}

		/**
		 * Field: Choices
		 */
		public static function choices( $settings, $value ) {

			?>
				<div class="merchant-choices">
					<?php if ( ! empty( $settings['options'] ) ) : ?>
						<?php foreach ( $settings['options'] as $key => $option ) : ?>
							<label>
								<input type="radio" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $value, $key, true ); ?>/>
								<figure><img src="<?php echo esc_url( sprintf( $option, MERCHANT_URI . 'assets/images' ) ); ?>" title="" /></figure>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			<?php

		}

		/**
		 * Field: Select
		 */
		public static function select( $settings, $value ) {

			?>
				<?php if ( ! empty( $settings['options'] ) ) : ?>
					<select name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]">
						<?php foreach ( $settings['options'] as $key => $option ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key, true ); ?>><?php echo esc_html( $option ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			<?php

		}

		/**
		 * Field: Buttons
		 */
		public static function buttons( $settings, $value ) {

			?>
				<div class="merchant-buttons">
					<?php if ( ! empty( $settings['options'] ) ) : ?>
						<?php foreach ( $settings['options'] as $key => $option ) : ?>
							<label>
								<input type="radio" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $value, $key, true ); ?>/>
								<span><?php echo esc_html( $option ); ?></span>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			<?php

		}

		/**
		 * Field: Buttons Alt
		 */
		public static function buttons_alt( $settings, $value ) {

			?>
				<div class="merchant-buttons">
					<?php if ( ! empty( $settings['options'] ) ) : ?>
						<?php foreach ( $settings['options'] as $key => $option ) : ?>
							<label class="merchant-button-<?php echo esc_attr( $key ); ?>">
								<input type="radio" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $value, $key, true ); ?>/>
								<span><?php echo esc_html( $option ); ?></span>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>
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
					<input type="range" class="merchant-range-input" name="" min="<?php echo esc_attr( $settings['min'] ); ?>" max="<?php echo esc_attr( $settings['max'] ); ?>" step="<?php echo esc_attr( $settings['step'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					<input type="number" class="merchant-range-number-input" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" min="<?php echo esc_attr( $settings['min'] ); ?>" max="<?php echo esc_attr( $settings['max'] ); ?>" step="<?php echo esc_attr( $settings['step'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					<?php if ( ! empty( $settings['unit'] ) ) : ?>
						<span class="merchant-range-unit"><?php echo esc_html( $settings['unit'] ); ?></span>
					<?php endif; ?>
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
					<div class="merchant-color-picker" data-default-color="<?php echo esc_attr( $settings['default'] ); ?>" style="background-color: <?php echo esc_attr( $value ); ?>;"></div>
					<input type="text" class="merchant-color-input" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
				</div>
			<?php
		}

		/**
		 * Field: Gallery
		 */
		public static function gallery( $settings, $value ) {

			$settings = wp_parse_args( $settings, array(
				'label' => esc_Html__( 'Select Images', 'merchant' ),
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
				<a href="#" class="merchant-gallery-button"><?php echo esc_html( $settings['label'] ); ?></a>
				<input type="hidden" class="merchant-gallery-input" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
			<?php
		}

		/**
		 * Field: Upload
		 */
		public static function upload( $settings, $value ) {

			$settings = wp_parse_args( $settings, array(
				'label' => esc_Html__( 'Select Image', 'merchant' ),
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
				<a href="#" class="merchant-upload-button"><?php echo esc_html( $settings['label'] ); ?></a>
				<input type="hidden" class="merchant-upload-input" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
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
		public static function divider() {}

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
			$value = json_decode( $value );

			?>
				<div class="merchant-sortable">
					<ul class="merchant-sortable-list ui-sortable">
						<?php 
						foreach ( $value as $option_key ) :
							$option_val = $settings[ 'options' ][ $option_key ];
						
							if ( in_array( $option_key, $value ) ) : 
								?>
								<li class="merchant-sortable-item" data-value="<?php echo esc_attr( $option_key ); ?>">
									<i class='dashicons dashicons-menu'></i>
									<i class="dashicons dashicons-visibility visibility"></i>
									<?php echo esc_html( $option_val ); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>

						<?php 
						foreach ( $settings[ 'options' ] as $option_key => $option_val ) : 
							if ( ! in_array( $option_key, $value ) ) :
								$invisible = ! in_array( $option_key, $value ) ? ' invisible' : '';

								?>
								<li class="merchant-sortable-item<?php echo esc_attr( $invisible ); ?>" data-value="<?php echo esc_attr( $option_key ); ?>">
									<i class='dashicons dashicons-menu'></i>
									<i class="dashicons dashicons-visibility visibility"></i>
									<?php echo esc_html( $option_val ); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>

					<input class="merchant-sortable-input" type="hidden" name="merchant[<?php echo esc_attr( $settings['id'] ); ?>]" value="<?php echo esc_attr( json_encode( $value ) ); ?>" />
				</div>
			<?php
		}

	}

	Merchant_Admin_Options::instance();

}
