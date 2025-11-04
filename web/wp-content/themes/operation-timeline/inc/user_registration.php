<?php

/**
 * Custom User Registration
 *
 * Orchestrates user registration by loading the necessary functions
 * and registering AJAX handlers
 */

// Load registration functions
require_once __DIR__ . '/handle_custom_user_registration.php';

// Register AJAX handlers only if WordPress is loaded
if (function_exists("add_action")) {
    // For logged-out users (most registration scenarios)
    add_action("wp_ajax_nopriv_custom_user_registration", "handle_custom_user_registration");

    // For logged-in users (in case they try to register while logged in)
    add_action("wp_ajax_custom_user_registration", "handle_custom_user_registration");
}
