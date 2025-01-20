<?php

/**
 * Plugin Installer.
 * This class is responsible for installing and activating plugins. This could be from wp.org and external sources.
 * 
 * @package Merchant
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Merchant_Plugin_Installer' ) ) {

    class Merchant_Plugin_Installer {

        /**
         * Constructor.
         * 
         */
        public function __construct() {
            add_action( 'admin_init', array( $this, 'init' ) );
        }

        /**
         * Initialize the class.
         * 
         * @return void
         */
        public function init() {
            if ( ! is_admin() ) {
                return;
            }

            if ( ! current_user_can( 'install_plugins' ) ) {
                return;
            }

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_ajax_merchant_install_plugin', array( $this, 'install_plugin' ) );
            add_action( 'wp_ajax_merchant_install_external_plugin', array( $this, 'install_external_plugin' ) );
        }

        /**
         * Enqueue scripts.
         * 
         * @return void
         */
        public function enqueue_scripts() {
            wp_enqueue_script( 'merchant-plugin-installer', MERCHANT_URI . '/assets/js/admin/plugin-installer.min.js', array( 'jquery' ), MERCHANT_VERSION, true );

            wp_localize_script( 'merchant-plugin-installer', 'merchantPluginInstallerConfig', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'merchant_plugin_installer_nonce' ),
                'i18n' => array(
                    'defaultText' => esc_html__( 'Install and Activate', 'merchant' ),
                    'installingText' => esc_html__( 'Installing...', 'merchant' ),
                    'activatingText' => esc_html__( 'Activating...', 'merchant' ),
                ),
            ) );
        }

        /**
         * Install plugin.
         * This method is responsible for installing plugins from the wp.org.
         * 
         * @return void
         */
        public function install_plugin() {
            // TODO: Implement this method.
        }

        /**
         * Install external plugin.
         * This method is responsible for installing plugins from external sources.
         * 
         * @return void
         */
        public function install_external_plugin() {
            check_ajax_referer( 'merchant_plugin_installer_nonce', 'nonce' );

            if ( ! current_user_can( 'install_plugins' ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to install plugins.', 'merchant' ) ) );
            }

            if ( empty( $_POST['url'] ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Plugin URL is required.', 'merchant' ) ) );
            }

            $url = esc_url_raw( $_POST['url'] );
            
            if ( empty( $_POST['plugin_name'] ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Plugin name is required.', 'merchant' ) ) );
            }

            $plugin_name = sanitize_text_field( wp_unslash( $_POST['plugin_name'] ) );

            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            require_once MERCHANT_DIR . 'admin/classes/class-merchant-silent-upgrader-skin.php';
            
            $upgrader = new Plugin_Upgrader( new Merchant_Silent_Upgrader_Skin() );
            $install = $upgrader->install( $url );       

            if ( is_wp_error( $install ) ) {
                wp_send_json_error( array( 'message' => $install->get_error_message() ) );
            }

            $activate = activate_plugin( $plugin_name );

            if ( is_wp_error( $activate ) ) {
                wp_send_json_error( array( 'message' => $activate->get_error_message() ) );
            }

            wp_send_json_success( 
                array( 
                    'message' => esc_html__( 'Plugin activated successfully.', 'merchant' ), 
                ) 
            );
        }
    }

    new Merchant_Plugin_Installer();
}