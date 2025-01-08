<?php
/**
 * All Campaigns.
 *
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="merchant-modules-header-heading"><?php esc_html_e( 'All Campaigns', 'merchant' ); ?></div>
<div class="merchant-module-page merchant-page-campaigns">
	<?php require_once MERCHANT_DIR . 'admin/components/table-campaigns.php'; ?>
</div>

