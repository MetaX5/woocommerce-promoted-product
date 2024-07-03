<?php

/**
 * Plugin Name: WooCommerce Promoted Product
 * Description:
 * Author: Mateusz Minkiewicz
 * Version: 1.0
 * Text Domain: woocommerce-promoted-product
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

/**
 * If this file is called directly, then abort execution.
 */
if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use MM\WoocommercePromotedProduct\Admin\Product_Custom_Fields;
use MM\WoocommercePromotedProduct\Admin\Woocommerce_Custom_Settings;
use MM\WoocommercePromotedProduct\Frontend\Promotion_Div_Shortcode;
use MM\WoocommercePromotedProduct\Cron\ProductPromotionTime;

/**
 * Global constant for text domain
 */
if (!defined('WPP_TEXT_DOMAIN')) {
    define('WPP_TEXT_DOMAIN', 'woocommerce-promoted-product');
}

/**
 * Global constant for plugin directory url
 */
if (!defined('WPP_PLUGIN_DIR')) {
    define('WPP_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

final class Woocommerce_Promoted_Product
{
    public function __construct()
    {
        register_deactivation_hook(__FILE__, 'wpp_deactivate');

        new Product_Custom_Fields();
        new Woocommerce_Custom_Settings();
        new Promotion_Div_Shortcode();
        new ProductPromotionTime();
    }

    /**
     * Remove the scheduled event when deactivating the plugin
     *
     * @return void
     */
    public function wpp_deactivate()
    {
        $timestamp = wp_next_scheduled('wpp_cron_hook');
        wp_unschedule_event($timestamp, 'wpp_cron_hook');
    }
}

new Woocommerce_Promoted_Product();
