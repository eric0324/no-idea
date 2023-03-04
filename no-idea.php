<?php
/**
 * Plugin Name:       No Idea
 * Plugin URI:        https://ericwu.asia/plugins/no0-idea
 * Description:       Easily and quickly produce content through OpenAI.
 * Requires at least: 6.1
 * Requires PHP:      7.1
 * Author:            Eric Wu
 * Author URI:        https://ericwu.asia/
 * Version:           1.0.0
 * Text Domain:       no-idea
 */

define('NO_IDEA_PLUGIN_DIR', plugin_dir_path(__FILE__));

const NO_IDEA_OPTION_PREFIX = 'no_idea_';

require_once NO_IDEA_PLUGIN_DIR . 'includes/View/Admin/NoIdeaHomeView.php';
require_once NO_IDEA_PLUGIN_DIR . 'includes/View/Admin/NoIdeaPostView.php';
require_once NO_IDEA_PLUGIN_DIR . 'includes/View/Admin/NoIdeaProductView.php';
require_once NO_IDEA_PLUGIN_DIR . 'includes/View/Admin/NoIdeaSettingView.php';

require_once NO_IDEA_PLUGIN_DIR . 'includes/Service/NoIdeaOpenAIService.php';

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

/**
 * Registration Options Group
 */
register_setting( NO_IDEA_OPTION_PREFIX . 'basic', NO_IDEA_OPTION_PREFIX . 'basic' );

$no_idea_home_view = new NoIdeaHomeView();
$no_idea_post_view = new NoIdeaPostView();
$no_idea_product_view = new NoIdeaProductView();
$no_idea_setting_view = new NoIdeaSettingView();
add_action( 'admin_menu', [ $no_idea_home_view, 'no_idea_home_admin' ] );
add_action( 'admin_menu', [ $no_idea_post_view, 'no_idea_post_admin' ] );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    add_action( 'admin_menu', [ $no_idea_product_view, 'no_idea_product_admin' ] );
}

add_action( 'admin_menu', [ $no_idea_setting_view, 'no_idea_setting_admin' ] );

$no_idea_open_ai_service = new NoIdeaOpenAIService();
add_action( 'admin_post_generate_post', [ $no_idea_open_ai_service, 'generate_post_content' ] );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    add_action( 'admin_post_generate_product', [ $no_idea_open_ai_service, 'generate_product_content' ] );
}