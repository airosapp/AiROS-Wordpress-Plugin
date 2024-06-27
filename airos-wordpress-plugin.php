<?php
/*
Plugin Name: AiROS App
Description: Allow all features of the AiROS App
Version: 1.2.1
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

    // General Information Section
    add_settings_section(
        'airos_section',
        __('Settings for AiROS App', 'wordpress'),
        'airos_settings_section_callback',
        'airosApp'
    );

    add_settings_field(
        'airos_description',
        __('App Description', 'wordpress'),
        'airos_description_render',
        'airosApp',
        'airos_section'
    );

    add_settings_field(
        'airos_github_token',
        __('GitHub API Token', 'wordpress'),
        'airos_github_token_render',
        'airosApp',
        'airos_section'
    );

    // Live Chat Section
    add_settings_section(
        'airos_section_live_chat',
        __('Live Chat Settings', 'wordpress'),
        'airos_settings_section_callback',
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
}

function airos_description_render() {
    $options = get_option('airos_settings');
    ?>
    <textarea name='airos_settings[airos_description]' rows='5' style='width: 100%; background: white;'><?php echo isset($options['airos_description']) ? esc_attr($options['airos_description']) : ''; ?></textarea>
    <?php
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

function airos_github_token_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='text' name='airos_settings[airos_github_token]' value='<?php echo isset($options['airos_github_token']) ? esc_attr($options['airos_github_token']) : ''; ?>' placeholder='GitHub API Token' style='background: white;'>
    <?php
}

function airos_settings_section_callback() {
    echo __('This section description', 'wordpress');
}

function airos_options_page() { 
    ?>
    <div class="wrap">
        <div class="airos-admin-header">
            <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/logo.png'; ?>" alt="AiROS Logo" class="airos-logo">
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
    <?php
}

// Enqueue admin scripts and styles for the settings page
add_action('admin_enqueue_scripts', 'airos_admin_scripts');
function airos_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_airos_app') {
        return;
    }
    error_log('Enqueuing admin styles.');
    wp_enqueue_style('airos_admin_styles', plugin_dir_url(__FILE__) . 'assets/css/admin-styles.css');
    wp_enqueue_script('airos_admin_script', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array('jquery'), null, true);
}

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

        if ($liveChatUrl) {
            ?>
            <button id="airos-live-chat-button">Chat</button>
            <div id="airos-live-chat-modal">
                <iframe src="<?php echo esc_url($liveChatUrl); ?>" width="500" height="500" allow="camera; microphone; autoplay"></iframe>
            </div>
            <?php
        }
    }
}
add_action('wp_footer', 'airos_live_chat_button');
?>
