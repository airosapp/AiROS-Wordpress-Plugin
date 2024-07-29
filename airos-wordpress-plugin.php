<?php
/*
Plugin Name: AiROS App
Description: Allow all features of the AiROS App
Version: 1.3.4
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
        'airos_live_chat_button_type',
        __('Live Chat Button Type', 'wordpress'),
        'airos_live_chat_button_type_render',
        'airosApp',
        'airos_section_live_chat'
    );

    // New fields for icon height and width
    add_settings_field(
        'airos_live_chat_icon_width',
        __('Icon Button Width (px)', 'wordpress'),
        'airos_live_chat_icon_width_render',
        'airosApp',
        'airos_section_live_chat'
    );

    add_settings_field(
        'airos_live_chat_icon_height',
        __('Icon Button Height (px)', 'wordpress'),
        'airos_live_chat_icon_height_render',
        'airosApp',
        'airos_section_live_chat'
    );

    add_settings_field(
        'airos_live_chat_color',
        __('Text Button Color', 'wordpress'),
        'airos_live_chat_color_render',
        'airosApp',
        'airos_section_live_chat'
    );
    
    add_settings_field(
        'airos_live_chat_font_color',
        __('Text Button Font Color', 'wordpress'),
        'airos_live_chat_font_color_render',
        'airosApp',
        'airos_section_live_chat'
    );

    add_settings_field(
        'airos_live_chat_text',
        __('Text for the Button', 'wordpress'),
        'airos_live_chat_text_render',
        'airosApp',
        'airos_section_live_chat'
    );
	
	    // Scheduler fields
    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    foreach ($days as $day) {
        add_settings_field(
            'airos_live_chat_schedule_' . strtolower($day),
            __($day . ' Schedule', 'wordpress'),
            'airos_live_chat_schedule_render',
            'airosApp',
            'airos_section_live_chat',
            ['day' => $day]
        );
	    }

    // Timezone field
    add_settings_field(
        'airos_live_chat_timezone',
        __('Timezone', 'wordpress'),
        'airos_live_chat_timezone_render',
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

function airos_live_chat_icon_width_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='number' name='airos_settings[airos_live_chat_icon_width]' value='<?php echo isset($options['airos_live_chat_icon_width']) ? esc_attr($options['airos_live_chat_icon_width']) : '40'; ?>' style='background: white; width: 60px;' min='10' max='100'>
    <?php
}

function airos_live_chat_icon_height_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='number' name='airos_settings[airos_live_chat_icon_height]' value='<?php echo isset($options['airos_live_chat_icon_height']) ? esc_attr($options['airos_live_chat_icon_height']) : '40'; ?>' style='background: white; width: 60px;' min='10' max='100'>
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

function airos_live_chat_font_color_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='color' name='airos_settings[airos_live_chat_font_color]' value='<?php echo isset($options['airos_live_chat_font_color']) ? esc_attr($options['airos_live_chat_font_color']) : '#ffffff'; ?>' style='background: white;'>
    <?php
}

function airos_live_chat_text_render() {
    $options = get_option('airos_settings');
    ?>
    <input type='text' name='airos_settings[airos_live_chat_text]' value='<?php echo isset($options['airos_live_chat_text']) ? esc_attr($options['airos_live_chat_text']) : 'Chat'; ?>' placeholder='Chat' style='background: white;'>
    <?php
}

function airos_live_chat_button_type_render() {
    $options = get_option('airos_settings');
    $selected = isset($options['airos_live_chat_button_type']) ? $options['airos_live_chat_button_type'] : 'text';
    ?>
    <select name='airos_settings[airos_live_chat_button_type]'>
        <option value='text' <?php selected($selected, 'text'); ?>>Text</option>
        <option value='icon' <?php selected($selected, 'icon'); ?>>Icon</option>
    </select>
    <?php
}

function airos_live_chat_schedule_render($args) {
    $options = get_option('airos_settings');
    $day = $args['day'];
    $day_lower = strtolower($day);
    ?>
    <div class="airos-day-schedule">
        <div class="day-header">
            <label class="all-day-container">
                <input type="checkbox" class="all-day-checkbox" name="airos_settings[airos_live_chat_schedule][<?php echo $day_lower; ?>][all_day]" value="1" <?php checked(isset($options['airos_live_chat_schedule'][$day_lower]['all_day']) ? $options['airos_live_chat_schedule'][$day_lower]['all_day'] : 0, 1); ?>>
                <span class="allday">All Day</span>
            </label>
        </div>
        <div class="day-time-inputs-wrapper">
            <div class="day-time-inputs">
                <div class="day-time-group">
                    <label><?php echo $day; ?> Start Time:</label>
                    <input type="time" class="day-time-input" name="airos_settings[airos_live_chat_schedule][<?php echo $day_lower; ?>][start_time]" value="<?php echo esc_attr($options['airos_live_chat_schedule'][$day_lower]['start_time'] ?? ''); ?>">
                </div>
                <div class="day-time-group">
                    <label><?php echo $day; ?> End Time:</label>
                    <input type="time" class="day-time-input" name="airos_settings[airos_live_chat_schedule][<?php echo $day_lower; ?>][end_time]" value="<?php echo esc_attr($options['airos_live_chat_schedule'][$day_lower]['end_time'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
    <?php
}


function airos_live_chat_timezone_render() {
    $options = get_option('airos_settings');
    $common_timezones = [
        'America/Los_Angeles' => 'Pacific Time (US & Canada)',
        'America/Denver' => 'Mountain Time (US & Canada)',
        'America/Chicago' => 'Central Time (US & Canada)',
        'America/New_York' => 'Eastern Time (US & Canada)',
        'America/Halifax' => 'Atlantic Time (Canada)',
        'America/St_Johns' => 'Newfoundland Time',
        'America/Anchorage' => 'Alaska Time',
        'America/Adak' => 'Hawaii-Aleutian Time',
        'GMT' => 'Greenwich Mean Time',
        'CET' => 'Central European Time',
        'EET' => 'Eastern European Time',
        'Australia/Sydney' => 'Australian Eastern Time',
        'Australia/Adelaide' => 'Australian Central Time'
    ];
    ?>
    <label for="airos_live_chat_timezone">Select Timezone:</label>
    <select name="airos_settings[airos_live_chat_timezone]" id="airos_live_chat_timezone">
        <?php foreach ($common_timezones as $timezone => $label) : ?>
            <option value="<?php echo $timezone; ?>" <?php selected($options['airos_live_chat_timezone'] ?? '', $timezone); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}


function airos_options_page() {
    include plugin_dir_path(__FILE__) . 'admin-page-template.php';
}

// Enqueue admin scripts for the settings page
add_action('admin_enqueue_scripts', 'airos_admin_scripts');
function airos_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_airos_app') {
        return;
    }

    // Correct CSS path
    $css_file_url = plugin_dir_url(__FILE__) . 'assets/css/admin-styles.css';
    $css_file_path = plugin_dir_path(__FILE__) . 'assets/css/admin-styles.css';
    $css_version = filemtime($css_file_path);

    wp_register_style('airos_admin_styles', $css_file_url, [], $css_version);
    wp_enqueue_style('airos_admin_styles');

    wp_enqueue_script('airos_admin_script', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array('jquery'), null, true);
}

// Enqueue front-end scripts and styles
function airos_enqueue_live_chat_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');

    // Correct CSS path
    $css_file_url = plugin_dir_url(__FILE__) . 'assets/css/live-chat-styles.css';
    $css_file_path = plugin_dir_path(__FILE__) . 'assets/css/live-chat-styles.css';
    $css_version = filemtime($css_file_path);

    wp_register_style('airos_live_chat_styles', $css_file_url, [], $css_version);
    wp_enqueue_style('airos_live_chat_styles');

    // Make sure jQuery is enqueued
    wp_enqueue_script('jquery');

    // Enqueue Bootstrap JS with jQuery as a dependency
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), null, true);

    // Enqueue custom live chat script with jQuery as a dependency
    wp_register_script('airos_live_chat_script', plugin_dir_url(__FILE__) . 'assets/js/live-chat-script.js', array('jquery'), filemtime(plugin_dir_path(__FILE__) . 'assets/js/live-chat-script.js'), true);
    wp_enqueue_script('airos_live_chat_script');
}
add_action('wp_enqueue_scripts', 'airos_enqueue_live_chat_scripts');

// Add HTML for the Floating Button and Modal

function airos_live_chat_button() {
    $options = get_option('airos_settings');
    if (isset($options['airos_live_chat_enabled']) && $options['airos_live_chat_enabled'] == 1) {
        $liveChatUrl = isset($options['airos_live_chat_url']) ? $options['airos_live_chat_url'] : '';
        $liveChatColor = isset($options['airos_live_chat_color']) ? $options['airos_live_chat_color'] : '#000000';
        $liveChatFontColor = isset($options['airos_live_chat_font_color']) ? $options['airos_live_chat_font_color'] : '#ffffff';
        $liveChatButtonType = isset($options['airos_live_chat_button_type']) ? $options['airos_live_chat_button_type'] : 'text';
        $liveChatText = isset($options['airos_live_chat_text']) ? $options['airos_live_chat_text'] : 'Chat';
        $liveChatIconWidth = isset($options['airos_live_chat_icon_width']) ? esc_attr($options['airos_live_chat_icon_width']) : '40';
        $liveChatIconHeight = isset($options['airos_live_chat_icon_height']) ? esc_attr($options['airos_live_chat_icon_height']) : '40';
        $timezone = isset($options['airos_live_chat_timezone']) ? $options['airos_live_chat_timezone'] : 'UTC';

        // Set the timezone
        date_default_timezone_set($timezone);

        // Get current time and day based on selected timezone
        $currentDay = strtolower(date('l'));
        $currentTime = date('H:i');
        $allDay = isset($options['airos_live_chat_schedule'][$currentDay]['all_day']) ? $options['airos_live_chat_schedule'][$currentDay]['all_day'] : 0;
        $startTime = $options['airos_live_chat_schedule'][$currentDay]['start_time'] ?? '';
        $endTime = $options['airos_live_chat_schedule'][$currentDay]['end_time'] ?? '';

        $isScheduledTime = $allDay || ($currentTime >= $startTime && $currentTime <= $endTime);

        if ($liveChatUrl && $isScheduledTime) {
            if ($liveChatButtonType === 'icon') {
                ?>
                <button id="airos-live-chat-button-icon" style="color: <?php echo esc_attr($liveChatFontColor); ?>;">
                    <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/live-chat-icon.png'; ?>" alt="Chat Icon" style="width: <?php echo $liveChatIconWidth; ?>px; height: <?php echo $liveChatIconHeight; ?>px;" class="chat-icon">
                    <span class="chat-close" style="display: none; width: <?php echo $liveChatIconWidth; ?>px; height: <?php echo $liveChatIconHeight; ?>px; line-height: <?php echo $liveChatIconHeight; ?>px; text-align: center;">X</span>
                </button>
                <?php
            } else {
                ?>
                <button id="airos-live-chat-button-text" style="background-color: <?php echo esc_attr($liveChatColor); ?>; color: <?php echo esc_attr($liveChatFontColor); ?>;">
                    <span class="chat-text"><?php echo esc_html($liveChatText); ?></span>
                    <span class="chat-close" style="display: none;">X</span>
                </button>
                <?php
            }
            ?>
            <div id="airos-live-chat-modal">
                <iframe src="<?php echo esc_url($liveChatUrl); ?>" width="500" height="500" allow="camera; microphone; autoplay"></iframe>
            </div>
            <?php
        }
    }
}



add_action('wp_footer', 'airos_live_chat_button');
?>

