// JavaScript to handle the live chat button click
jQuery(document).ready(function($) {
    $('#airos-live-chat-button').click(function() {
        var $modal = $('#airos-live-chat-modal');
        var $button = $(this);

        $modal.toggleClass('open');
        $button.toggleClass('active');

        if ($modal.hasClass('open')) {
            $button.text('X'); // Change button text to 'X' when open
        } else {
            $button.text('Chat'); // Change button text back to 'Chat' when closed
        }
    });
});
