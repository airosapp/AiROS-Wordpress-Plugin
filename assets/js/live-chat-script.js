jQuery(document).ready(function($) {
    $('#airos-live-chat-button').on('click', function() {
        $('#airos-live-chat-modal').toggle();
    });

    // Hide the chat modal when clicking outside of it
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#airos-live-chat-modal, #airos-live-chat-button').length) {
            $('#airos-live-chat-modal').hide();
        }
    });
});
