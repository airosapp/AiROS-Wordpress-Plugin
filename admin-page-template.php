<!-- admin-page-template.php -->
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
