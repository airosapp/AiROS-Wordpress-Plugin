<?php
/*
Plugin Name: AiROS App
Description: Allow all features of the AiROS App
Version: 1.0
Author: AiROS
*/

// Include the library file
require plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php';

// Set up the update checker
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/airosapp/yoast-seo-rest-api', // URL of the repository
    __FILE__, // Full path to the main plugin file
    'yoast-seo-rest-api' // Plugin slug
);

// Optional: Set the branch that contains the stable release
$updateChecker->setBranch('main');

// Register Yoast SEO meta fields in REST API
function register_yoast_seo_meta_fields() {
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
}
add_action('rest_api_init', 'register_yoast_seo_meta_fields');
