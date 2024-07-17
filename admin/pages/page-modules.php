<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$merchant_modules = Merchant_Admin_Modules::get_modules(); 

?>

<div class="merchant-modules-header">

	<div class="merchant-modules-header-left">

		<div class="merchant-module-page-back">
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'merchant' ), 'admin.php' ) ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.3329 7.20013H4.46628L7.19961 4.20013L6.46628 3.4668L2.59961 7.6668L6.46628 11.5335L7.19961 10.8001L4.53294 8.20013H13.3329V7.20013Z" fill="#1D2327"/>
				</svg>
				<span><?php esc_html_e( 'Go Back', 'merchant' ); ?></span>
			</a>
		</div>

		<div class="merchant-modules-header-heading"><?php esc_html_e( 'Your Enabled Modules', 'merchant' ); ?> <?php esc_html_e('👍', 'merchant'); ?></div>

		<div class="merchant-modules-header-subheading"><?php esc_html_e( 'Quickly navigate through all the enabled modules and customize each in real-time.', 'merchant' ); ?></div>

		<div class="merchant-modules-header-status">
			<div class="merchant-modules-header-status-box">
				<?php esc_html_e( 'Total Modules', 'merchant' ); ?>
				<strong>
					<?php
					$merchant_count          = 0;
					$merchant_active_modules = 0;
					$merchant_option_modules = get_option( 'merchant-modules', array() );

					if ( ! empty( $merchant_modules ) ) {
						foreach ( $merchant_modules as $merchant_data ) {
							if ( ! empty( $merchant_data['modules'] ) ) {
								foreach ( $merchant_data['modules'] as $merchant_module_id => $merchant_module ) {
									if ( ! empty( $merchant_option_modules[ $merchant_module_id ] ) ) {
										++$merchant_active_modules;
									}

									++$merchant_count;
								}
							}
						}
					}
					
					echo esc_attr( $merchant_count );
					?>
				</strong>
			</div>
			<div class="merchant-modules-header-status-box">
				<span><?php esc_html_e( 'Active Modules', 'merchant' ); ?><i class="merchant-modules-indicator"></i></span>
				<strong><?php echo esc_html( $merchant_active_modules ); ?></strong>
			</div>
		</div>

	</div>

	<div class="merchant-modules-header-right">
		<div class="merchant-modules-header-image">
			<img src="<?php echo esc_url( MERCHANT_URI ); ?>assets/images/modules-hero.svg" alt="Merchant" />
		</div>
	</div>

</div>

