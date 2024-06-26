<?php
/*
Plugin Name: AiROS App
Description: Allow all features of the AiROS App
Version: 1.1.1
Author: AiROS
*/

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', plugin_dir_path(__FILE__) . 'error_log.txt');

// Log the loading of the plugin
error_log('AiROS App plugin is being loaded.');

// Include the library file and check for its existence
if (file_exists(plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php')) {
    require plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php';
    error_log('Plugin Update Checker included successfully.');
} else {
    error_log('Plugin Update Checker file not found.');
}

// Use the correct namespace and class for PUC 5.4
if (class_exists('YahnisElsts\\PluginUpdateChecker\\v5p4\\PucFactory')) {
    // Set up the update checker
    $updateChecker = YahnisElsts\PluginUpdateChecker\v5p4\PucFactory::buildUpdateChecker(
        'https://github.com/airosapp/AiROS-Wordpress-Plugin', // URL of the repository
        __FILE__, // Full path to the main plugin file
        'airos-wordpress-plugin' // Plugin slug
    );
    error_log('Update checker set up successfully.');

    // Optional: Set the branch that contains the stable release
    $updateChecker->setBranch('main');
} else {
    error_log('Puc_v5p4_Factory class not found.');
}

// Register Yoast SEO meta fields in REST API
function register_yoast_seo_meta_fields() {
    error_log('Registering Yoast SEO meta fields.');
    register_post_meta('post', '_yoast_wpseo_title', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
    register_post_meta('post', '_yoast_wpseo_metadesc', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
    error_log('Yoast SEO meta fields registered.');
}
add_action('rest_api_init', 'register_yoast_seo_meta_fields');

error_log('AiROS App plugin setup completed.');

// Function to manually check for updates
function manual_check_for_updates() {
    global $updateChecker;
    if ($updateChecker) {
        $updateChecker->checkForUpdates();
        error_log('Manual check for updates initiated.');
    } else {
        error_log('UpdateChecker is not set.');
    }
}
add_action('admin_init', 'manual_check_for_updates');

// Log the result of the update check
add_filter('puc_manual_check_result-airos-wordpress-plugin', function($update, $result) {
    error_log('Update check result: ' . print_r($result, true));
    return $update;
}, 10, 2);

error_log('Manual check for updates function added.');
