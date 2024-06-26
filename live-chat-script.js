// Create live-chat-script.js file
$live_chat_script = <<<EOT
jQuery(document).ready(function($) {
    $('#airos-live-chat-button').click(function() {
        $('#airos-live-chat-modal').toggle();
    });
});
EOT;

file_put_contents(plugin_dir_path(__FILE__) . 'live-chat-script.js', $live_chat_script);
