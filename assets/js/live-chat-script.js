// live-chat-script.js

(function($) {
    $(document).ready(function() {
        // Open modal when button is clicked
        $('.airos-chat-button-icon, .airos-chat-button-text').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation(); // Prevent event bubbling

            var $modal = $('.airos-modal');
            var $button = $(this);
            var $icon = $button.find('.chat-icon');
            var $text = $button.find('.chat-text');
            var $close = $button.find('.chat-close');

            $modal.toggleClass('open');
            $button.toggleClass('active');

            if ($modal.hasClass('open')) {
                $icon.hide();
                $text.hide();
                $close.show();
            } else {
                $icon.show();
                $text.show();
                $close.hide();
            }
        });

        // Close modal when 'X' is clicked inside the modal
        $('.airos-modal-close').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation(); // Prevent event bubbling

            var $modal = $(this).closest('.airos-modal');
            var $button = $('.airos-chat-button-icon.active, .airos-chat-button-text.active');
            var $icon = $button.find('.chat-icon');
            var $text = $button.find('.chat-text');
            var $close = $button.find('.chat-close');

            $modal.removeClass('open');
            $button.removeClass('active');

            $icon.show();
            $text.show();
            $close.hide();
        });

        // Close modal when clicking outside the modal content
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.airos-modal iframe, .airos-chat-button-icon, .airos-chat-button-text').length) {
                var $modal = $('.airos-modal.open');
                var $button = $('.airos-chat-button-icon.active, .airos-chat-button-text.active');
                var $icon = $button.find('.chat-icon');
                var $text = $button.find('.chat-text');
                var $close = $button.find('.chat-close');

                $modal.removeClass('open');
                $button.removeClass('active');

                $icon.show();
                $text.show();
                $close.hide();
            }
        });
    });
})(jQuery);
