jQuery(document).ready(function($) {
    // Handle tab switching
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.tab-content').hide();
        var target = $(this).attr('href');
        $(target).show();
    });

    // Show the first tab by default
    $('#general').show();

    // New functionality for 'All Day' checkbox
    function toggleTimeInputs(checkbox) {
        var dayTimeInputsWrapper = checkbox.closest('.airos-day-schedule').querySelector('.day-time-inputs-wrapper');
        if (checkbox.checked) {
            dayTimeInputsWrapper.style.display = 'none';
        } else {
            dayTimeInputsWrapper.style.display = 'flex';
        }
    }

    var checkboxes = document.querySelectorAll('.all-day-checkbox');
    checkboxes.forEach(function(checkbox) {
        // Initial toggle based on current state
        toggleTimeInputs(checkbox);

        // Attach change event listener
        checkbox.addEventListener('change', function() {
            toggleTimeInputs(checkbox);
        });
    });
});
