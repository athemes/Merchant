<?php

/**
 * Silent Upgrader Skin.
 * The main purpose of this class is to suppress the output of the upgrader.
 * 
 * @package Merchant
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

if ( ! class_exists( 'Merchant_Silent_Upgrader_Skin' ) ) {
    class Merchant_Silent_Upgrader_Skin extends WP_Upgrader_Skin {
        public function header() {}
        public function footer() {}
        public function error( $errors ) {}
        public function feedback( $feedback, ...$args ) {}
    }
}
