// JavaScript to handle the live chat button click
jQuery(document).ready(function($) {
    $('#airos-live-chat-button-icon, #airos-live-chat-button-text').click(function(event) {
        event.preventDefault();    // Prevents the default action (if necessary)
        event.stopPropagation();   // Stops the event from bubbling up the DOM tree

        var $modal = $('#airos-live-chat-modal');
        var $button = $(this);
        var $icon = $button.find('.chat-icon');
        var $text = $button.find('.chat-text');
        var $close = $button.find('.chat-close');

        $modal.toggleClass('open');
        $button.toggleClass('active');

        if ($modal.hasClass('open')) {
            $icon.hide();
            $text.hide();
            $close.css('display', 'flex'); // Show the close button as a flexbox item
            $modal.css({
                'opacity': '1',
                'visibility': 'visible'
            });
        } else {
            $icon.show();
            $text.show();
            $close.hide();
            $modal.css({
                'opacity': '0',
                'visibility': 'hidden'
            });
        }
    });
});
