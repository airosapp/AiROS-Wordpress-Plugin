<?php
/*
Plugin Name: AiROS App
Description: Allow all features of the AiROS App
Version: 1.2.2
Author: AiROS
*/

// Include the library file and check for its existence
if (file_exists(plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php')) {
    require plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php';
}

// Use the correct namespace and class for PUC 5.4
if (class_exists('YahnisElsts\\PluginUpdateChecker\\v5p4\\PucFactory')) {
    $options = get_option('airos_settings');
    $githubToken = isset($options['airos_github_token']) ? $options['airos_github_token'] : '';

    $updateChecker = YahnisElsts\PluginUpdateChecker\v5p4\PucFactory::buildUpdateChecker(
        'https://github.com/airosapp/AiROS-Wordpress-Plugin',
        __FILE__,
        'airos-wordpress-plugin'
    );

    if ($githubToken) {
        $updateChecker->setAuthentication($githubToken);
    }

    $updateChecker->setBranch('main');
}

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

// Function to manually check for updates
function manual_check_for_updates() {
    global $updateChecker;
    if ($updateChecker) {
        $updateChecker->checkForUpdates();
    }
}
add_action('admin_init', 'manual_check_for_updates');

// Add the admin menu and settings page
add_action('admin_menu', 'airos_add_admin_menu');
add_action('admin_init', 'airos_settings_init');

function airos_add_admin_menu() { 
    add_menu_page('AiROS App', 'AiROS App', 'manage_options', 'airos_app', 'airos_options_page');
}

function airos_settings_init() {
    register_setting('airosApp', 'airos_settings');

    // General Section
    add_settings_section(
        'airos_section_general',
        __('General Information', 'wordpress'),
        'airos_settings_section_callback_general',
        'airosApp'
    );

    // Live Chat Section
    add_settings_section(
        'airos_section_live_chat',
        __('Live Chat Settings', 'wordpress'),
        'airos_settings_section_callback_live_chat',
        'airosApp'
    );

    add_settings_field(
        'airos_live_chat_enabled',
        __('Enable Live Chat', 'wordpress'),
        'airos_live_chat_enabled_render',
        'airosApp',
        'airos_section_live_chat'
    );

    add_settings_field(
        'airos_live_chat_url',
        __('Live Chat URL', 'wordpress'),
        'airos_live_chat_url_render',
        'airosApp',
        'airos_section_live_chat'
    );

    add_settings_field(
        'airos_live_chat_color',
        __('Live Chat Button Color', 'wordpress'),
        'airos_live_chat_color_render',
        'airosApp',
        'airos_section_live_chat'
    );

    add_settings_field(
        'airos_live_chat_text',
        __('Live Chat Button Text', 'wordpress'),
        'airos_live_chat_text_render',
        'airosApp',
        'airos_section_live_chat'
    );
}

function airos_settings_section_callback_general() {
    echo __('General settings for the AiROS App.', 'wordpress');
}

function airos_settings_section_callback_live_chat() {
    echo __('Settings for the Live Chat feature.', 'wordpress');
}

function airos_live_chat_enabled_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='checkbox' style='transform: scale(1.5);' name='airos_settings[airos_live_chat_enabled]' <?php checked(isset($options['airos_live_chat_enabled']) ? $options['airos_live_chat_enabled'] : 0, 1); ?> value='1'>
    <?php
}

function airos_live_chat_url_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='text' name='airos_settings[airos_live_chat_url]' value='<?php echo isset($options['airos_live_chat_url']) ? esc_attr($options['airos_live_chat_url']) : ''; ?>' placeholder='https://your-live-chat-url.com' style='background: white;'>
    <?php
}

function airos_live_chat_color_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='color' name='airos_settings[airos_live_chat_color]' value='<?php echo isset($options['airos_live_chat_color']) ? esc_attr($options['airos_live_chat_color']) : '#000000'; ?>' style='background: white;'>
    <?php
}

function airos_live_chat_text_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='text' name='airos_settings[airos_live_chat_text]' value='<?php echo isset($options['airos_live_chat_text']) ? esc_attr($options['airos_live_chat_text']) : 'Chat'; ?>' placeholder='Chat' style='background: white;'>
    <?php
}

function airos_options_page() { 
    ?>
    <div class="wrap airos-wrap">
        <div class="airos-admin-header">
            <img src="https://airosapp.com/wp-content/uploads/2024/03/AiROS-Logo-copy.png" alt="AiROS Logo" class="airos-logo">
            <h2>AiROS App</h2>
        </div>
        <div class="airos-admin-content">
            <form action='options.php' method='post'>
                <?php
                settings_fields('airosApp');
                do_settings_sections('airosApp');
                submit_button();
                ?>
            </form>
        </div>
    </div>

    <style>
    /* Admin panel styles */
    .wrap {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 20px auto;
    }

    .airos-admin-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .airos-logo {
        max-width: 100px;
        height: auto;
    }

    .airos-admin-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    input[type="checkbox"] {
        transform: scale(1.5);
        margin-right: 10px;
    }

    input[type="color"] {
        background: white;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 40px;
		height: 40px;
        box-sizing: border-box;
        margin-bottom: 20px;
    }
		
		input[type="text"], textarea {
        background: white;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 20px;
    }
		
    </style>
    <?php
}

// Enqueue admin scripts for the settings page
add_action('admin_enqueue_scripts', 'airos_admin_scripts');
function airos_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_airos_app') {
        return;
    }
    wp_enqueue_style('airos_admin_styles', plugin_dir_url(__FILE__) . 'assets/css/admin-styles.css');
    wp_enqueue_script('airos_admin_script', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array('jquery'), null, true);
}

// Enqueue front-end scripts for the live chat button and modal
add_action('wp_enqueue_scripts', 'airos_enqueue_live_chat_scripts');
function airos_enqueue_live_chat_scripts() {
    wp_enqueue_style('airos_live_chat_styles', plugin_dir_url(__FILE__) . 'assets/css/live-chat-styles.css');
    wp_enqueue_script('airos_live_chat_script', plugin_dir_url(__FILE__) . 'assets/js/live-chat-script.js', array('jquery'), null, true);
}

// Add HTML for the Floating Button and Modal
function airos_live_chat_button() {
    $options = get_option('airos_settings');
    if (isset($options['airos_live_chat_enabled']) && $options['airos_live_chat_enabled'] == 1) {
        $liveChatUrl = isset($options['airos_live_chat_url']) ? $options['airos_live_chat_url'] : '';
        $liveChatColor = isset($options['airos_live_chat_color']) ? $options['airos_live_chat_color'] : '#000000';
        $liveChatText = isset($options['airos_live_chat_text']) ? $options['airos_live_chat_text'] : 'Chat';

        if ($liveChatUrl) {
            ?>
            <button id="airos-live-chat-button" style="background-color: <?php echo esc_attr($liveChatColor); ?>;"><?php echo esc_html($liveChatText); ?></button>
            <div id="airos-live-chat-modal">
                <iframe src="<?php echo esc_url($liveChatUrl); ?>" width="500" height="500" allow="camera; microphone; autoplay"></iframe>
            </div>
            <?php
        }
    }
}
add_action('wp_footer', 'airos_live_chat_button');
