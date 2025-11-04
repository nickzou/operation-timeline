<?php

/**
 * Enqueue Registration Form Assets
 *
 * Load registration-form script only on the User Registration page template
 */

function enqueue_registration_form_script()
{
    // Only enqueue on the User Registration page template
    if (is_page_template("page-register.php")) {
        // Enqueue Alpine.js Simple Validate plugin from CDN
        wp_enqueue_script(
            "alpine-simple-validate",
            "https://unpkg.com/@colinaut/alpinejs-plugin-simple-validate@1/dist/alpine.validate.min.js",
            [],
            "1.0.0",
            true, // Load in footer, before Alpine.js
        );

        // Enqueue our registration form script
        wp_enqueue_script(
            "registration-form",
            get_template_directory_uri() . "/js/registration-form.js",
            ["alpine-simple-validate"], // Depends on validation plugin
            filemtime(get_template_directory() . "/js/registration-form.js"),
            true, // Load in footer
        );
    }
}

add_action("wp_enqueue_scripts", "enqueue_registration_form_script");
