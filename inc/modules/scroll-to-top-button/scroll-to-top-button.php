<?php

function merchant_scroll_to_top_buttton() {

	if ( Merchant_Modules::is_module_active( 'scroll-to-top-button' ) ) {

		$type = Merchant_Option::get( 'scroll-to-top-button', 'type', 'icon' );

		$position = Merchant_Option::get( 'scroll-to-top-button', 'position', 'right' );
	
		$visibility = Merchant_Admin_Options::get( 'scroll-to-top-button', 'visibility', 'all' );

		echo '<div class="merchant-scroll-to-top-button merchant-position-'. esc_attr( $position ) .' merchant-visibility-'. esc_attr( $visibility ) .'">';

			if ( $type === 'text+icon' ) {

				$text = Merchant_Option::get( 'scroll-to-top-button', 'text', 'Back to top' );

				echo '<span>'. esc_html( $text ) .'</span>';

			}

			$arrow = Merchant_Option::get( 'scroll-to-top-button', 'icon', 'arrow-1' );

			switch ( $arrow ) {

				case 'arrow-1':
					echo '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15L12 8L19 15" stroke-width="1.5" stroke-linejoin="round"></path></svg>';
				break;

				case 'arrow-2':
					echo '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 15l7-7 7 7" stroke-width="3" stroke-linejoin="round"></path></svg>';
				break;

				case 'arrow-3':
					echo '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12l5.5-5.5m0 0L18 12m-5.5-5.5V19" stroke-width="1.5" stroke-linejoin="round"></path></svg>';
				break;

				case 'arrow-4':
					echo '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12l5.5-5.5m0 0L18 12m-5.5-5.5V19" stroke-width="3" stroke-linejoin="round"></path></svg>';
				break;

			}

		echo '</div>';

	}

}
add_action( 'wp_footer', 'merchant_scroll_to_top_buttton' );
