<?php
/**
 * Plugin Name: Learning Modal Plugin
 * Plugin URI: https://example.com/
 * Description: Displays a closable modal with course topic descriptions before showing the first lesson.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://your-website.com/
 * License: GPL2
 * Text Domain: learning-modal
 * Domain Path: /languages
 */
function my_plugin_enqueue_modal_assets() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style(
        'topic-modal-style',
        $plugin_url . 'assets/css/topic-modal.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'topic-modal-script',
        $plugin_url . 'assets/js/topic-modal.js',
        [],
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'my_plugin_enqueue_modal_assets' );
?>