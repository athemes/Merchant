<?php
/**
 * SVG icons used in Merchant.
 *
 * @package Merchant
 */

if ( ! class_exists( 'Merchant_SVG_Icons' ) ) {

	/**
	 * Get a svg icon
	 */
	class Merchant_SVG_Icons {

		public static function get_svg_icon( $icon ) {
			$icons = self::$svg_icons;

			$svg_code = $icons[ $icon ];

			return $svg_code;
		}

		public static $svg_icons = array(
			'check1' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"></path></svg>',
			'check2' => '<svg class="feather feather-check-circle" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
			'check3' => '<svg class="feather feather-check-square" fill="none" height="24" stroke="#212121" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',

			'heart1' => '<svg width="17" height="17" viewBox="0 0 25 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.8213 2.50804L13.8216 2.5078C16.1161 0.140222 19.7976 -0.212946 22.2492 1.87607C25.093 4.30325 25.2444 8.66651 22.6933 11.2992L22.6932 11.2993L13.245 21.055C13.245 21.0551 13.245 21.0551 13.2449 21.0551C12.8311 21.4822 12.1652 21.4822 11.7514 21.0551C11.7513 21.0551 11.7513 21.0551 11.7513 21.055L2.30334 11.2995C-0.243225 8.66684 -0.0918835 4.30344 2.75181 1.8762C5.20368 -0.213127 8.88985 0.140465 11.1793 2.50744L11.1799 2.50804L12.1418 3.49925L12.5006 3.86899L12.8594 3.49925L13.8213 2.50804Z" stroke-width="1" stroke="#212121" fill="transparent"/></svg>',
			'heart2' => '<svg class="stroke-based" width="20" height="20" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.5 3C12.8905 3 11.0082 4.70299 10 5.8C8.99175 4.70299 7.1095 3 5.5 3C2.651 3 1 5.07403 1 7.71371C1 12.1 10 17 10 17C10 17 19 12.1 19 7.9C19 5.26032 17.349 3 14.5 3Z" stroke-width="1"/><path d="M14 9.5L11.5 12" stroke-width="1" stroke-linecap="round" stroke="#212121" fill="transparent"/></svg>'
		);
	}
}
