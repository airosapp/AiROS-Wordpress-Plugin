<?php
/*
Plugin Name: AiROS App
Description: Allow all features of the AiROS App
Version: 1.1.2
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
        'single' include 'single' => true,
        'type' => 'string',
    ));
    error_log('Yoast SEO meta fields registered.');
}
add_action('rest_api_init', 'register_yoast_seo_meta_fields');

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

// Add the admin menu and settings page
add_action('admin_menu', 'airos_add_admin_menu');
add_action('admin_init', 'airos_settings_init');

function airos_add_admin_menu() { 
    add_menu_page('AiROS App', 'AiROS App', 'manage_options', 'airos_app', 'airos_options_page');
}

function airos_settings_init() { 
    register_setting('airosApp', 'airos_settings');

    add_settings_section(
        'airos_section', 
        __('Settings for AiROS App', 'wordpress'), 
        'airos_settings_section_callback', 
        'airosApp'
    );
}

function airos_settings_section_callback() { 
    echo __('This section description', 'wordpress');
}

function airos_options_page() { 
    ?>
    <div class="wrap">
        <h2>AiROS App</h2>
        <h2 class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active">General Information</a>
            <a href="#livechat" class="nav-tab">Live Chat</a>
        </h2>
        <div id="general" class="tab-content" style="display: block;">
            <form action='options.php' method='post'>
                <?php
                settings_fields('airosApp');
                do_settings_sections('airosApp');
                submit_button();
                ?>
            </form>
        </div>
        <div id="livechat" class="tab-content" style="display: none;">
            <h3>Live Chat</h3>
            <p>This section will contain the live chat settings.</p>
        </div>
    </div>
    <?php
}

// Enqueue admin scripts and styles for the live chat button and modal
add_action('wp_enqueue_scripts', 'airos_enqueue_live_chat_scripts');
function airos_enqueue_live_chat_scripts() {
    wp_enqueue_style('airos_live_chat_styles', plugin_dir_url(__FILE__) . 'live-chat-styles.css');
    wp_enqueue_script('airos_live_chat_script', plugin_dir_url(__FILE__) . 'live-chat-script.js', array('jquery'), null, true);
}

// Create live-chat-styles.css file
$live_chat_styles = <<<EOT
#airos-live-chat-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #0073aa;
    color: white;
    border: none;
    border-radius: 50%;
    padding: 15px;
    cursor: pointer;
    z-index: 1000;
}

#airos-live-chat-modal {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 300px;
    height: 400px;
    border: 1px solid #0073aa;
    border-radius: 5px;
    background-color: white;
    z-index: 1000;
    box-shadow: 