<div class="merchant-modules-panel">

	<?php if ( ! empty( $merchant_modules ) ) : ?>

		<div class="merchant-modules-box">

			<div class="merchant-modules-box-heading">
				<?php esc_html_e( 'My Modules', 'merchant' ); ?>
			</div>

			<div class="merchant-modules-list">

				<?php foreach ( $merchant_modules as $merchant_data ) : ?>

					<?php if ( ! empty( $merchant_data['modules'] ) ) : ?>

						<?php foreach ( $merchant_data['modules'] as $merchant_module_id => $merchant_module ) : ?>

							<?php 
							if ( ! Merchant_Modules::is_module_active( $merchant_module_id ) ) {
								continue;
							} 
							?>

							<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'merchant', 'module' => $merchant_module_id ), 'admin.php' ) ); ?>" class="merchant-modules-list-item">

								<div class="merchant-modules-list-item-icon">
									<?php if ( ! empty( $merchant_module['icon'] ) ) : ?>
										<?php echo wp_kses( $merchant_module['icon'], merchant_kses_allowed_tags( array(), false ) ); ?>
									<?php else : ?>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M18.7744 12.9949C18.652 12.8581 18.5845 12.6822 18.5845 12.5C18.5845 12.3178 18.652 12.1419 18.7744 12.0051L19.7516 10.9252C19.8593 10.8072 19.9261 10.6588 19.9426 10.5012C19.959 10.3435 19.9242 10.1848 19.8432 10.0478L18.3164 7.45319C18.2362 7.31635 18.114 7.20788 17.9673 7.14324C17.8206 7.07861 17.6569 7.06111 17.4995 7.09324L16.0643 7.3782C15.8817 7.41526 15.6916 7.38539 15.5299 7.29421C15.3681 7.20303 15.246 7.05685 15.1864 6.88326L14.7208 5.51095C14.6695 5.362 14.572 5.23264 14.4418 5.14113C14.3117 5.04963 14.1556 5.00061 13.9955 5.00102H10.9419C10.7754 4.99248 10.6107 5.03771 10.4728 5.12979C10.335 5.22187 10.2316 5.35574 10.1785 5.51095L9.751 6.88326C9.69146 7.05685 9.56929 7.20303 9.40756 7.29421C9.24582 7.38539 9.05571 7.41526 8.87309 7.3782L7.39972 7.09324C7.25051 7.07252 7.0984 7.09565 6.96256 7.15971C6.82671 7.22376 6.71319 7.32588 6.63632 7.45319L5.10951 10.0478C5.02642 10.1833 4.98906 10.3411 5.00277 10.4987C5.01648 10.6563 5.08056 10.8056 5.18585 10.9252L6.15537 12.0051C6.27776 12.1419 6.34526 12.3178 6.34526 12.5C6.34526 12.6822 6.27776 12.8581 6.15537 12.9949L5.18585 14.0748C5.08056 14.1944 5.01648 14.3437 5.00277 14.5013C4.98906 14.6589 5.02642 14.8167 5.10951 14.9522L6.63632 17.5468C6.71655 17.6837 6.83871 17.7921 6.98538 17.8568C7.13206 17.9214 7.29576 17.9389 7.45316 17.9068L8.88836 17.6218C9.07098 17.5847 9.26109 17.6146 9.42282 17.7058C9.58456 17.797 9.70673 17.9431 9.76627 18.1167L10.2319 19.489C10.2851 19.6443 10.3884 19.7781 10.5263 19.8702C10.6641 19.9623 10.8289 20.0075 10.9953 19.999H14.049C14.209 19.9994 14.3651 19.9504 14.4953 19.8589C14.6254 19.7674 14.723 19.638 14.7742 19.489L15.2399 18.1167C15.2994 17.9431 15.4216 17.797 15.5833 17.7058C15.745 17.6146 15.9352 17.5847 16.1178 17.6218L17.553 17.9068C17.7104 17.9389 17.8741 17.9214 18.0208 17.8568C18.1674 17.7921 18.2896 17.6837 18.3698 17.5468L19.8966 14.9522C19.9777 14.8152 20.0125 14.6565 19.996 14.4988C19.9796 14.3412 19.9127 14.1928 19.805 14.0748L18.7744 12.9949ZM17.637 13.9998L18.2477 14.6747L17.2705 16.3395L16.3697 16.1595C15.8199 16.0491 15.2479 16.1408 14.7624 16.4173C14.2769 16.6938 13.9117 17.1357 13.736 17.6593L13.4459 18.4992H11.4916L11.2167 17.6443C11.041 17.1208 10.6758 16.6788 10.1903 16.4023C9.70476 16.1258 9.13281 16.0341 8.58299 16.1445L7.68218 16.3245L6.68976 14.6672L7.30048 13.9923C7.67604 13.5798 7.88367 13.0459 7.88367 12.4925C7.88367 11.9391 7.67604 11.4052 7.30048 10.9927L6.68976 10.3178L7.66691 8.66802L8.56773 8.848C9.11755 8.9584 9.68949 8.86665 10.175 8.59018C10.6605 8.31371 11.0258 7.87175 11.2015 7.3482L11.4916 6.50082H13.4459L13.736 7.3557C13.9117 7.87925 14.2769 8.32121 14.7624 8.59768C15.2479 8.87415 15.8199 8.9659 16.3697 8.8555L17.2705 8.67552L18.2477 10.3403L17.637 11.0152C17.2656 11.4267 17.0606 11.9576 17.0606 12.5075C17.0606 13.0574 17.2656 13.5883 17.637 13.9998ZM12.4687 9.50041C11.8648 9.50041 11.2744 9.67633 10.7722 10.0059C10.2701 10.3355 9.87867 10.804 9.64755 11.3521C9.41643 11.9002 9.35595 12.5033 9.47378 13.0852C9.5916 13.6671 9.88243 14.2015 10.3095 14.621C10.7365 15.0405 11.2806 15.3262 11.873 15.442C12.4653 15.5577 13.0793 15.4983 13.6373 15.2713C14.1953 15.0442 14.6722 14.6598 15.0077 14.1665C15.3432 13.6732 15.5223 13.0933 15.5223 12.5C15.5223 11.7045 15.2006 10.9415 14.6279 10.379C14.0553 9.81644 13.2786 9.50041 12.4687 9.50041ZM12.4687 13.9998C12.1667 13.9998 11.8716 13.9118 11.6205 13.747C11.3694 13.5822 11.1737 13.348 11.0581 13.0739C10.9426 12.7999 10.9123 12.4983 10.9712 12.2074C11.0302 11.9165 11.1756 11.6492 11.3891 11.4395C11.6026 11.2297 11.8747 11.0869 12.1709 11.029C12.467 10.9712 12.774 11.0009 13.053 11.1144C13.332 11.2279 13.5704 11.4201 13.7382 11.6668C13.906 11.9134 13.9955 12.2034 13.9955 12.5C13.9955 12.8978 13.8347 13.2792 13.5483 13.5605C13.262 13.8418 12.8737 13.9998 12.4687 13.9998Z" fill="#787C82"/>
										</svg>
									<?php endif; ?>
								</div>

								<div class="merchant-modules-list-item-content">
									<div class="merchant-modules-list-item-title">
										<?php echo esc_html( $merchant_module['title'] ); ?>
										<i class="merchant-modules-indicator"></i>
									</div>
									<?php if ( ! empty( $merchant_module['desc'] ) ) : ?>
										<div class="merchant-modules-list-item-desc"><?php echo esc_html( $merchant_module['desc']  ); ?></div>
									<?php endif; ?>
								</div>

							</a>

						<?php endforeach; ?>
				
					<?php endif; ?>

				<?php endforeach; ?>

			</div>

		</div>

	<?php endif; ?>

</div>

