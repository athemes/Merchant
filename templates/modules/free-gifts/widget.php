<?php
/**
 * Template for free gifts module widget
 *
 * @var $args array template args
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$settings = isset( $args['settings'] ) ? $args['settings'] : array();

$fg_classes  = 'merchant-free-gifts-widget';
$fg_classes .= ' merchant-free-gifts-widget--' . ( $settings['position'] ?? 'top_right' );
$fg_classes .= ! empty( $settings['mobile_width'] ) ? ' merchant-free-gifts-widget-has-mobile-width' : '';
?>
<div id="merchant-free-gifts-widget" class="<?php echo esc_attr( $fg_classes ); ?>">
    <div class="merchant-free-gifts-widget-inner">
        <div class="merchant-free-gifts-widget-button">
            <span class="merchant-free-gifts-widget-count"><?php echo absint( $args['count'] ?? 0 ); ?></span>
            <div class="merchant-free-gifts-widget-icon">
			    <?php echo wp_kses( Merchant_SVG_Icons::get_svg_icon( $settings['icon'] ?? 'gifts-icon-1' ), merchant_kses_allowed_tags( array(), false ) ); ?>
            </div>
        </div>
        <div class="merchant-free-gifts-widget-offers">
		    <?php merchant_get_template_part( Merchant_Free_Gifts::MODULE_TEMPLATES_PATH, 'widget-contents', $args ); ?>
        </div>
    </div>
</div>
