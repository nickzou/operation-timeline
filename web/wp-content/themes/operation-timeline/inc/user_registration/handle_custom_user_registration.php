<?php

/**
 * User Registration Handler Function
 *
 * Processes AJAX user registration with validation
 */

/**
 * Process user registration
 */
function handle_custom_user_registration()
{
    // Verify nonce for security
    if (
        !isset($_POST["registration_nonce"]) ||
        !wp_verify_nonce($_POST["registration_nonce"], "custom_registration_nonce")
    ) {
        wp_send_json_error([
            "message" => "Security verification failed. Please refresh and try again.",
        ]);
        return;
    }

    // Sanitize and validate input
    $username = sanitize_user($_POST["username"] ?? "");
    $email = sanitize_email($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $first_name = sanitize_text_field($_POST["first_name"] ?? "");
    $last_name = sanitize_text_field($_POST["last_name"] ?? "");

    // Validation errors array
    $errors = [];

    // Validate each field using dedicated functions
    $username_error = validate_registration_username($username);
    if ($username_error !== null) {
        $errors["username"] = $username_error;
    }

    $email_error = validate_registration_email($email);
    if ($email_error !== null) {
        $errors["email"] = $email_error;
    }

    $password_error = validate_registration_password($password);
    if ($password_error !== null) {
        $errors["password"] = $password_error;
    }

    $confirm_password_error = validate_registration_confirm_password($password, $confirm_password);
    if ($confirm_password_error !== null) {
        $errors["confirm_password"] = $confirm_password_error;
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        wp_send_json_error([
            "message" => "Please fix the errors below.",
            "errors" => $errors,
        ]);
        return;
    }

    // Create the user
    $user_data = [
        "user_login" => $username,
        "user_email" => $email,
        "user_pass" => $password,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "role" => "subscriber", // Default role
    ];

    $user_id = wp_insert_user($user_data);

    // Check for errors
    if (is_wp_error($user_id)) {
        wp_send_json_error([
            "message" => "Registration failed: " . $user_id->get_error_message(),
        ]);
        return;
    }

    // Success! Auto-login the user
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    // Send success response
    wp_send_json_success([
        "message" => "Registration successful! Welcome to Operation Timeline.",
        "redirect" => home_url("/"), // Redirect to homepage
    ]);
}
