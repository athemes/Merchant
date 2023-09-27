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
?>

<div id="merchant-free-gifts-widget" class="merchant-free-gifts-widget <?php if (isset($args['hide']) && $args['hide'] === true) echo 'hide' ?>">
    <div class="merchant-free-gifts-widget-button" onclick="document.getElementById('merchant-free-gifts-widget').classList.toggle('active');">
        <span class="merchant-free-gifts-widget-count"><?php echo absint( $args['count'] ) ?></span>
        <div class="merchant-free-gifts-widget-icon">
            <svg fill="currentColor" height="800px" width="800px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 464.628 464.628" xmlns:xlink="http://www.w3.org/1999/xlink">
                <path d="m394.33,105.596h-76.136c22.944-22.372 37.216-53.596 37.216-88.096 0-9.665-7.835-17.5-17.5-17.5-44.786,0-84.057,24.045-105.596,59.9-21.539-35.855-60.81-59.9-105.595-59.9-9.665,0-17.5,7.835-17.5,17.5 0,34.5 14.273,65.724 37.216,88.096h-76.137c-9.665,0-17.5,7.835-17.5,17.5v324.033c0,9.665 7.835,17.5 17.5,17.5h324.032c9.665,0 17.5-7.835 17.5-17.5v-324.033c0-9.665-7.835-17.5-17.5-17.5zm-17.5,162.016h-127.016v-127.016h127.016v127.016zm-58.646-230.385c-7.525,32.765-33.378,58.618-66.144,66.143 7.526-32.766 33.379-58.619 66.144-66.143zm-105.596,66.142c-32.765-7.525-58.618-33.378-66.143-66.143 32.765,7.525 58.618,33.378 66.143,66.143zm2.226,37.227v127.016h-127.016v-127.016h127.016zm-127.016,162.016h127.016v127.017h-127.016v-127.017zm162.016,127.016v-127.016h127.016v127.017h-127.016z"/>
            </svg>
        </div>
    </div>
    <div class="merchant-free-gifts-widget-offers">
		<?php merchant_get_template_part( Merchant_Free_Gifts::MODULE_TEMPLATES_PATH, 'widget-contents', $args ); ?>
    </div>
</div>

