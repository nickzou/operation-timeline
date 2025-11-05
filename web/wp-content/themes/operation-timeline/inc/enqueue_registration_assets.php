<?php

/**
 * Enqueue Registration Form Assets
 *
 * Conditionally enqueue scripts for the user registration page
 * Scripts are registered globally in inc/register_theme_js.php
 */

function enqueue_registration_form_assets()
{
    // Only enqueue on the User Registration page template
    if (is_page_template("page-register.php")) {
        wp_enqueue_script("registration-form");
    }
}

add_action("wp_enqueue_scripts", "enqueue_registration_form_assets");
